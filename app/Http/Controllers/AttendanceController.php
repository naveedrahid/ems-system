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
use App\Models\LeaveApplication;
use App\Models\Shift;

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

        $leaves = LeaveApplication::whereIn('user_id', $userIds)->select('start_date', 'end_date', 'status')->get();

        if ($request->ajax()) {
            $html = view('attendance.adminFilter.attendanceTable', compact('users', 'employees', 'attendance', 'month', 'year', 'holidays', 'leaves'))->render();

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

        return view('attendance.report', compact('departments', 'employees', 'users', 'attendance', 'month', 'year', 'departmentId', 'userId', 'leaves'));
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

        $userName = null;
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $userName = $user->name;
            }
        }
        $styles = view('attendance.partial.pdfFilterStyle', compact('imageHtml', 'month', 'year', 'userName'))->render();
        $leaves = LeaveApplication::whereIn('user_id', $userIds)->select('start_date', 'end_date', 'status')->get();

        $pdfContent = '';
        if (view()->exists('attendance.adminFilter.attendanceTable')) {
            $pdfContent = view('attendance.adminFilter.attendanceTable', compact('users', 'attendance', 'month', 'year', 'holidays', 'employees', 'leaves'))->render();
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
        $leaves = LeaveApplication::where('user_id', auth()->user()->id)->select('start_date', 'end_date', 'status')->get();

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
            $html = view('attendance.employeeFilter.attendanceTable', compact('attendance', 'month', 'year', 'holidays', 'leaves'))->render();

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
            $userName = auth()->user()->name;
            $styles = view('attendance.partial.pdfFilterStyle', compact('month', 'year', 'imageHtml', 'userName'))->render();
            $html = view('attendance.employeeFilter.attendanceTable', compact('attendance', 'month', 'year', 'holidays', 'leaves'))->render();

            $pdfContentWithLogo = $styles . $html;

            $dompdf->loadHtml($pdfContentWithLogo);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            return $dompdf->stream('employee_attendance_report.pdf', ['Attachment' => true]);
        }

        return view('attendance.attendanceFilter', compact('user', 'attendance', 'month', 'year', 'holidays', 'leaves'));
    }

    public function attendanceLog()
    {
        // Fetch all users
        $users = User::all();
    
        // Fetch all attendance records for the current month
        $startDate = Carbon::now()->startOfMonth();

        $endDate = Carbon::now()->endOfDay();

        $attendance = Attendance::whereBetween('attendance_date', [$startDate, $endDate])
                                ->orderBy('attendance_date', 'ASC')
                                ->get();
    
        // Fetch all holiday records
        $holidays = Holiday::all();
    
        // Fetch all leave applications
        $leaves = LeaveApplication::all();
    
        // Get all dates for the current month
        $allDates = Carbon::parse($startDate)->toPeriod($endDate, '1 day');
    
        // Group attendance records by date
        $attendanceByDate = $attendance->groupBy('attendance_date');
    
        return view('attendance.log', compact('attendance', 'users', 'attendanceByDate', 'allDates', 'holidays', 'leaves'));
    }
    
    public function AttendanceShow()
    {
        $currentUser = auth()->user()->id;
        $holidays = Holiday::all();
        $leaves = LeaveApplication::where('user_id', $currentUser)->select('start_date', 'end_date', 'status')->get();
        // dd($leaves);
        $users = User::where('id', $currentUser)->get();
        $attendance = Attendance::where('user_id', auth()->user()->id)->get();
        $currentMonth = date('m');
        $currentYear = date('Y');

        return view('attendance.attendance', compact('users', 'attendance', 'currentMonth', 'currentYear', 'holidays', 'leaves'));
    }

    public function downloadPdf()
    {
        $userId = auth()->user()->id;
        $data = [
            'attendance' => Attendance::where('user_id', $userId)->get(),
            'leaves' => LeaveApplication::where('user_id', $userId)->select('start_date', 'end_date', 'status')->get(),
            'holidays' => Holiday::all(),
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
        
        $employee = Employee::where('user_id', $userId)->first();

        if (!$employee || !$employee->shift_id)
            return response()->json(['message' => 'No shift assigned']);

            $shift = Shift::find($employee->shift_id);

        if (!$shift)
            return response()->json(['message' => 'Shift not found']);

        $checkInStatus = null;
        $grassTime = Carbon::parse($shift->opening)->addMinutes(21);
        $openingTime = Carbon::parse($shift->opening);
        $closingTime = Carbon::parse($shift->closing);

        if ($currentTime->greaterThan($grassTime)) {
            $checkInStatus = 'Late In';
        } elseif ($currentTime->greaterThan($openingTime) && $currentTime->lessThan($grassTime)) {
            $checkInStatus = 'In';
        } elseif ($currentTime->lessThan($openingTime)) {
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

        Mail::to($user->email)->send(new EmployeeAttendanceMail($user, $checkInStatus, $currentTime->toTimeString(), 'Check-In'));
        return response()->json(['message' => 'Check in successfully']);
    }

    public function hasCheckedIn()
    {
        $userId = Auth::id();
        $currentDate = now()->toDateString();

        $checkInAttendance = Attendance::where('user_id', $userId)
            ->whereDate('attendance_date', $currentDate)
            ->first();

        return response()->json(['checkedIn' => $checkInAttendance ? true : false]);
    }

    public function checkOutUser(Request $request)
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);
    
        $currentDate = now()->toDateString();
        $currentTime = now();
    
        $existingAttendance = Attendance::where('user_id', $userId)
            ->where('attendance_date', $currentDate)
            ->first();
    
        if ($existingAttendance && $existingAttendance->check_out) {
            return response()->json(['message' => 'You have already checked out.']);
        }
    
        $employee = Employee::where('user_id', $userId)->first();
    
        if (!$employee || !$employee->shift_id) {
            return response()->json(['message' => 'No shift assigned']);
        }
    
        $shift = Shift::find($employee->shift_id);
    
        if (!$shift) {
            return response()->json(['message' => 'Shift not found']);
        }
    
        $checkOutStatus = null;
        $graceTime = Carbon::parse($shift->closing)->addMinutes(21);
        $closingTime = Carbon::parse($shift->closing);
    
        if ($currentTime->lessThan($closingTime)) {
            $checkOutStatus = 'Early Out';
        } elseif ($currentTime->greaterThan($closingTime) && $currentTime->lessThan($graceTime)) {
            $checkOutStatus = 'Out';
        } elseif ($currentTime->greaterThan($graceTime)) {
            $checkOutStatus = 'Late Out';
        }
    
        $totalOvertime = null;
        if ($checkOutStatus === 'Late Out') {
            $checkOutDateTime = Carbon::parse($currentDate . ' ' . $currentTime->toTimeString());
            $overtime = $checkOutDateTime->diff($graceTime)->format('%H:%I');
            $totalOvertime = $overtime;
        }
    
        if ($existingAttendance) {
            $existingAttendance->update([
                'check_out' => $currentTime,
                'check_out_status' => $checkOutStatus,
                'total_overtime' => $totalOvertime,
            ]);
        } else {
            return response()->json(['message' => 'Attendance record not found.']);
        }
    
        Mail::to($user->email)->send(new EmployeeAttendanceMail($user, $checkOutStatus, $currentTime->toTimeString(), 'Check-Out'));
    
        return response()->json(['message' => 'Check out successfully']);
    }
    
    public function create()
    {
        $attendance = new Attendance();
        $route = route('attendance.store');
        $formMethod = 'POST';
        $isEdit = false;
        $userNames = User::all()->pluck('name', 'id');
        return view('attendance.form', compact('userNames', 'attendance', 'route', 'formMethod', 'isEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'attendance_date' => 'required',
        ]);

        $letInTime = Carbon::createFromTime(8, 21);
        $openTime = Carbon::createFromTime(8, 00);
        // $earlyInTime = Carbon::createFromTime(8, 0);
        $otTime = Carbon::createFromTime(17, 21);
        $closingTime = Carbon::createFromTime(17, 00);

        $checkInTime = Carbon::parse($request->check_in);
        $checkOutTime = Carbon::parse($request->check_out);

        $checkInStatus = null;
        if ($checkInTime->greaterThan($letInTime)) {
            $checkInStatus = 'Late In';
        } elseif ($checkInTime->lessThan($openTime)) {
            $checkInStatus = 'Early In';
        } elseif ($checkInTime->lessThan($letInTime) && $checkInTime->greaterThan($openTime)) {
            $checkInStatus = 'In';
        }

        $checkOutStatus = null;
        if ($checkOutTime->greaterThan($otTime)) {
            $checkOutStatus = 'Late Out';
        } elseif ($checkOutTime->lessThan($closingTime)) {
            $checkOutStatus = 'Early Out';
        } elseif ($checkOutTime->greaterThan($closingTime) && $checkOutTime->lessThan($otTime)) {
            $checkOutStatus = 'Out';
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

        return response()->json(['message' => 'Attendance created Successfully'], 200);
    }

    public function edit(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $route = route('attendance.update', $attendance->id);
        $formMethod = 'PUT';
        $isEdit = true;
        $userNames = User::all()->pluck('name', 'id');
        return view('attendance.form', compact('userNames', 'attendance', 'route', 'formMethod', 'isEdit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'attendance_date' => 'required',
        ]);

        $letInTime = Carbon::createFromTime(8, 21);
        $openTime = Carbon::createFromTime(8, 00);
        $otTime = Carbon::createFromTime(17, 21);
        $closingTime = Carbon::createFromTime(17, 00);

        $checkInTime = Carbon::parse($request->check_in);
        $checkOutTime = Carbon::parse($request->check_out);

        $checkInStatus = null;
        if ($checkInTime->greaterThan($letInTime)) {
            $checkInStatus = 'Late In';
        } elseif ($checkInTime->lessThan($openTime)) {
            $checkInStatus = 'Early In';
        } elseif ($checkInTime->lessThan($letInTime) && $checkInTime->greaterThan($openTime)) {
            $checkInStatus = 'In';
        }

        $checkOutStatus = null;
        if ($checkOutTime->greaterThan($otTime)) {
            $checkOutStatus = 'Late Out';
        } elseif ($checkOutTime->lessThan($closingTime)) {
            $checkOutStatus = 'Early Out';
        } elseif ($checkOutTime->greaterThan($closingTime) && $checkOutTime->lessThan($otTime)) {
            $checkOutStatus = 'Out';
        }

        $totalMinutes = calculateOvertime($request->check_in, $request->check_out);

        $attendance = Attendance::findOrFail($id);
        $attendance->update([
            'user_id' => $request->user_id,
            'attendance_date' => $request->attendance_date,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'check_in_status' => $checkInStatus,
            'check_out_status' => $checkOutStatus,
            'total_overtime' => $totalMinutes,
            'status' => 'Present',
        ]);

        return response()->json(['message' => 'Attendance updated successfully'], 200);
    }
}
