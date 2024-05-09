<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
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
        $users = User::get();
        // $roleId = Role::find($role_id);
        return view('users.users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all()->mapWithKeys(function ($role) {
            return [$role->id => ucwords(str_replace('-', ' ', $role->name))];
        });
        return view('users.create', compact('roles'));
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
            'phone_number' => 'required',
            'emergency_phone_number' => 'required',
            'emergency_person_name' => 'required',
            'employee_img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        $role = Role::find($request->user_role);
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
            'employee_img' => $imageName,
            'gender' => $request->gender,
        ]);

        return response()->json(['success' => 'User Created Successfully'], 200);
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
    public function edit(Request $request, $id)
    {
        $users = User::findOrFail($id);
        $employee = Employee::where('user_id', $id)->first();
        $roles = Role::pluck('name', 'id');
        return view('users.edit', compact('users', 'employee', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
            'phone_number' => 'required',
            'emergency_phone_number' => 'required',
            'emergency_person_name' => 'required',
            'employee_img' => $request->hasFile('employee_img') ? 'image|mimes:jpeg,png,jpg,gif|max:2048' : '',
            'gender' => 'required|in:male,female',
            'status' => 'required|in:active,deactive',
        ]);

        $user->update([
            'name' => $request->user_name,
            'email' => $request->user_email,
            'role_id' => $request->user_role,
            'status' => $request->status,
            'password' => Hash::make($request->user_password),
        ]);

        $role = Role::find($request->user_role);
        if ($role) {
            $user->role()->associate($role);
            $user->save();
        }
        $userData = [];

        if ($request->hasFile('employee_img')) {
            if ($user->employee && $user->employee->employee_img) {
                $imagePath = public_path('upload') . '/' . $user->employee->employee_img;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $imageName = time() . '.' . $request->employee_img->extension();
            $request->employee_img->move(public_path('upload'), $imageName);
            $userData['employee_img'] = $imageName;
        }

        if ($user->employee) {
            $user->employee()->update([
                'user_id' => $user->id,
                'date_of_birth' => $request->date_of_birth,
                'joining_date' => $request->joining_date,
                'fater_name' => $request->fater_name,
                'city' => $request->city,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'emergency_phone_number' => $request->emergency_phone_number,
                'emergency_person_name' => $request->emergency_person_name,
                'employee_img' => isset($userData['employee_img']) ? $userData['employee_img'] : $user->employee->employee_img,
                'gender' => $request->gender,
            ]);
        }

        return response()->json(['success' => 'User Updated Successfully'], 200);
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
