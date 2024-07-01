@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $employee->exists ? 'Edit Employee' : 'Add Employee' }}
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    {!! Form::model($employee, [
                        'url' => $route,
                        'method' => $formMethod,
                        'files' => true,
                        'id' => $employee->exists ? 'UpdateEmployee' : 'addEmployee',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                <label class="form-label">Name: <span class="text text-red">*</span></label>
                                {!! Form::label('title', 'Name') !!}
                                {!! Form::text('user_name', null, ['class' => 'form-control']) !!}
                                {{-- <input type="text" name="user_name" id="user_name" class="form-control"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Father Name') !!}
                                {!! Form::text('fater_name', null, ['class' => 'form-control']) !!}

                                {{-- <input type="text" name="fater_name" id="fater_name" class="form-control"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Email address') !!}
                                {!! Form::email('user_email', null, ['class' => 'form-control']) !!}
                                {{-- <input type="email" name="user_email" id="user_email" class="form-control"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'City') !!}
                                {!! Form::text('city', null, ['class' => 'form-control']) !!}
                                {{-- <input type="text" name="city" id="city" class="form-control"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Phone Number') !!}
                                {!! Form::tel('phone_number', null, ['class' => 'form-control']) !!}
                                {{-- <input type="tel" name="phone_number" id="phone_number" class="form-control"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Emergency Number') !!}
                                {!! Form::tel('emergency_phone_number', null, ['class' => 'form-control']) !!}
                                {{-- <input type="tel" name="" id="emergency_phone_number" class="form-control"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Emergency Person Name') !!}
                                {!! Form::text('emergency_person_name', null, ['class' => 'form-control']) !!}

                                {{-- <input type="text" name="" id="emergency_person_name"
                                        class="form-control"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Status') !!}
                                {!! Form::select(
                                    'status',
                                    ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'],
                                    $employee->status,
                                    ['class' => 'form-control form-select select2'],
                                ) !!}
                                {{-- <select name="status" id="status" class="form-control form-select select2"
                                        style="width: 100%;">
                                        <option value="">Select Status</option>
                                        @foreach (['active', 'deactive'] as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Employee Type') !!}
                                {!! Form::select(
                                    'employee_type_id',
                                    ['' => 'Select Employee Type'] + $employeeTypes->pluck('type', 'id')->toArray(),
                                    null,
                                    ['class' => 'form-control form-select select2', 'id' => 'employee_type_id'],
                                ) !!}
                                {{-- <select name="employee_type_id" id="employee_type_id"
                                        class="form-control form-select select2">
                                        <option value="">Select Employee Type</option>
                                        @foreach ($employeeTypes as $employeeType)
                                        <option value="{{ $employeeType->id }}">{{ $employeeType->type }}</option>
                                        @endforeach
                                    </select> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Employee Shift') !!}
                                {!! Form::select('shift_id', ['' => 'Select Shift'] + $employeeShift->pluck('name', 'id')->toArray(), null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'shift_id',
                                ]) !!}
                                {{-- <select name="shift_id" id="shift_id" class="form-control form-select select2">
                                    <option value="">Select Shift</option>
                                    @foreach ($employeeShift as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                    @endforeach
                                </select> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Work Type') !!}
                                <?php
                                $workTypes = ['fulltime' => 'Fulltime', 'parttime' => 'Parttime'];
                                ?>

                                {!! Form::select('work_type', ['' => 'Select Type'] + $workTypes, old('work_type'), [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'work_type',
                                    'style' => 'width: 100%;',
                                ]) !!}
                                {{-- <select name="work_type" id="work_type" class="form-control form-select select2"
                                    style="width: 100%;">
                                    <option value="">Select Type</option>
                                    @foreach (['fulltime', 'parttime'] as $workType)
                                        <option value="{{ $workType }}"
                                            {{ old('work_type') == $workType ? 'selected' : '' }}>
                                            {{ ucfirst($workType) }}</option>
                                    @endforeach
                                </select> --}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Employee Image') !!}
                                {!! Form::file('employee_img', null, ['class' => 'form-control']) !!}
                                {{-- <input type="file" class="form-control" name="" id="employee_img"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Department Name') !!}
                                {!! Form::select('department_id', ['' => 'Select Department'] + $departments, null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'department_id',
                                ]) !!}
                                {{-- <select name="department_id" id="department_id" class="form-control form-select select2"
                                    style="width: 100%;">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $id => $departmentName)
                                        <option value="{{ $id }}">{{ $departmentName }}</option>
                                    @endforeach
                                </select> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Designation') !!}
                                {!! Form::select(
                                    'designation_id',
                                    ['' => 'Select Designation'] + $designations->pluck('designation_name', 'id')->toArray(),
                                    null,
                                    [
                                        'class' => 'form-control form-select select2',
                                        'id' => 'designation_id',
                                        'style' => 'width: 100%;',
                                        'data-department-id' => '',
                                    ],
                                ) !!}

                                {{-- <select name="designation_id" id="designation_id"
                                    class="form-control form-select select2" style="width: 100%;">
                                </select> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Gender') !!}
                                {!! Form::select('gender', ['' => 'Select Status', 'male' => 'Male', 'female' => 'Female'], $employee->gender, [
                                    'class' => 'form-control form-select select2',
                                ]) !!}
                                {{-- <select name="gender" id="gender" class="form-control form-select select2"
                                    style="width: 100%;">
                                    <option value="">Select Gender</option>
                                    @foreach (['male', 'female'] as $status)
                                        <option value="{{ $status }}"
                                            {{ old('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}</option>
                                    @endforeach
                                </select> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Role') !!}
                                {!! Form::select('user_role', ['' => 'Select Department'] + $roles->pluck('name', 'id')->toArray(), null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'user_role',
                                ]) !!}
                                {{-- <select name="user_role" id="author_feature" class="form-control form-select select2"
                                    style="width: 100%;">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Date of birth') !!}
                                {!! Form::text('date_of_birth', null, ['class' => 'form-control']) !!}
                                {{-- <input type="date" name="" class="form-control" id="date_of_birth"
                                placeholder="Date of Birth"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Joining Date') !!}
                                {!! Form::date('joining_date', null, ['class' => 'form-control']) !!}
                                {{-- <input type="date" name="" id="joining_date" class="form-control"
                                id="dob" placeholder="Joining Date"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Address') !!}
                                {!! Form::text('address', null, ['class' => 'form-control']) !!}
                                {{-- <input type="text" name="" id="address" class="form-control" id="dob"
                                placeholder="Enter Your Address"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Password') !!}
                                {!! Form::password('user_password', null, ['class' => 'form-control']) !!}
                                {{-- <input type="password" name="user_password" id="user_password" class="form-control"> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Job Type') !!}
                                {!! Form::select(
                                    'job_type',
                                    ['' => 'Select Type', 'onsite' => 'On Site', 'remote' => 'Remote', 'hybrid' => 'Hybrid'],
                                    $employee->job_type,
                                    ['class' => 'form-control form-select select2'],
                                ) !!}
                                {{-- <select name="job_type" id="job_type" class="form-control form-select select2"
                                    style="width: 100%;">
                                    <option value="">Select Type</option>
                                    @foreach (['onsite', 'remote', 'hybrid'] as $jobType)
                                        <option value="{{ $jobType }}"
                                            {{ old('job_type') == $jobType ? 'selected' : '' }}>
                                            {{ ucfirst($jobType) }}</option>
                                    @endforeach
                                </select> --}}
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($employee->exists ? 'update' : 'create', ['class' => 'btn btn-primary setDisabled']) !!}
                        <a href="{{ route('employees.view') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}























                    {{-- <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data"
                        id="addEmployee">
                        @csrf
                        <div class="row">

                            
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="" class="btn btn-danger">Cancel</a>
                        </div>
                    </form> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('js')
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2();

        // Initially hide all designations
        $('#designation_id').find('option').hide();
        $('#designation_id').find('option[value=""]').show(); // Show the default option

        // On department change
        $('#department_id').change(function() {
            var selectedDepartment = $(this).val();

            // Hide all designations initially
            $('#designation_id').find('option').hide();
            $('#designation_id').find('option[value=""]').show(); // Show the default option

            // Show designations matching the selected department
            $('#designation_id').find('option').each(function() {
                var designationDepartment = $(this).data('department-id');
                if (designationDepartment == selectedDepartment) {
                    $(this).show();
                }
            });

            // Reset designation dropdown
            $('#designation_id').val('').trigger('change');
        });
    });
</script>
@endpush
