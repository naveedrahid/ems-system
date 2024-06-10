<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeAttendanceMail;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function showAttendanceReport()
    {
        $holidays = Holiday::all();
        $departments = Department::all();
        $employees = Employee::with('user')->get();
        $users = User::all();
        return view('attendance.report', compact('departments', 'employees', 'users', 'holidays'));
    }

    public function filterAttendanceReport(Request $request)
    {
        $holidays = Holiday::all();
        $employees = Employee::with('user')->get();
        $departments = Department::all();
        $users = User::all();

        $attendance = collect();
        $month = date('m');
        $year = date('Y');

        if ($request->has('department_id')) {
            $departmentId = $request->input('department_id');
        } else {
            $departmentId = null;
        }

        if ($request->has('user_id')) {
            $userId = $request->input('user_id');
        } else {
            $userId = null;
        }

        $employeesQuery = Employee::query();
        if ($departmentId) {
            $employeesQuery->where('department_id', $departmentId);
        }
        if ($userId) {
            $employeesQuery->where('user_id', $userId);
        }
        $employees = $employeesQuery->with('user')->get();
        $userIds = $employees->pluck('user_id');

        if ($request->has('month') && $request->has('year')) {
            $month = $request->input('month');
            $year = $request->input('year');
            $attendance = Attendance::whereIn('user_id', $userIds)
                ->whereYear('attendance_date', $year)
                ->whereMonth('attendance_date', $month)
                ->get();
        }

        if ($request->ajax()) {
            $html = view('attendance.adminFilter.attendanceTable', compact('users','employees', 'attendance', 'month', 'year', 'holidays'))->render();

            if ($attendance->isEmpty()) {
                $html = '<tr><td colspan="9" class="text-center">No record found</td></tr>';
            }

            return response()->json([
                'status' => 'success',
                'html' => $html,
                'download_url' => route('attendance.report.download', [
                    'department_id' => $departmentId,
                    'user_id' => $userId,
                    'month' => $month,
                    'year' => $year,
                ]),
            ]);
        }

        return view('attendance.report', compact('departments', 'employees', 'users', 'attendance', 'month', 'year', 'departmentId', 'userId'));
    }

    public function downloadAttendanceReport(Request $request)
    {
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id');
        $month = $request->input('month');
        $year = $request->input('year');
        $users = User::all();
        $holidays = Holiday::all();

        $employeesQuery = Employee::query();
        if ($departmentId) {
            $employeesQuery->where('department_id', $departmentId);
        }
        if ($userId) {
            $employeesQuery->where('user_id', $userId);
        }
        $employees = $employeesQuery->with('user')->get();
        $userIds = $employees->pluck('user_id');

        $attendance = Attendance::whereIn('user_id', $userIds)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->get();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        $imagePath = public_path('admin/images/Pixelz360.png');
        $image = base64_encode(file_get_contents($imagePath));
        $imageHtml = '<img src="data:image/png;base64,' . $image . '" width="100" height="100"/>';

        $styles = view('attendance.partial.pdfFilterStyle', compact('imageHtml', 'month', 'year'))->render();

        $pdfContent = '';
        if (view()->exists('attendance.adminFilter.attendanceTable')) {
            $pdfContent = view('attendance.adminFilter.attendanceTable', compact('users', 'attendance', 'month', 'year', 'holidays','employees'))->render();
        }

        $pdfContentWithLogo = $styles . $pdfContent;

        $dompdf->loadHtml($pdfContentWithLogo);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('attendance_report.pdf', ['Attachment' => true]);
    }

    public function AttendanceWithFilter(Request $request)
    {
        $holidays = Holiday::all();
        $user = User::where('id', auth()->user()->id)->get();
        $attendanceQuery = Attendance::where('user_id', auth()->user()->id);

        if ($request->has('month') && $request->has('year')) {
            $month = $request->input('month');
            $year = $request->input('year');
            $attendanceQuery->whereYear('attendance_date', $year)->whereMonth('attendance_date', $month);
        } else {
            $month = date('m');
            $year = date('Y');
        }

        $attendance = $attendanceQuery->get();

        if ($request->ajax()) {
            $html = view('attendance.employeeFilter.attendanceTable', compact('attendance', 'month', 'year', 'holidays'))->render();

            if ($attendance->isEmpty()) {
                $html = '<tr><td colspan="9" class="text-center">No record found</td></tr>';
            }
            $downloadUrl = route('attendance.filter', ['download' => 'pdf', 'month' => $month, 'year' => $year]);
            return response()->json([
                'status' => 'success',
                'html' => $html,
                'download_url' => $downloadUrl,
            ]);
        }

        // Handle PDF download
        if ($request->has('download') && $request->input('download') == 'pdf') {
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $dompdf = new Dompdf($options);

            // Generate the image HTML
            $imagePath = public_path('admin/images/Pixelz360.png');
            $image = base64_encode(file_get_contents($imagePath));
            $imageHtml = '<img src="data:image/png;base64,' . $image . '" width="200" height="200"/>';

            $styles = view('attendance.partial.pdfFilterStyle', compact('month', 'year', 'imageHtml'))->render();
            $html = view('attendance.employeeFilter.attendanceTable', compact('attendance', 'month', 'year', 'holidays'))->render();

            $pdfContentWithLogo = $styles . $html;

            $dompdf->loadHtml($pdfContentWithLogo);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            return $dompdf->stream('employee_attendance_report.pdf', ['Attachment' => true]);
        }

        return view('attendance.attendanceFilter', compact('user', 'attendance', 'month', 'year', 'holidays'));
    }

    public function attendanceLog()
    {
        $users = User::with('role')->get();
        $attendance = Attendance::paginate(10);
        return view('attendance.log', compact('users', 'attendance'));
    }

    public function AttendanceShow()
    {
        $holidays = Holiday::all();
        $users = User::where('id', auth()->user()->id)->get();
        $attendance = Attendance::where('user_id', auth()->user()->id)->get();
        $currentMonth = date('m');
        $currentYear = date('Y');

        return view('attendance.attendance', compact('users', 'attendance', 'currentMonth', 'currentYear', 'holidays'));
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
        $lateInTime = Carbon::createFromTime(8, 20);
        $earlyInTime = Carbon::createFromTime(8, 0);

        if ($currentTime->greaterThan($lateInTime)) {
            $checkInStatus = 'Late In';
        } elseif ($currentTime->greaterThan($earlyInTime) && $currentTime->lessThan($lateInTime)) {
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

        $user = User::find($userId);
        Mail::to($user->email)->cc('developer@pixelz360.com.au')->send(new EmployeeAttendanceMail($user, $checkInStatus, $currentTime->toTimeString()));

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
            // Mail::to($existingAttendance->user->email)->send(new EmployeeAttendanceMail(
            //     $existingAttendance->user,
            //     $checkOutStatus,
            //     $existingAttendance->check_in,
            //     $checkOutTime,
            //     true // Set isCheckOut to true
            // ));
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

        return redirect()->route('attendance.create')->with('success', 'Attendance created Successfully');
    }
}
