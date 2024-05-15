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
    public function create(leaveType $leaveType)
    {
        return view('leave-type.create', compact('leaveType'));
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
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveType $leaveType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaveType $leaveType)
    {
        return view('leave-type.edit', compact('leaveType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $validate = $request->validate([
            'name' => 'required|unique:leave_types,name',
            'description' => 'nullable',
            'default_balance' => 'required|numeric',
            'status' => 'required|in:active,deactive',
        ]);
        $leaveType->update($validate);
        return response()->json(['message' => 'Leave Type Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();
        return response()->json(['success' => 'Leave Type deleted successfully'], 200);
    }

    public function updateStatus(Request $request, LeaveType $leaveType)
    {
        $leaveType->status = $request->status;
        $leaveType->save();
        return response()->json(['message' => 'Status updated successfully'], 200);
    }

}
