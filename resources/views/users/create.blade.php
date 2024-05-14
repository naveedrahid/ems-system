@extends('masterLayout.app')
@section('main')
@section('page-title')
    create User
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('post_user') }}" enctype="multipart/form-data" id="addUsers">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Name: <span class="text text-red">*</span></label>
                                    <input type="text" name="user_name" id="user_name" class="form-control">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Father Name: <span class="text text-red">*</span></label>
                                    <input type="text" name="fater_name" id="fater_name" class="form-control">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Email address: <span class="text text-red">*</span></label>
                                    <input type="email" name="user_email" id="user_email" class="form-control">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">City: <span class="text text-red">*</span></label>
                                    <input type="text" name="city" id="city" class="form-control">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Phone Number: <span class="text text-red">*</span></label>
                                    <input type="tel" name="phone_number" id="phone_number" class="form-control">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Emergency Number: <span class="text text-red">*</span></label>
                                    <input type="tel" name="emergency_phone_number" id="emergency_phone_number"
                                        class="form-control">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Emergency Person Name: <span
                                            class="text text-red">*</span></label>
                                    <input type="text" name="emergency_person_name" id="emergency_person_name"
                                        class="form-control">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Status: <span class="text text-red">*</span></label>
                                    <select name="status" id="status" class="form-control form-select select2"
                                        style="width: 100%;">
                                        <option value="">Select Role</option>
                                        @foreach (['active', 'deactive'] as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="book_img">Add Employee Image:</label>
                                    <input type="file" class="form-control" name="employee_img" id="employee_img">
                                    <small class="label label-warning">Cover Photo will be uploaded</small>
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Department Name: <span class="text text-red">*</span></label>
                                    <select name="department_id" id="department_id" class="form-control form-select select2"
                                        style="width: 100%;">
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $id => $departmentName)
                                            <option value="{{ $id }}">{{ $departmentName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Designation: <span class="text text-red">*</span></label>
                                    <select name="designation_id" id="designation_id"
                                        class="form-control form-select select2" style="width: 100%;">
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Gender: <span class="text text-red">*</span></label>
                                    <select name="gender" id="gender" class="form-control form-select select2"
                                        style="width: 100%;">
                                        <option value="">Select Role</option>
                                        @foreach (['male', 'female'] as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Role: <span class="text text-red">*</span></label>
                                    <select name="user_role" id="author_feature" class="form-control form-select select2"
                                        style="width: 100%;">
                                        @if (isset($role))
                                            <option value="{{ $role->id }}" selected>{{ $role->name }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="dob">Date of birth: <span class="text text-red">*</span></label>
                                    <input type="date" name="date_of_birth" class="form-control" id="date_of_birth"
                                        placeholder="Date of Birth">
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="dob">Joining Date: <span class="text text-red">*</span></label>
                                    <input type="date" name="joining_date" id="joining_date" class="form-control"
                                        id="dob" placeholder="Joining Date">
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="dob">Address: <span class="text text-red">*</span></label>
                                    <input type="text" name="address" id="address" class="form-control"
                                        id="dob" placeholder="Enter Your Address">
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="user_password" id="user_password" class="form-control">
                                </div>
                            </div   >
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
