<?php

namespace App\Http\Controllers;

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

    // public function employeeDashboard()
    // {
    //     return view('employeeDashboard');
    // }

    public function dashboard()
    {
        $user = Auth::user();
        $employee = $user->employee;
    
        if ($employee) {
            $department = optional($employee->department)->department_name;
            $designation = optional($employee->designation)->designation_name;
        } else {
            $department = null;
            $designation = null;
        }
    
        return view('dashboard', compact('department', 'designation'));
    }    

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
