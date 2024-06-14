<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Employee;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $complaints = Complaint::all();
        // return view('complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $complaint = new Complaint();
        $route = route('complaints.store');
        $formMethod = 'POST';
        // getting user details
        $user = auth()->user();

        $employee = Employee::with(['designation', 'department', 'employeeType'])
            ->where('user_id', $user->id)
            ->select('id', 'user_id', 'department_id', 'designation_id', 'employee_type_id')
            ->first();

        $designationName = $employee->designation->designation_name ?? null;
        $departmentName = $employee->department->department_name ?? null;
        $employeeTypeName = $employee->employeeType->type ?? null;
        $ticketNumber = 'TICKET-' . strtoupper(bin2hex(random_bytes(8)));

        return view('complaints.form', compact('complaint', 'route', 'formMethod', 'designationName', 'departmentName', 'employeeTypeName', 'ticketNumber'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function show(Complaint $complaint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function edit(Complaint $complaint)
    {
        $route = route('complaints.update', $complaint->id);
        $formMethod = 'PUT';
        return view('complaints.form', compact('complaint', 'route', 'formMethod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Complaint $complaint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complaint $complaint)
    {
        //
    }
}
