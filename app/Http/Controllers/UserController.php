<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = User::whereIn('role_id', [1, 2])->with('employee')->get();
        return view('users.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser = auth()->user();
        if ($currentUser->role_id == 1) {
            $roles = Role::pluck('name', 'id');
        } else {
            $roles = Role::where('name', 'employee')->first();
        }
        $route = route('users.store');
        $formMethod = 'POST';
        $user = new User();
        $employee = new Employee();
        $departments = Department::pluck('department_name', 'id')->toArray();
        $designations = Designation::all();
        $employeeTypes = EmployeeType::all();
        $employeeShift = Shift::all();

        return view('users.form', compact('employee','formMethod', 'route', 'user', 'departments', 'designations', 'roles', 'employeeTypes', 'employeeShift'));
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
            'password' => 'required',
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
            'employee_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|in:male,female',
            'status' => 'required|in:active,deactive',
            'job_type' => 'required',
            'work_type' => 'required|in:fulltime,parttime',
            'employee_type_id' => 'required',
            'shift_id' => 'required'
        ]);

        $user = User::create([
            'name' => $request->user_name,
            'email' => $request->user_email,
            'role_id' => $request->user_role,
            'status' => $request->status,
            'job_type' => $request->job_type,
            'work_type' => $request->work_type,
            'job_type' => $request->job_type,
            'password' => Hash::make($request->user_password),
        ]);

        $role = Role::where('name', 'employee')->first();
        if ($role) {
            $user->role()->associate($role);
            $user->save();
        }

        $imageName = null;
        if ($request->hasFile('employee_img') && $request->file('employee_img')->isValid()) {
            $imageName = time() . '.' . $request->employee_img->extension();
            $request->employee_img->move(public_path('upload'), $imageName);
        }


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
            'shift_id' => $request->shift_id,
            'employee_type_id' => $request->employee_type_id,
        ]);

        return response()->json(['message' => 'User Created successfully created']);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        $employee = User::with('employee')->findOrFail($user->id);
        $route = route('users.update', $user->id);
        $formMethod = 'PUT';  // Assuming you're editing, so the method should be PUT
        $roles = Role::pluck('name', 'id');
        $departments = Department::pluck('department_name', 'id')->toArray();
        $designations = Designation::all();
        $employeeTypes = EmployeeType::all();
        $employeeShift = Shift::all();

        return view('users.form', compact('user', 'employee', 'roles', 'departments', 'designations', 'employeeTypes', 'employeeShift', 'route', 'formMethod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, User $user)
    {
        $request->validate([
            'user_name' => 'required',
            'user_email' => 'required|email',
            'user_role' => 'required',
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
            'employee_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|in:male,female',
            'status' => 'required|in:active,deactive',
            'job_type' => 'required',
            'work_type' => 'required|in:fulltime,parttime',
            'employee_type_id' => 'required',
            'shift_id' => 'required'
        ]);

        $userData = [
            'name' => $request->user_name,
            'email' => $request->user_email,
            'role_id' => $request->user_role,
            'status' => $request->status,
            'job_type' => $request->job_type,
            'work_type' => $request->work_type,
        ];

        $user->update($userData);

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
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'gender' => $request->gender,
            'shift_id' => $request->shift_id,
            'employee_type_id' => $request->employee_type_id,
        ];

        if ($request->hasFile('employee_img') && $request->file('employee_img')->isValid()) {
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

        $role = Role::find($request->user_role);
        if ($role) {
            $user->role()->associate($role);
            $user->save();
        }

        return response()->json(['message' => 'User Updated Successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['message' => 'Status updated successfully'], 200);
    }
}
