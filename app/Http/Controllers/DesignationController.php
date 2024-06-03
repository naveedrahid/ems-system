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
        $designations = Designation::with('department')->get();
        return view('designation.index', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        $designation = new Designation();
        $formMethod = 'POST';
        $route = route('designation.store');
        $departments = Department::pluck('department_name', 'id');
        return view('designation.form', compact('departments', 'designation', 'formMethod', 'route'));
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
            'department_id' => 'required',
            'designation_name' => 'required',
            'status' => 'required', 
        ]);

        // if ($validator->fails()) {
        //     return $this->sendErrorResponse($validator->errors());
        // }

        Designation::create($request->all());
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
    public function edit(Request $request, Designation $designation)
    {
        $formMethod = 'PUT';
        $route = route('designation.update', $designation->id);
        $departments = Department::pluck('department_name', 'id');
        return view('designation.form', compact('designation', 'departments', 'formMethod', 'route'));
    }    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Designation $designation)
    {
        $request->validate([
            'department_id' => 'required',
            'designation_name' => 'required',
            'status' => 'required',
        ]);
    
        $designation->update($request->all());
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
