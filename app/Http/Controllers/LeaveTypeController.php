<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaveTypes = LeaveType::all();
        return view('leave-type.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(leaveType $leave_type)
    {
        $leave_type = new LeaveType();
        $route = route('leave-types.store');
        $formMethod = 'POST';
        return view('leave-type.form', compact('leave_type', 'route', 'formMethod'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:leave_types,name',
            'description' => 'nullable',
            'default_balance' => 'required|numeric',
            'status' => 'required|in:active,deactive',
        ]);
        LeaveType::create($validate);

        return response()->json(['message', 'Leave Type Created Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeaveType  $leave_type
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveType $leave_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeaveType  $leave_type
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaveType $leave_type)
    {
        $route = route('leave-types.update', $leave_type->id);
        $formMethod = 'PUT';
        return view('leave-type.form', compact('leave_type', 'route', 'formMethod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaveType  $leave_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveType $leave_type)
    {
        $validate = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'default_balance' => 'required|numeric',
            'status' => 'required|in:active,deactive',
        ]);
        $leave_type->update($validate);
        return response()->json(['message' => 'Leave Type Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeaveType  $leave_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveType $leave_type)
    {
        $leave_type->delete();
        return response()->json(['message' => 'Leave Type deleted successfully'], 200);
    }

    public function updateStatus(Request $request, LeaveType $leave_type)
    {
        $leave_type->status = $request->status;
        $leave_type->save();
        return response()->json(['message' => 'Status updated successfully'], 200);
    }

}
