<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

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
        $department = new Department();
        $formMethod = 'POST';
        $route = route('department.store');
        return view('department.form', compact('department', 'formMethod', 'route'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
            'status' => 'required|in:active,deactive',
        ]);

        Department::create($request->all());

        return response()->json(['message' => 'Department created successfully'], 200);
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
    public function edit(Department $department)
    {
        // $department = Department::find($id);
        $formMethod = 'PUT';
        $route = route('department.update', $department->id);
        return view('department.form', compact('department', 'formMethod', 'route'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'department_name' => 'required',
            'status' => 'required|in:active,deactive',
        ]);

        $department->update($request->all());

        return response()->json(['message' => 'Department updated successfully'], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        // $department = Department::findOrFail($id);
        $department->delete();
        return response()->json(['success' => 'Department deleted successfully'], 200);
    }
    
    
}
