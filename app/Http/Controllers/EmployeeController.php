<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = User::with('employee.department', 'employee.designation')->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $role = Role::where('name', 'employee')->first();
        $departments = Department::pluck('department_name', 'id');
        $designations = Designation::pluck('designation_name', 'id');
        return view('employees.create', compact('departments', 'designations', 'role'));
    }


    public function getDesignations($departmentId)
    {
        $designations = Designation::where('department_id', $departmentId)->pluck('designation_name', 'id');
        return response()->json($designations);
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
            'user_name' => 'required',
            'user_email' => 'required|email',
            'user_role' => 'required',
            'user_password' => 'required',
            'date_of_birth' => 'required',
            'joining_date' => 'required',
            'fater_name' => 'required',
            'city' => 'required',
            'address' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',
            'phone_number' => 'required',
            'emergency_phone_number' => 'required',
            'emergency_person_name' => 'required',
            'employee_img' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|in:male,female',
            'status' => 'required|in:active,deactive',
        ]);

        $user = User::create([
            'name' => $request->user_name,
            'email' => $request->user_email,
            'role_id' => $request->user_role,
            'status' => $request->status,
            'password' => Hash::make($request->user_password),
        ]);

        $role = Role::where('name', 'employee')->first();
        if ($role) {
            $user->role()->associate($role);
            $user->save();
        }

        $imageName = time() . '.' . $request->employee_img->extension();
        $request->employee_img->move(public_path('upload'), $imageName);

        Employee::create([
            'user_id' => $user->id,
            'date_of_birth' => $request->date_of_birth,
            'joining_date' => $request->joining_date,
            'fater_name' => $request->fater_name,
            'city' => $request->city,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'emergency_phone_number' => $request->emergency_phone_number,
            'emergency_person_name' => $request->emergency_person_name,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'employee_img' => $imageName,
            'gender' => $request->gender,
        ]);

        return response()->json(['message' => 'Employee Created successfully created']);
        // return redirect()->route('employees.view')->with('message', 'Employee Created successfully created');
    }

    public function edit(Request $request, $id)
    {
        $employees = User::with('employee')->findOrFail($id);
        $roles = Role::pluck('name', 'id');
        $departments = Department::pluck('department_name', 'id');
        $designations = Designation::pluck('designation_name', 'id');

        return view('employees.edit', compact('employees', 'roles', 'departments', 'designations'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'user_name' => 'required',
            'user_email' => 'required|email',
            'user_role' => 'required',
            'user_password' => 'required',
            'date_of_birth' => 'required',
            'joining_date' => 'required',
            'fater_name' => 'required',
            'city' => 'required',
            'address' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',
            'phone_number' => 'required',
            'emergency_phone_number' => 'required',
            'emergency_person_name' => 'required',
            'employee_img' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|in:male,female',
            'status' => 'required|in:active,deactive',
        ]);

        $userData = [
            'name' => $request->user_name,
            'email' => $request->user_email,
            'role_id' => $request->user_role,
            'status' => $request->status,
        ];

        if ($request->filled('user_password') && $request->user_password != $user->password) {
            $userData['password'] = Hash::make($request->user_password);
        }

        $user->update($userData);

        $role = Role::find($request->user_role);
        if ($role) {
            $user->role()->associate($role);
        }

        $employeeData = [
            'user_id' => $user->id,
            'date_of_birth' => $request->date_of_birth,
            'joining_date' => $request->joining_date,
            'fater_name' => $request->fater_name,
            'city' => $request->city,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'emergency_phone_number' => $request->emergency_phone_number,
            'emergency_person_name' => $request->emergency_person_name,
            'gender' => $request->gender,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
        ];

        if ($request->hasFile('employee_img')) {
            if ($user->employee && $user->employee->employee_img) {
                $imagePath = public_path('upload') . '/' . $user->employee->employee_img;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $imageName = time() . '.' . $request->file('employee_img')->getClientOriginalExtension();
            $request->file('employee_img')->move(public_path('upload'), $imageName);
            $employeeData['employee_img'] = $imageName;
        }

        if ($user->employee) {
            $user->employee->update($employeeData);
        } else {
            $user->employee()->create($employeeData);
        }

        return response()->json(['success' => 'User Updated Successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    public function updateStatus(Request $request, $id)
    {
        $employee = User::findOrFail($id);
        $employee->status = $request->status;
        $employee->save();

        return response()->json(['message' => 'Status updated successfully'], 200);
    }
}
