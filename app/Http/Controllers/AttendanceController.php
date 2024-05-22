<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AttendanceController extends Controller
{

    public function AttendanceShow(){
        $users = User::Where('id', auth()->user()->id)->get();
        // $attendance = Attendance::all();
        $attendance = Attendance::Where('user_id', auth()->user()->id)->get();
    
        // Get the current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');
    
        return view('attendance.attendance', compact('users', 'attendance', 'currentMonth', 'currentYear'));
    }

    public function dailyReport() {
        $users = User::with('role')->get();
        $currentDate = Carbon::now()->format('Y-m-d');
        $attendance = Attendance::whereDate('attendance_date', $currentDate)->get();
        $authUserRoleId = auth()->user()->role_id;
        $authUserId = auth()->user()->id;
        return view('attendance.daily-report', compact('users', 'attendance', 'authUserRoleId', 'authUserId'));
    }
    

    public function checkInuser(Request $request)
    {
        $userId = Auth::id();
        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('attendance_date', now()->toDateString())
            ->first();
    
        if ($attendance) {
            return response()->json(['message' => 'Already checked in for today']);
        }
    
        Attendance::create([
            'user_id' => $userId,
            'attendance_date' => now()->toDateString(),
            'check_in' => now()->toTimeString(),
            'status' => 'present',
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
            return redirect()->back()->with('error', 'You have already checked out.');
        }
    
        $officeClosingTime = Carbon::createFromTime(17, 0, 0);
        $checkOutTime = now();
        $totalOvertime = null;
    
        if ($checkOutTime > $officeClosingTime) {
            $officeClosingDateTime = Carbon::createFromTime(17, 0, 0);
            $checkOutDateTime = Carbon::parse($date . ' ' . $time);
            $overtime = $checkOutDateTime->diff($officeClosingDateTime)->format('%H:%I');
            $totalOvertime = $overtime;
        }
    
        Attendance::updateOrCreate(
            ['user_id' => $user_id, 'attendance_date' => $date],
            [
                'check_out' => $time,
                'status' => $checkOutTime <= $officeClosingTime ? 'early_out' : 'present',
                'total_overtime' => $totalOvertime, 
            ]
        );
    
        return response()->json(['message' => 'Check out successfully']);
    }
}
