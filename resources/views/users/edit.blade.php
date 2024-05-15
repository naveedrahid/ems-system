@extends('masterLayout.app')
@section('main')
@section('page-title')
    create Edit
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('users.update', $users->id) }}" enctype="multipart/form-data"
                        id="UpdateUser">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Name: <span class="text text-red">*</span></label>
                                    <input type="text" name="user_name" id="user_name" class="form-control"
                                        value="{{ $employees->name }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Father Name: <span class="text text-red">*</span></label>
                                    <input type="text" name="fater_name" id="fater_name" class="form-control"
                                        value="{{ $employees->employee ? $employees->employee->fater_name : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Email address: <span class="text text-red">*</span></label>
                                    <input type="email" name="user_email" id="user_email" class="form-control"
                                        value="{{ $employees->email }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">City: <span class="text text-red">*</span></label>
                                    <input type="text" name="city" id="city" class="form-control"
                                        value="{{ $employees->employee ? $employees->employee->city : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Phone Number: <span class="text text-red">*</span></label>
                                    <input type="tel" name="phone_number" id="phone_number" class="form-control"
                                        value="{{ $employees->employee ? $employees->employee->phone_number : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Emergency Number: <span class="text text-red">*</span></label>
                                    <input type="tel" name="emergency_phone_number" id="emergency_phone_number"
                                        class="form-control"
                                        value="{{ $employees->employee ? $employees->employee->emergency_phone_number : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Emergency Person Name: <span
                                            class="text text-red">*</span></label>
                                    <input type="text" name="emergency_person_name" id="emergency_person_name"
                                        class="form-control"
                                        value="{{ $employees->employee ? $employees->employee->emergency_person_name : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Status: <span class="text text-red">*</span></label>
                                    <select name="status" id="status" class="form-control form-select select2"
                                        style="width: 100%;">
                                        <option value="active" {{ $employees->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="deactive" {{ $employees->status == 'deactive' ? 'selected' : '' }}>
                                            DeActive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="book_img">Add Employee Image: <span class="text text-red">*</span></label>
                                    <input type="file" class="form-control" name="employee_img" id="employee_img">
                                    {{-- <input type="text" class="form-control" name="employee_img_old" id="employee_img_old" value="{{($employees->employee) ? $employees->employee->employee_img : ''}}"> --}}
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Department Name: <span class="text text-red">*</span></label>
                                    <select name="department_id" id="department_id" class="form-control form-select select2"
                                        style="width: 100%;">
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $id => $departmentName)
                                            <option value="{{ $id }}"
                                                {{ optional($employees->employee)->department_id == $id ? 'selected' : '' }}>
                                                {{ $departmentName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3 form-group">
                                    <label class="form-label">Designation: <span class="text text-red">*</span></label>
                                    <select name="designation_id" id="designation_id"
                                        class="form-control form-select select2" style="width: 100%;">
                                        @foreach ($designations as $id => $designationName)
                                            <option value="{{ $id }}"
                                                {{ optional($employees->employee)->designation_id == $id ? 'selected' : '' }}>
                                                {{ $designationName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Gender: <span class="text text-red">*</span></label>
                                    <select name="gender" id="gender" class="form-control form-select select2"
                                        style="width: 100%;">
                                        <option value=""
                                            {{ is_null($employees->employee) || is_null($employees->employee->gender) ? 'selected' : '' }}>
                                        </option>
                                        <option value="male"
                                            {{ $employees->employee && $employees->employee->gender == 'male' ? 'selected' : '' }}>
                                            Male</option>
                                        <option value="female"
                                            {{ $employees->employee && $employees->employee->gender == 'female' ? 'selected' : '' }}>
                                            Female</option>
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Role: <span class="text text-red">*</span></label>
                                    <select name="user_role" id="author_feature" class="form-control form-select select2"
                                        style="width: 100%;">
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $roleId => $roleName)
                                            <option value="{{ $roleId }}"
                                                {{ $employees->role_id == $roleId ? 'selected' : '' }}>{{ $roleName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="dob">Date of birth: <span class="text text-red">*</span></label>
                                    <input type="date" name="date_of_birth" class="form-control" id="date_of_birth"
                                        placeholder="Date of Birth"
                                        value="{{ $employees->employee ? $employees->employee->date_of_birth : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="dob">Joining Date: <span class="text text-red">*</span></label>
                                    <input type="date" name="joining_date" id="joining_date" class="form-control"
                                        id="dob" placeholder="Joining Date"
                                        value="{{ $employees->employee ? $employees->employee->joining_date : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="dob">Address: <span class="text text-red">*</span></label>
                                    <input type="text" name="address" id="address" class="form-control"
                                        id="dob" placeholder="Enter Your Address"
                                        value="{{ $employees->employee ? $employees->employee->address : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('users') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
