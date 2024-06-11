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
                    <form method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data"
                        id="UpdateEmployee">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Name: <span class="text text-red">*</span></label>
                                    <input type="text" name="user_name" id="user_name" class="form-control"
                                        value="{{ $employee->name }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Father Name: <span class="text text-red">*</span></label>
                                    <input type="text" name="fater_name" id="fater_name" class="form-control"
                                        value="{{ $employee->employee ? $employee->employee->fater_name : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Email address: <span class="text text-red">*</span></label>
                                    <input type="email" name="user_email" id="user_email" class="form-control"
                                        value="{{ $employee->email }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">City: <span class="text text-red">*</span></label>
                                    <input type="text" name="city" id="city" class="form-control"
                                        value="{{ $employee->employee ? $employee->employee->city : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Phone Number: <span class="text text-red">*</span></label>
                                    <input type="tel" name="phone_number" id="phone_number" class="form-control"
                                        value="{{ $employee->employee ? $employee->employee->phone_number : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Emergency Number: <span class="text text-red">*</span></label>
                                    <input type="tel" name="emergency_phone_number" id="emergency_phone_number"
                                        class="form-control"
                                        value="{{ $employee->employee ? $employee->employee->emergency_phone_number : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Emergency Person Name: <span
                                            class="text text-red">*</span></label>
                                    <input type="text" name="emergency_person_name" id="emergency_person_name"
                                        class="form-control"
                                        value="{{ $employee->employee ? $employee->employee->emergency_person_name : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Status: <span class="text text-red">*</span></label>
                                    <select name="status" id="status" class="form-control form-select select2"
                                        style="width: 100%;">
                                        <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="deactive" {{ $employee->status == 'deactive' ? 'selected' : '' }}>
                                            DeActive
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="employee_type_id">Employee Type</label>
                                    <select name="employee_type_id" id="employee_type_id"
                                        class="form-control form-select select2">
                                        <option value="">Select Employee Type</option>
                                        {{-- @foreach ($employeeTypes as $employeeType)
                                            <option value="{{ $employeeType->id }}">{{ $employeeType->type }}</option>
                                            @endforeach --}}
                                        @foreach ($employeeTypes as $employeeType)
                                            <option value="{{ $employeeType->id }}"
                                                {{ optional($employee->employee)->employee_type_id == $employeeType->id ? 'selected' : '' }}>
                                                {{ $employeeType->type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="shift_id">Employee Shift</label>
                                    <select name="shift_id" id="shift_id" class="form-control form-select select2">
                                        <option value="">Select Shift</option>
                                        @foreach ($employeeShifts as $shift)
                                            <option value="{{ $shift->id }}"
                                                {{ optional($employee->employee)->shift_id == $shift->id ? 'selected' : '' }}>
                                                {{ $shift->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="book_img">Add Employee Image: <span class="text text-red">*</span></label>
                                    <input type="file" class="form-control" name="employee_img" id="employee_img">
                                    {{-- <input type="text" class="form-control" name="employee_img_old" id="employee_img_old" value="{{($employee->employee) ? $employee->employee->employee_img : ''}}"> --}}
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Department Name: <span class="text text-red">*</span></label>
                                    <select name="department_id" id="department_id"
                                        class="form-control form-select select2" style="width: 100%;">
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $id => $departmentName)
                                            <option value="{{ $id }}"
                                                {{ optional($employee->employee)->department_id == $id ? 'selected' : '' }}>
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
                                                {{ optional($employee->employee)->designation_id == $id ? 'selected' : '' }}>
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
                                            {{ is_null($employee->employee) || is_null($employee->employee->gender) ? 'selected' : '' }}>
                                        </option>
                                        <option value="male"
                                            {{ $employee->employee && $employee->employee->gender == 'male' ? 'selected' : '' }}>
                                            Male</option>
                                        <option value="female"
                                            {{ $employee->employee && $employee->employee->gender == 'female' ? 'selected' : '' }}>
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
                                                {{ $employee->role_id == $roleId ? 'selected' : '' }}>{{ $roleName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="dob">Date of birth: <span class="text text-red">*</span></label>
                                    <input type="date" name="date_of_birth" class="form-control" id="date_of_birth"
                                        placeholder="Date of Birth"
                                        value="{{ optional($employee->employee)->date_of_birth ? optional($employee->employee)->date_of_birth->format('Y-m-d') : '' }}">
                                </div>

                                <div class="mb-3 form-group">
                                    <label for="dob">Joining Date: <span class="text text-red">*</span></label>
                                    <input type="date" name="joining_date" id="joining_date" class="form-control"
                                        id="dob" placeholder="Joining Date"
                                        value="{{ $employee->employee ? $employee->employee->joining_date : '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="dob">Address: <span class="text text-red">*</span></label>
                                    <input type="text" name="address" id="address" class="form-control"
                                        id="dob" placeholder="Enter Your Address"
                                        value="{{ $employee->employee ? $employee->employee->address : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('employees.view') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
