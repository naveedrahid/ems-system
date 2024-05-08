<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designations = Designation::all();
        return view('designation.index', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::pluck('department_name', 'id');
        return view('designation.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'required',
            'designation_name' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse($validator->errors());
        }

        Designation::create([
            'department_id' => $request->department_id,
            'designation_name' => $request->designation_name,
            'status' => $request->status,
        ]);
        return response()->json(['message' => 'Role created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $designation = Designation::findOrFail($id);
        $departments = Department::pluck('department_name', 'id');
        return view('designation.edit', compact('designation', 'departments'));
    }    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $designation = Designation::findOrFail($id);
    
        $validatedData = $request->validate([
            'department_id' => 'required',
            'designation_name' => 'required',
            'status' => 'required',
        ]);
    
        $designation->update($validatedData);
        return response()->json(['message' => 'Designation updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();
        return response()->json(['success' => 'Designation deleted successfully'], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $designation = Designation::findOrFail($id);
        $designation->status = $request->status;
        $designation->save();
    
        return response()->json(['message' => 'Status updated successfully'], 200);
    }
    

}
