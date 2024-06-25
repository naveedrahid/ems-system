<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('employees.index');
    }

    public function getData()
    {
        $employees = User::join('employees', 'users.id', '=', 'employees.user_id')
            ->with('employee.department', 'employee.designation', 'employee.employeeType')
            ->select(['users.*', 'employees.employee_img', 'employees.gender', 'employees.id as employee_id'])
            ->get();
        return DataTables::of($employees)
            ->addColumn('action', function ($employee) {
                return '<a href="' . route('employees.edit', $employee->id) . '" class="btn btn-info btn-flat btn-sm">
                    <i class="fa fa-edit"></i>
                </a> <button class="employee-toggle btn btn-' . ($employee->status === "active" ? "info" : "danger") . ' btn-sm"
                    data-id="' . $employee->id . '" data-status="' . $employee->status . '">
                    <i class="fa fa-thumbs-' . ($employee->status === "active" ? "up" : "down") . '"></i>
                </button> <a href="' . route('employees.profile', $employee->id) . '" class="btn btn-info btn-flat btn-sm">
                    <i class="far fa-eye"></i>
                </a>';
            })
            ->editColumn('employee_img', function ($employee) {
                if (empty($employee->employee->employee_img)) {
                    if ($employee->gender === 'male') {
                        return '<img src="' . asset('admin/images/male.jpg') . '" width="60" height="60" alt="User Image">';
                    } elseif ($employee->gender === 'female') {
                        return '<img src="' . asset('admin/images/female.png') . '" width="60" height="60" alt="User Image">';
                    }
                } else {
                    return '<img src="' . asset('upload/' . optional($employee->employee)->employee_img) . '" width="60" height="60" alt="User Image">';
                }
            })
            ->editColumn('name', function ($employee) {
                $user = auth()->user();
                if (isAdmin($user)) {
                    return $employee->name . '<br><a href="' . route('bank-details.create', ['employee_id' => $employee->employee_id]) . '">
                    <i class="fa fa-building-columns"></i></a>';
                } else {
                    return $employee->name;
                }
            })
            ->editColumn('department', function ($employee) {
                return optional($employee->employee->department)->department_name ?: 'No department';
            })
            ->editColumn('designation', function ($employee) {
                return optional($employee->employee->designation)->designation_name ?: 'No Designation';
            })
            ->editColumn('employee_type_id', function ($employee) {
                return optional($employee->employee->employeeType)->type ?? '';
            })
            ->editColumn('shift_id', function ($employee) {
                return optional($employee->employee->shift)->name ?? '';
            })
            ->rawColumns(['employee_img', 'name', 'employee_type_id', 'shift_id', 'action', 'status'])
            ->make(true);
    }

    public function create()
    {
        $currentUser = auth()->user();
        if ($currentUser->role_id === 1) {
            $roles = Role::all();
        } else {
            $roles = Role::where('name', 'employee')->get();
        }
        $departments = Department::pluck('department_name', 'id');
        $designations = Designation::pluck('designation_name', 'id');
        $employeeTypes = EmployeeType::all();
        $employeeShift = Shift::all();
        return view('employees.create', compact('departments', 'designations', 'roles', 'employeeTypes', 'employeeShift'));
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
            'employee_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|in:male,female',
            'status' => 'required|in:active,deactive',
            'employee_type_id' => 'required',
            'shift_id' => 'required'
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
            'employee_type_id' => $request->employee_type_id,
            'shift_id' => $request->shift_id,
        ]);

        return response()->json(['message' => 'Employee Created successfully created']);
    }

    public function edit(Request $request, $id)
    {
        $employee = User::with('employee')->findOrFail($id);
        $roles = Role::pluck('name', 'id');
        $departments = Department::pluck('department_name', 'id');
        $designations = Designation::pluck('designation_name', 'id');
        $employeeShifts = Shift::all();
        $employeeTypes = EmployeeType::all();

        return view('employees.edit', compact('employee', 'roles', 'departments', 'designations', 'employeeShifts', 'employeeTypes'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

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
            'employee_type_id' => $request->employee_type_id,
            'shift_id' => $request->shift_id,
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

    public function employeeProfile(Request $request, $id)
    {
        $user = auth()->user();

        if (isAdmin($user)) {
            $employee = User::with(['employee.bank'])->findOrFail($id);
            return view('employees.profile', compact('employee'));
        } 
        return view('employees.profile', compact('employee'));
    }

    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*[0-9].*[0-9])(?=.*[@#!]).*$/'
            ],
        ]);

        $user->password = Hash::make($request->new_password);
        $user->save();
        Auth::logout();

        return response()->json(['message' => 'Password changed successfully', 'logout' => true], 200);
    }
}
