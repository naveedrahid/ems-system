@extends('masterLayout.app')
@section('main')
@section('page-title')
    Add Employee
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    {{-- addEmployee --}}
                    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data" id="addEmployee">
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
                                        <option value="">Select Status</option>
                                        @foreach (['active', 'deactive'] as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="employee_type_id">Employee Type</label>
                                    <select name="employee_type_id" id="employee_type_id"
                                        class="form-control form-select select2">
                                        <option value="">Select Employee Type</option>
                                        @foreach ($employeeTypes as $employeeType)
                                            <option value="{{ $employeeType->id }}">{{ $employeeType->type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="shift_id">Employee Shift</label>
                                    <select name="shift_id" id="shift_id"
                                        class="form-control form-select select2">
                                        <option value="">Select Shift</option>
                                        @foreach ($employeeShift as $shift)
                                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Work Type: <span class="text text-red">*</span></label>
                                    <select name="work_type" id="work_type" class="form-control form-select select2"
                                        style="width: 100%;">
                                        <option value="">Select Type</option>
                                        @foreach (['fulltime', 'parttime'] as $workType)
                                            <option value="{{ $workType }}"
                                                {{ old('work_type') == $workType ? 'selected' : '' }}>
                                                {{ ucfirst($workType) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="book_img">Add Employee Image: <span class="text text-red">*</span></label>
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
                                        <option value="">Select Gender</option>
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
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
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
                                <div class="mb-3 form-group">
                                    <label class="form-label">Job Type: <span class="text text-red">*</span></label>
                                    <select name="job_type" id="job_type" class="form-control form-select select2"
                                        style="width: 100%;">
                                        <option value="">Select Type</option>
                                        @foreach (['onsite', 'remote', 'hybrid'] as $jobType)
                                            <option value="{{ $jobType }}"
                                                {{ old('job_type') == $jobType ? 'selected' : '' }}>
                                                {{ ucfirst($jobType) }}</option>
                                        @endforeach
                                    </select>
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
{{-- @push('js')
<script>
    $(document).ready(function() {
        $('#addEmployee').submit(function(e) {
            e.preventDefault();

            const user_name = $('input[name="user_name"]').val().trim();
            const fater_name = $('input[name="fater_name"]').val().trim();
            const user_email = $('input[name="user_email"]').val().trim();
            const city = $('input[name="city"]').val().trim();
            const phone_number = $('input[name="phone_number"]').val().trim();
            const emergency_phone_number = $('input[name="emergency_phone_number"]').val().trim();
            const emergency_person_name = $('input[name="emergency_person_name"]').val().trim();
            const gender = $('select[name="gender"]').val().trim();
            const date_of_birth = $('input[name="date_of_birth"]').val().trim();
            const joining_date = $('input[name="joining_date"]').val().trim();
            const address = $('input[name="address"]').val().trim();
            const user_role = $('select[name="user_role"]').val().trim();
            const status = $('select[name="status"]').val().trim();
            const department_id = $('select[name="department_id"]').val().trim();
            // const designation_id = $('select[name="designation_id"]').val().trim();
            const employee_type_id = $('select[name="employee_type_id"]').val().trim();

            emergency_person_name
            if (user_name == '' || fater_name == '' || user_email == '' || city == '' || phone_number ==
                '' || emergency_phone_number == '' || emergency_person_name == '' || gender == '' ||
                date_of_birth == '' || joining_date == '' || address == '' || user_role == '' ||
                status == '' || department_id == '' || employee_type_id == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'All Fields cannot be empty.',
                });
                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(function(response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    });
                    $('#addEmployee')[0].reset();
                })
                .catch(function(xhr) {
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to create Employee.',
                    });
                });
        });
    });
</script>
@endpush --}}
