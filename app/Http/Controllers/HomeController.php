<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
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
    
        // Fetch all leave types
        $leaveTypes = LeaveType::all();
    
        // Fetch approved leave applications for the user
        $leaveApplications = LeaveApplication::where('employee_id', $user->id)
            ->where('status', 'Approved')
            ->get();
    
        // Group leave applications by leave type and calculate total availed leaves
        $availedLeaves = $leaveApplications->groupBy('leave_type_id')->map(function ($leaves) {
            return $leaves->sum('total_leave');
        });
    
        // Calculate remaining leaves
        $remainingLeaves = $leaveTypes->mapWithKeys(function ($leaveType) use ($availedLeaves) {
            $availedLeaveCount = $availedLeaves->get($leaveType->id, 0);
            $remainingLeaveCount = $leaveType->default_balance - $availedLeaveCount;
            return [$leaveType->id => $remainingLeaveCount];
        });
    
        return view('dashboard', compact(
            'designation', 
            'departmentName', 
            'employeesByDepartment', 
            'leaveTypes', 
            'availedLeaves',
            'remainingLeaves'
        ));
    }
    
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
