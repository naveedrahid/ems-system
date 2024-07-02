@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $users->exists ? 'Edit Shift' : 'Create Shift' }}
@endsection
@section('page-content')
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card small-box card-primary p-5">
                    {!! Form::model($users, [
                        'url' => $route,
                        'method' => $formMethod,
                        'files' => true,
                        'id' => $users->exists ? 'UpdateEmployee' : 'addEmployee',
                    ]) !!}

                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {!! Form::label('user_name', 'Name') !!}
                                {!! Form::text('user_name', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('father_name', 'Father Name') !!}
                                {!! Form::text('fater_name', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('user_email', 'Email address') !!}
                                {!! Form::email('user_email', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('city', 'City') !!}
                                {!! Form::text('city', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('phone_number', 'Phone Number') !!}
                                {!! Form::tel('phone_number', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('emergency_phone_number', 'Emergency Number') !!}
                                {!! Form::tel('emergency_phone_number', null, [
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('emergency_person_name', 'Emergency Person Name') !!}
                                {!! Form::text('emergency_person_name', null, [
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('status', 'Status') !!}
                                {{-- {!! Form::select('status', ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'], null, [
                                    'class' => 'form-control form-select select2',
                                ]) !!} --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('employee_type_id', 'Employee Type') !!}
                                {{-- {!! Form::select(
                                    'employee_type_id',
                                    ['' => 'Select Employee Type'] + $employeeTypes->pluck('type', 'id')->toArray(),
                                    null,
                                    ['class' => 'form-control form-select select2', 'id' => 'employee_type_id'],
                                ) !!} --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('shift_id', 'Employee Shift') !!}
                                {{-- {!! Form::select('shift_id', ['' => 'Select Shift'] + $employeeShift->pluck('name', 'id')->toArray(), null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'shift_id',
                                ]) !!} --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('work_type', 'Work Type') !!}
                                {{-- {!! Form::select('work_type', ['' => 'Select Type', 'fulltime' => 'Fulltime', 'parttime' => 'Parttime'], null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'work_type',
                                    'style' => 'width: 100%;',
                                ]) !!} --}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {!! Form::label('employee_img', 'Employee Image') !!}
                                {!! Form::file('employee_img', ['class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('department_id', 'Department Name') !!}
                                {{-- {!! Form::select('department_id', ['' => 'Select Department'] + $departments, null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'department_id',
                                ]) !!} --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('designation_id', 'Designation') !!}
                                {{-- <select id="designation_id" name="designation_id" class="form-control form-select select2"
                                    style="width: 100%;">
                                    <option value="">Select Designation</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}"
                                            data-department-id="{{ $designation->department_id }}"
                                            {{ isset($employee->employee) && $employee->employee->designation_id == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->designation_name }}
                                        </option>
                                    @endforeach
                                </select> --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('gender', 'Gender') !!}
                                {!! Form::select('gender', ['' => 'Select Gender', 'male' => 'Male', 'female' => 'Female'], null, [
                                    'class' => 'form-control form-select select2',
                                ]) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {{-- {!! Form::label('role_id', 'Role') !!} --}}
                                {{-- @php
                                    $rolesArray = $roles->pluck('name', 'id')->toArray();
                                @endphp --}}
                                    {{-- {{dd($rolesArray)}} --}}
                                {{-- {!! Form::select('user_role', $rolesArray, null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'user_role',
                                ]) !!} --}}
                                {{-- {!! Form::select('user_role', ['' => 'Select Role'] + $roles->pluck('name', 'id')->toArray(), null, [
                                    'class' => 'form-control form-select select2',
                                ]) !!} --}}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('date_of_birth', 'Date of birth') !!}
                                {!! Form::text('date_of_birth', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('joining_date', 'Joining Date') !!}
                                {!! Form::date('joining_date', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('address', 'Address') !!}
                                {!! Form::text('address', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::label('job_type', 'Job Type') !!}
                                {{-- {!! Form::select(
                                    'job_type',
                                    ['' => 'Select Type', 'onsite' => 'On Site', 'remote' => 'Remote', 'hybrid' => 'Hybrid'],
                                    null,
                                    ['class' => 'form-control form-select select2'],
                                ) !!} --}}
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        {!! Form::submit($users->exists ? 'update' : 'create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('users.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
    @push('js')
        <script>
            $(document).ready(function() {
                $('#addUsers').submit(function(e) {
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

                    emergency_person_name
                    if (user_name == '' || fater_name == '' || user_email == '' || city == '' || phone_number ==
                        '' || emergency_phone_number == '' || emergency_person_name == '' || gender == '' ||
                        date_of_birth == '' || joining_date == '' || address == '' || user_role == '' ||
                        status == '') {
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
                            $('#addUsers')[0].reset();
                            $('#user_name').val('');
                            $('#fater_name').val('');
                            $('#user_email').val('');
                            $('#city').val('');
                            $('#phone_number').val('');
                            $('#emergency_phone_number').val('');
                            $('#emergency_person_name').val('');
                            $('#user_role').val('');
                            $('#gender').val('');
                            $('#date_of_birth').val('');
                            $('#joining_date').val('');
                            $('#address').val('');
                            $('#status').val('');
                            // window.location.reload(); // or redirect to a different page
                        })
                        .catch(function(xhr) {
                            console.error(xhr);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to create Designation.',
                            });
                        });
                });
            });
        </script>
    @endpush
