@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $employee->exists ? 'Edit Employee' : 'Add Employee' }}
@endsection
@section('page-content')
    {!! Form::model($employee, [
        'url' => $route,
        'method' => $formMethod,
        'files' => true,
        'id' => $employee->exists ? 'UpdateEmployee' : 'addEmployee',
    ]) !!}
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card small-box card-primary">
                <div class="card-header bg-white">
                    <h4 class="card-title text-bold">Personal Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('user_name', 'Name') !!}
                                {!! Form::text('user_name', $employee->name ?? '', ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('employee_img', 'Photo') !!}
                                <div class="input-group">
                                    <div class="custom-file">
                                        {!! Form::file('employee_img', ['class' => 'custom-file-input']) !!}
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('fater_name', 'Father Name') !!}
                                {!! Form::text('fater_name', $employee->employee->fater_name ?? '', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Fater Name',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('date_of_birth', 'Date of birth') !!}
                                {!! Form::date('date_of_birth', $employee->employee->date_of_birth ?? '', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Date of birth',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('gender', 'Gender') !!}
                                {!! Form::select(
                                    'gender',
                                    ['' => 'Select Gender', 'male' => 'Male', 'female' => 'Female'],
                                    $employee->employee->gender ?? '',
                                    ['class' => 'form-control form-select select2'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('phone_number', 'Phone Number') !!}
                                {!! Form::tel('phone_number', $employee->employee->phone_number ?? '', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Phone Number',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('country', 'Country *') !!}
                                {!! Form::select('country', ['' => 'Select Country'] + $countries, old('country', $employee->country ?? ''), [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'country',
                                    'placeholder' => 'Country',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('city', 'City') !!}
                                {!! Form::select(
                                    'city',
                                    ['' => 'Select City'] + (isset($cities[$employee->country_id]) ? $cities[$employee->country_id]->toArray() : []),
                                    old('city', $employee->city),
                                    ['class' => 'form-control form-select select2', 'id' => 'city'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('address', 'Address') !!}
                                {!! Form::text('address', $employee->employee->address ?? '', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Address',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('role_id', 'Role') !!}
                                {!! Form::text('user_roles', $roles->name ?? '', [
                                    'class' => 'form-control',
                                    'readonly',
                                ]) !!}
                                {!! Form::hidden('user_role', $roles->id ?? '', [
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card small-box card-primary">
                <div class="card-header bg-white">
                    <h4 class="card-title text-bold">Company Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('employee_type_id', 'Employee Type') !!}
                                {!! Form::select(
                                    'employee_type_id',
                                    ['' => 'Select Employee Type'] + $employeeTypes->pluck('type', 'id')->toArray(),
                                    $employee->employee->employee_type_id ?? '',
                                    ['class' => 'form-control form-select select2', 'id' => 'employee_type_id', 'placeholder' => 'Employee Type'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('shift_id', 'Employee Shift') !!}
                                {!! Form::select(
                                    'shift_id',
                                    ['' => 'Select Shift'] + $employeeShift->pluck('name', 'id')->toArray(),
                                    $employee->employee->shift_id ?? '',
                                    ['class' => 'form-control form-select select2', 'id' => 'shift_id'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('department_id', 'Department Name') !!}
                                {!! Form::select(
                                    'department_id',
                                    ['' => 'Select Department'] + $departments,
                                    $employee->employee->department_id ?? '',
                                    ['class' => 'form-control form-select select2', 'id' => 'department_id'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('designation_id', 'Designation') !!}
                                <select id="designation_id" name="designation_id" class="form-control form-select select2"
                                    style="width: 100%;">
                                    <option value="">Select Designation</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}"
                                            data-department-id="{{ $designation->department_id }}"
                                            {{ isset($employee->employee) && $employee->employee->designation_id == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->designation_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('job_type', 'Job Type') !!}
                                {!! Form::select(
                                    'job_type',
                                    ['' => 'Select Type', 'onsite' => 'On Site', 'remote' => 'Remote', 'hybrid' => 'Hybrid'],
                                    $employee->job_type ?? '',
                                    ['class' => 'form-control form-select select2'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('work_type', 'Work Type') !!}
                                {!! Form::select(
                                    'work_type',
                                    ['' => 'Select Type', 'fulltime' => 'Fulltime', 'parttime' => 'Parttime'],
                                    $employee->work_type ?? '',
                                    ['class' => 'form-control form-select select2', 'id' => 'work_type', 'style' => 'width: 100%;'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('status', 'Status') !!}
                                {!! Form::select(
                                    'status',
                                    ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'],
                                    $employee->status ?? '',
                                    ['class' => 'form-control form-select select2'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('joining_date', 'Joining Date') !!}
                                {!! Form::date('joining_date', $employee->employee->joining_date ?? '', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Joining Date',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card small-box card-primary">
                <div class="card-header bg-white">
                    <h4 class="card-title text-bold">Emergency Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('emergency_phone_number', 'Emergency Number') !!}
                                {!! Form::tel('emergency_phone_number', $employee->employee->emergency_phone_number ?? '', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Emergency Number',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('emergency_person_name', 'Emergency Person Name') !!}
                                {!! Form::text('emergency_person_name', $employee->employee->emergency_person_name ?? '', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Emergency Person Name',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card small-box card-primary">
                <div class="card-header bg-white">
                    <h4 class="card-title text-bold">Employee Account</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-12">
                            @if ($formMethod === 'POST')
                                <div class="mb-3 form-group">
                                    {!! Form::label('title', 'Password') !!}
                                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('user_email', 'Email address') !!}
                                {!! Form::email('user_email', $employee->email ?? '', ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        {!! Form::submit($employee->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary setDisabled']) !!}
        <a href="{{ route('employees.view') }}" class="btn btn-danger">Cancel</a>
    </div>
    {!! Form::close() !!}
@endsection
@endsection

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .form-control:focus {
        border: 1.4px solid #007BFF !important;
    }

    form#addEmployee .form-control,
    form#addEmployee .select2-selection {
        height: calc(2.25rem) !important;
        border-radius: 10px !important;
    }

    span.select2-selection.select2-selection--single {
        height: 40px;
    }

    /* .form-control {
        height: calc(2rem + 0px) !important;
        border-radius: 10px !important;
    } */

    .card-header {
        border-top-left-radius: 0.85rem !important;
        border-top-right-radius: .85rem !important;
    }

    label {
        margin-left: 3px;
        display: inline-block;
        margin-bottom: 4px;
    }

    /* .form-control {
        height: calc(2rem + 0px) !important;
    } */

    label:not(.form-check-label):not(.custom-file-label) {
        font-weight: 500 !important;
    }

    /* .form-control:focus {
        border: 1.4px solid #007BFF !important;
    } */

    form * {
        font-size: 15px !important;
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        $('#addEmployee, #UpdateEmployee').submit(function(e) {
            e.preventDefault();

            const submitButton = $('input[type="submit"]');
            const fields = [{
                    name: 'user_name',
                    message: 'User name is required'
                },
                {
                    name: 'fater_name',
                    message: 'Father name is required'
                },
                {
                    name: 'user_email',
                    message: 'User email is required'
                },
                {
                    name: 'country',
                    message: 'Country is required'
                },
                {
                    name: 'city',
                    message: 'City is required'
                },
                {
                    name: 'phone_number',
                    message: 'Phone number is required'
                },
                {
                    name: 'emergency_phone_number',
                    message: 'Emergency phone number is required'
                },
                {
                    name: 'emergency_person_name',
                    message: 'Emergency person name is required'
                },
                {
                    name: 'gender',
                    message: 'Gender is required'
                },
                {
                    name: 'date_of_birth',
                    message: 'Date of birth is required'
                },
                {
                    name: 'joining_date',
                    message: 'Joining date is required'
                },
                {
                    name: 'address',
                    message: 'Address is required'
                },
                {
                    name: 'status',
                    message: 'Status is required'
                },
                {
                    name: 'department_id',
                    message: 'Department is required'
                },
                {
                    name: 'employee_type_id',
                    message: 'Employee type is required'
                },
                {
                    name: 'designation_id',
                    message: 'Designation is required'
                },
                {
                    name: 'shift_id',
                    message: 'Shift is required'
                },
                {
                    name: 'work_type',
                    message: 'Work type is required'
                },
                {
                    name: 'job_type',
                    message: 'Job type is required'
                }
            ];

            let isValid = true;
            fields.forEach(function(field) {
                const fieldElement = $('[name="' + field.name + '"]');
                if (fieldElement.length > 0) {
                    const value = fieldElement.val().trim();
                    if (value === '') {
                        toastr.error(field.message);
                        isValid = false;
                    }
                } else {
                    toastr.error('Field ' + field.name + ' is missing in the form.');
                    isValid = false;
                }
            });

            if (!isValid) {
                return;
            }

            submitButton.prop('disabled', true);
            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                    url: url,
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(function(response) {
                    toastr.success(response.message);
                    submitButton.prop('disabled', false);
                    if ($(e.target).attr('id') === 'addEmployee') {
                        $('#addEmployee')[0].reset();
                    }
                })
                .catch(function(xhr) {
                    console.error(xhr);
                    toastr.success(response.message);
                    submitButton.prop('disabled', false);
                });
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('select').select2();
        $('#department_id, #designation_id').select2();

        $('#designation_id option').not(':first').each(function() {
            const option = $(this);
            option.data('select2-hidden', true);
        });

        $('#designation_id').select2({
            templateResult: function(option) {
                if ($(option.element).data('select2-hidden')) {
                    return null;
                }
                return option.text;
            }
        });

        $('#department_id').on('change', function() {
            const selectedDepartmentId = $(this).val();

            $('#designation_id option').not(':first').each(function() {
                const option = $(this);
                option.data('select2-hidden', true);
            });

            if (selectedDepartmentId) {
                $('#designation_id option').each(function() {
                    if ($(this).data('department-id') == selectedDepartmentId) {
                        $(this).data('select2-hidden', false);
                    }
                });
            }

            $('#designation_id').select2({
                templateResult: function(option) {
                    if ($(option.element).data('select2-hidden')) {
                        return null;
                    }
                    return option.text;
                }
            });
        });
    });

    $(document).ready(function() {
        const cities = @json($cities);

        function populateCities(countryId, selectedCityId = null) {
            const countryCities = cities[countryId] || {};
            const $citySelect = $('#city');
            $citySelect.empty().append(new Option('Select City', ''));

            $.each(countryCities, function(cityId, cityName) {
                $citySelect.append(new Option(cityName, cityId));
            });

            if (selectedCityId) {
                $citySelect.val(selectedCityId);
            }
        }

        const initialCountryId = $('#country').val();
        const initialCityId = '{{ old('city', $employee->city) }}';
        if (initialCountryId) {
            populateCities(initialCountryId, initialCityId);
        }

        $('#country').on('change', function() {
            const countryId = $(this).val();
            populateCities(countryId);
        });
    });
</script>
@endpush
