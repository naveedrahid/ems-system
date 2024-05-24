<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AttendanceController extends Controller
{

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
            $checkInStatus = 'late in';
        } elseif ($currentTime->lessThan($earlyInTime)) {
            $checkInStatus = 'early in';
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

    public function create(){
        $userNames = User::all();
        return view('attendance.create', compact('userNames'));
    }

    public function store(Request $request){
        $request->validate([
            'user_id' => 'required',
            'attendance_date' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
        ]);
        $checkInStatus = null;
        $currentTime = now();
        $letInTime = Carbon::createFromTime(8, 30);
        $earlyInTime = Carbon::createFromTime(8, 0);
        
        if ($currentTime->greaterThan($letInTime)) {
            $checkInStatus = 'late in';
        } elseif ($currentTime->lessThan($earlyInTime)) {
            $checkInStatus = 'early in';
        }

        $officeClosingTime = Carbon::createFromTime(17, 0, 0);
        $checkOutTime = now();
        $checkOutStatus = $checkOutTime <= $officeClosingTime ? 'Early Out' : 'Late Out';
        // $totalOvertime = null;
        $totalMinutes = calculateOvertime($request->check_in, $request->check_out);
        Attendance::create([
            'user_id' => $request->user_id,
            'attendance_date' => $request->attendance_date,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'check_in_status' => $checkInStatus,
            'check_out_status' => $checkOutStatus,
            'total_overtime' => $totalMinutes,
        ]);
        return redirect()->route('attendance')->with('success', 'Attendance created Successfully');
    }

}
