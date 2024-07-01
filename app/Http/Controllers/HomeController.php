<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\Notice;
use App\Models\TimeLog;
use App\Models\User;
use Attribute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth.login');
    }


    public function dashboard()
    {
        //get use upcomming birthday code
        $currentDate = Carbon::now();

        $startOfMonth = $currentDate->copy()->startOfMonth();
        $userBirthdays = getUpcomingBirthdays();

        //get use upcomming holidays
        $holidayDate = Carbon::now()->format('Y-m-d');
        $holidays = Holiday::orderBy('date', 'ASC')->get()->filter(function ($holiday) use ($holidayDate) {
            $startDate = explode(' - ', $holiday->date)[0];
            return $startDate >= $holidayDate;
        });

        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found');
        }

        $departmentId = $employee->department_id;
        $department = Department::find($departmentId);
        $departmentName = $department ? $department->department_name : null;
        $designation = $employee->designation ? $employee->designation->designation_name : null;
        $employeesByDepartment = Employee::where('department_id', $departmentId)->get();
        $leaveTypes = LeaveType::where('status', 'active')->get();
        $leaveApplications = LeaveApplication::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->get();
        $availedLeaves = $leaveApplications->groupBy('leave_type_id')->map(function ($leaves) {
            return $leaves->sum('total_leave');
        });

        $remainingLeaves = $leaveTypes->mapWithKeys(function ($leaveType) use ($availedLeaves) {
            $availedLeaveCount = $availedLeaves->get($leaveType->id, 0);
            $remainingLeaveCount = $leaveType->default_balance - $availedLeaveCount;
            return [$leaveType->id => $remainingLeaveCount];
        });

        $activeEmployees = User::where('status', 'active')->get();
        $activeEmployeeCount = $activeEmployees->count();

        $leaveQuery = LeaveApplication::with(['user', 'leaveType', 'employee'])->orderBy('id', 'DESC')->get();
        $notices = Notice::where('status', 'active')->orderBy('id', 'ASC')->take(10)->get();

        $attendanceCount = Attendance::whereDate('attendance_date', now()->toDateString())->select('status', 'check_in');
        $timeLogId = TimeLog::whereDate('created_at', now()->toDateString())
        ->where('user_id', auth()->id())
        ->pluck('id')
        ->first();
            
        return view('dashboard', compact(
            'designation',
            'departmentName',
            'employeesByDepartment',
            'leaveTypes',
            'availedLeaves',
            'remainingLeaves',
            'userBirthdays',
            'holidays',
            'activeEmployeeCount',
            'leaveQuery',
            'notices',
            'activeEmployees',
            'attendanceCount',
            'timeLogId'
        ));
    }

    // public function startTimeIsExist(){
    //     // $startTimeTracking = $timeLog->get()->first();
    //     return view('dashboard', compact('startTimeTracking'));
    // }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
