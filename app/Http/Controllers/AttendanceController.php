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
use Illuminate\Support\Facades\DB;

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
    
        $employees = Employee::where('department_id', $departmentId)->get();
        $userIds = $employees->pluck('user_id');
    
        if ($userId) {
            $userIds = collect([$userId]);
        }
    
        $startDate = Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-01");
        $endDate = $startDate->copy()->endOfMonth();
        $currentDate = now()->format('Y-m-d');
    
        $days = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $days[$date->format('Y-m-d')] = [
                'date' => $date->format('Y-m-d'),
                'dayOfWeek' => $date->format('N'),
                'isHoliday' => in_array($date->dayOfWeek, [6, 7]),
                'attendanceStatus' => '',
            ];
        }
    
        $attendanceRecords = Attendance::whereIn('user_id', $userIds)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->get();
    
        foreach ($days as $day => &$data) {
            if ($data['isHoliday']) {
                $data['attendanceStatus'] = 'Holiday';
            } elseif ($day <= $currentDate) {
                $attendanceCount = $attendanceRecords->where('attendance_date', $day)->count();
                if ($attendanceCount == 0) {
                    $data['attendanceStatus'] = 'Absent';
                } else {
                    $data['attendanceStatus'] = 'Present';
                }
            }
        }
    
        return response()->json(['days' => $days, 'attendance' => $attendanceRecords]);
    }
    



    // public function filterAttendanceReport(Request $request)
    // {
    //     $attendance = Attendance::where('user_id', $request->user_id)
    //         ->whereYear('attendance_date', $request->year)
    //         ->whereMonth('attendance_date', $request->month)
    //         ->with('user')
    //         ->get();

    //     $currentMonthDates = collect();
    //     $startDate = Carbon::createFromDate($request->year, $request->month, 1);
    //     $endDate = $startDate->copy()->endOfMonth();
    //     $currentMonthDates = $currentMonthDates->merge($startDate->toPeriod($endDate, '1 day'));

    //     $weekendDates = $currentMonthDates->filter(function ($date) {
    //         return $date->isWeekend();
    //     })->pluck('date')->toArray();

    //     $otherHolidays = Holiday::whereRaw("YEAR(date) = ? AND MONTH(date) = ?", [$request->year, $request->month])
    //         ->whereNotIn(DB::raw("DATE(date)"), $weekendDates)
    //         ->pluck('date')
    //         ->toArray();

    //     $holidays = array_merge($weekendDates, $otherHolidays);

    //     $attendanceDataForMonth = [];

    //     foreach ($currentMonthDates as $date) {
    //         $formattedDate = $date->format('Y-m-d');
    //         $attendanceData = $attendance->where('attendance_date', $formattedDate)->first();

    //         if ($attendanceData) {
    //             $attendanceDataForMonth[] = $attendanceData;
    //         } else {
    //             $isHoliday = in_array($formattedDate, $holidays);
    //             $attendanceDataForMonth[] = [
    //                 'attendance_date' => $formattedDate,
    //                 'status' => $isHoliday ? 'Holiday' : 'Absent',
    //                 'total_hours' => null,
    //                 'user' => null, // Assuming you want to include user information for each date
    //             ];
    //         }
    //     }

    //     return response()->json(['attendance' => $attendanceDataForMonth]);
    // }



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
        $letInTime = Carbon::createFromTime(8, 30);
        $earlyInTime = Carbon::createFromTime(8, 0);

        if ($currentTime->greaterThan($letInTime)) {
            $checkInStatus = 'Late In';
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

        $officeClosingTime = Carbon::createFromTime(17, 0, 0);
        $checkOutTime = now();
        $checkOutStatus = $checkOutTime <= $officeClosingTime ? 'Early Out' : 'Late Out';
        $totalOvertime = null;

        if ($checkOutStatus === 'Late Out') {
            $officeClosingDateTime = Carbon::createFromTime(17, 0, 0);
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

        $letInTime = Carbon::createFromTime(8, 30);
        // $earlyInTime = Carbon::createFromTime(8, 0);
        $officeClosingTime = Carbon::createFromTime(17, 0);

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
