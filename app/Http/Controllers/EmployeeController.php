<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
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
use Illuminate\Support\Facades\Cache;
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
        $userId = auth()->user()->id;
        $employees = User::join('employees', 'users.id', '=', 'employees.user_id')
            ->with('employee.department', 'employee.designation', 'employee.employeeType')
            ->where('users.role_id', '!=', 0)
            ->select(['users.*', 'employees.employee_img', 'employees.gender', 'employees.id as employee_id'])
            ->get();
        return DataTables::of($employees)
            ->addColumn('action', function ($employee) use ($userId) {

                $action = '<div class="manage-process">';
                if ($employee->id !== $userId) {
                    $statusClass = $employee->status === 'active' ? 'active-badge' : ($employee->status === 'pending' ? 'pending-badge' : 'deactive-badge');
                    $action .= '<a href="#" class="status-toggle empoyee-toggle" data-id="' . $employee->id . '" data-status="' . $employee->status . '">
                        <span class="badges ' . $statusClass . '">' . ucfirst($employee->status) . '</span>
                    </a>';
                }
                $action .= '<a href="' . route('employees.edit', $employee->id) . '" class="edit-item">
                <i class="fa fa-edit"></i> edit</a>
                <a href="' . route('employees.profile', $employee->id) . '" class="edit-item">
                <i class="far fa-eye"></i> View</a><a href="javascript:;" data-delete-route="' . route('employees.destroy', $employee->employee->id) . '" class="edit-item delete-employee">
                <i class="far fa-trash-alt"></i> Delete</a></div>';
                return $action;
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
                    return '<h6>' . $employee->name . '</h6>' . '<span>' . optional($employee->employee->department)->department_name . '</span> - <span>' . optional($employee->employee->designation)->designation_name . '</span>';
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
        $roles = Role::where('name', 'Employee')->first();
        $route = route('employees.store');
        $formMethod = 'POST';
        $employee = new Employee();
        $departments = Department::pluck('department_name', 'id')->toArray();
        $designations = Designation::all();
        $employeeTypes = EmployeeType::all();
        $employeeShift = Shift::all();
        $countries = Country::pluck('name', 'id')->toArray();
        $cities = City::all()->groupBy('country_id')->map(function ($cityGroup) {
            return $cityGroup->pluck('name', 'id');
        })->toArray();

        return view('employees.form', compact('route', 'formMethod', 'employee', 'departments', 'designations', 'roles', 'employeeTypes', 'employeeShift', 'countries', 'cities'));
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
            'country' => 'required',
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
            'job_type' => 'required',
            'work_type' => 'required|in:fulltime,parttime',
            'shift_id' => 'required'
        ]);

        $user = User::create([
            'name' => $request->user_name,
            'email' => $request->user_email,
            'role_id' => $request->user_role,
            'job_type' => $request->job_type,
            'work_type' => $request->work_type,
            'status' => $request->status,
            'city' => $request->city,
            'country' => $request->country,
            'password' => Hash::make($request->password),
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
            'city' => $request->city
        ]);

        return response()->json(['message' => 'Employee Created successfully created']);
    }

    public function edit(Request $request, $id)
    {
        $employee = User::with('employee')->findOrFail($id);
        $route = route('employees.update', $employee->id);
        $formMethod = 'PUT';
        $currentUser = auth()->user();
        $roles = Role::where('name', 'Employee')->first();
        $departments = Department::pluck('department_name', 'id')->toArray();
        $designations = Designation::all();
        $employeeShift = Shift::all();
        $employeeTypes = EmployeeType::all();
        $countries = Country::pluck('name', 'id')->toArray();
        $cities = City::all()->groupBy('country_id')->map(function ($cityGroup) {
            return $cityGroup->pluck('name', 'id');
        })->toArray();

        return view('employees.form', compact('roles', 'formMethod', 'route', 'employee', 'departments', 'designations', 'employeeShift', 'employeeTypes', 'countries', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'user_name' => 'required',
            'user_email' => 'required|email',
            'date_of_birth' => 'required',
            'joining_date' => 'required',
            'fater_name' => 'required',
            'country' => 'required',
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
            'job_type' => 'required',
            'work_type' => 'required|in:fulltime,parttime',
        ]);

        $userData = [
            'name' => $request->user_name,
            'email' => $request->user_email,
            'role_id' => $request->user_role,
            'status' => $request->status,
            'job_type' => $request->job_type,
            'work_type' => $request->work_type,
            'city' => $request->city,
            'country' => $request->country,
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
        return response()->json(['message' => 'User Updated Successfully'], 200);
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
        } else {
            $employee = User::with(['employee.bank'])->findOrFail($user->id);
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

    public function destroy(Request $request, $id)
    {
        $employee = Employee::find($id);

        if ($employee) {
            $userId = $employee->user_id;

            $user = User::find($userId);

            if ($user) {
                $user->delete();
            }

            return response()->json(['message' => 'User deleted successfully'], 200);
        }

        return response()->json(['message' => 'Employee not found'], 404);
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'employee_img' => 'required|file|mimes:png,jpeg,jpg,webp|max:1024',
        ]);

        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->first();
    
        if ($request->hasFile('employee_img')) {
            if ($employee->employee_img) {
                $imagePath = public_path('upload') . '/' . $employee->employee_img;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $imageName = time() . '.' . $request->file('employee_img')->getClientOriginalExtension();
            $request->file('employee_img')->move(public_path('upload'), $imageName);
            $employee->employee_img = $imageName;
            $employee->save();
    
            return response()->json(['image' => $imageName, 'message' => 'Profile image updated successfully!'], 200);
        }
    
        return response()->json(['message' => 'No image uploaded.'], 400);
    }
    
    
}
