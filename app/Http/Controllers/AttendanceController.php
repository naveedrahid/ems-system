<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function showAttendanceReport()
    {
        $departments = Department::all();
        $employees = Employee::with('user')->get();
        $users = User::all();
        return view('attendance.report', compact('departments', 'employees', 'users'));
    }

    public function filterAttendanceReport(Request $request)
    {
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id');
        $month = $request->input('month');
        $year = $request->input('year');

        $employeesQuery = Employee::query();
        if ($departmentId) {
            $employeesQuery->where('department_id', $departmentId);
        }
        if ($userId) {
            $employeesQuery->where('user_id', $userId);
        }
        $employees = $employeesQuery->with('user')->get();
        $userIds = $employees->pluck('user_id');

        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $attendanceRecords = Attendance::whereIn('user_id', $userIds)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->with('user')
            ->get();

        $days = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $isWeekend = $date->isWeekend();
            $attendanceData = $attendanceRecords->where('attendance_date', $formattedDate)->first();

            $days[] = [
                'date' => $formattedDate,
                'displayDate' => $date->format('l - d M, Y'),
                'isWeekend' => $isWeekend,
                'attendanceData' => $attendanceData,
                'user' => $attendanceData ? $attendanceData->user : null,
                'check_in' => $attendanceData ? $attendanceData->check_in : null,
                'check_out' => $attendanceData ? $attendanceData->check_out : null,
                'total_overtime' => $attendanceData ? $attendanceData->total_overtime : null
            ];
        }

        return response()->json(['days' => $days, 'employees' => $employees]);
    }

    public function AttendanceWithFilter(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();

        $attendance = collect();

        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        if ($request->has('month') && $request->has('year')) {
            $attendance = Attendance::where('user_id', auth()->user()->id)
                ->whereYear('attendance_date', $year)
                ->whereMonth('attendance_date', $month)
                ->get();
        }

        if ($request->ajax()) {
            $html = view('attendance.attendanceTable', compact('user', 'attendance', 'month', 'year'))->render();

            if ($attendance->isEmpty()) {
                $html = '<tr><td colspan="9" class="text-center">No record found</td></tr>';
            }

            return response()->json([
                'status' => 'success',
                'html' => $html,
            ]);
        }

        return view('attendance.attendanceFilter', compact('user', 'attendance', 'month', 'year'));
    }

    public function attendanceLog()
    {
        $users = User::with('role')->get();
        $attendance = Attendance::all();
        return view('attendance.log', compact('users', 'attendance'));
    }

    public function AttendanceShow()
    {
        $users = User::where('id', auth()->user()->id)->get();
        $attendance = Attendance::where('user_id', auth()->user()->id)->get();
        $currentMonth = date('m');
        $currentYear = date('Y');

        return view('attendance.attendance', compact('users', 'attendance', 'currentMonth', 'currentYear'));
    }

    public function downloadPdf()
    {
        $data = [
            'attendance' => Attendance::where('user_id', auth()->user()->id)->get(),
            'currentYear' => date('Y'),
            'currentMonth' => date('m'),
        ];

        $pdf = new Dompdf();
        $html = view('attendance.table', $data)->render();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        return $pdf->stream('attendance.pdf');
    }

    public function dailyReport()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $attendanceQuery = Attendance::whereDate('attendance_date', $currentDate);
        $users = auth()->user()->isAdmin() ? User::with('role')->get() : [auth()->user()];
        $attendance = auth()->user()->isAdmin() ? $attendanceQuery->get() : $attendanceQuery->where('user_id', auth()->user()->id)->get();
        $authUserRoleId = auth()->user()->role_id;
        $authUserId = auth()->user()->id;

        return view('attendance.daily-report', compact('users', 'attendance'));
    }

    public function checkInuser(Request $request)
    {
        $userId = Auth::id();
        $currentDate = now()->toDateString();
        $currentTime = now();
        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('attendance_date', $currentDate)
            ->first();

        if ($attendance) {
            return response()->json(['message' => 'Already checked in for today']);
        }

        $checkInStatus = null;
        $letInTime = Carbon::createFromTime(8, 20);
        $earlyInTime = Carbon::createFromTime(8, 0);

        if ($currentTime->greaterThan($letInTime)) {
            $checkInStatus = 'Late In';
        } elseif ($currentTime->greaterThan($earlyInTime) && $currentTime->lessThan($letInTime)) {
            $checkInStatus = 'In';
        } elseif ($currentTime->lessThan($earlyInTime)) {
            $checkInStatus = 'Early In';
        }

        Attendance::create([
            'user_id' => $userId,
            'attendance_date' => $currentDate,
            'check_in' => $currentTime->toTimeString(),
            'status' => 'present',
            'check_in_status' => $checkInStatus,
        ]);
        return response()->json(['message' => 'Check in successfully']);
    }

    public function checkOutUser(Request $request)
    {
        $user_id = $request->user_id;
        $date = now()->format('Y-m-d');
        $time = now()->format('H:i:s');
        $existingAttendance = Attendance::where('user_id', $user_id)
            ->where('attendance_date', $date)
            ->first();

        if ($existingAttendance && $existingAttendance->check_out) {
            return response()->json(['message' => 'You have already checked out.']);
        }

        $checkOutTime = now();
        $earlyCheckOutTime = Carbon::createFromTime(17, 0, 0);
        $lateCheckOutTime = Carbon::createFromTime(17, 20, 0);

        if ($checkOutTime->between($earlyCheckOutTime, $lateCheckOutTime)) {
            $checkOutStatus = 'Out';
        } elseif ($checkOutTime->lessThanOrEqualTo($earlyCheckOutTime)) {
            $checkOutStatus = 'Early Out';
        } else {
            $checkOutStatus = 'Late Out';
        }

        $totalOvertime = null;
        if ($checkOutStatus === 'Late Out') {
            $officeClosingDateTime = Carbon::createFromTime(17, 20, 0);
            $checkOutDateTime = Carbon::parse($date . ' ' . $time);
            $overtime = $checkOutDateTime->diff($officeClosingDateTime)->format('%H:%I');
            $totalOvertime = $overtime;
        }

        Attendance::updateOrCreate(
            ['user_id' => $user_id, 'attendance_date' => $date],
            [
                'check_out' => $time,
                'status' => 'present',
                'check_out_status' => $checkOutStatus,
                'total_overtime' => $totalOvertime,
            ]
        );

        return response()->json(['message' => 'Check out successfully']);
    }


    public function create()
    {
        $userNames = User::all();
        return view('attendance.create', compact('userNames'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'attendance_date' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
        ]);

        $letInTime = Carbon::createFromTime(8, 20);
        // $earlyInTime = Carbon::createFromTime(8, 0);
        $officeClosingTime = Carbon::createFromTime(17, 20);

        $checkInTime = Carbon::parse($request->check_in);
        $checkOutTime = Carbon::parse($request->check_out);

        $checkInStatus = null;
        if ($checkInTime->greaterThan($letInTime)) {
            $checkInStatus = 'Late In';
        } elseif ($checkInTime->lessThan($letInTime)) {
            $checkInStatus = 'Early In';
        }

        $checkOutStatus = null;
        if ($checkOutTime->lessThanOrEqualTo($officeClosingTime)) {
            $checkOutStatus = 'Early Out';
        } else {
            $checkOutStatus = 'Late Out';
        }

        $totalMinutes = calculateOvertime($request->check_in, $request->check_out);

        Attendance::create([
            'user_id' => $request->user_id,
            'attendance_date' => $request->attendance_date,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'check_in_status' => $checkInStatus,
            'check_out_status' => $checkOutStatus,
            'total_overtime' => $totalMinutes,
            'status' => 'Present',
        ]);

        return redirect()->route('attendance')->with('success', 'Attendance created Successfully');
    }
}
