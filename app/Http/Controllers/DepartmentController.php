<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::get();
        return view('department.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('department.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
            'status' => 'required|in:active,deactive',
        ]);

        Department::create([
            'department_name' => $request->department_name,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Role created successfully'], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department, $id)
    {
        $department = Department::find($id);
        return view('department.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // Log::info('Updating department with ID ' . $department->id . ' to name: ' . $request->department_name . ', status: ' . $request->status);

        // $validatedData = $request->validate([
        //     'department_name' => 'required|string|max:255',
        //     'status' => 'required|string|in:active,inactive',
        // ]);

        $department = Department::where('id', $id)->first();
        // dd($id, $request->toArray(), $department->toArray());

        $department->update([
            'department_name' => $request->department_name,
            'status' => $request->status,
        ]);

        // Log::info('Department updated successfully');

        return response()->json(['message' => 'Department updated successfully']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return response()->json(['success' => 'Department deleted successfully'], 200);
    }
    
    
}
