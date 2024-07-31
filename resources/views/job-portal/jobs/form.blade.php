@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $job->exists ? 'Edit job' : 'Create job' }}
@endsection
@section('page-content')
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card small-box card-primary p-5 position-relative">
                    <div id="loadingSpinner" style="display: none; text-align: center;">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                    </div>
                    {!! Form::model($job, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => $job->exists ? 'updateJobHandler' : 'createJobHandler',
                        'files' => true,
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Job Created By') !!}
                                {!! Form::text('created_by', $createrName, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Job Title') !!}
                                {!! Form::text('title', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('department_id', 'Department Name') !!}
                                {!! Form::select('department_id', ['' => 'Select Department'] + $departments, null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'department_id',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('designation_id', 'Designation') !!}
                                <select id="designation_id" name="designation_id" class="form-control form-select select2"
                                    style="width: 100%;">
                                    <option value="">Select Designation</option>
                                    @foreach ($designations as $id => $data)
                                        <option value="{{ $id }}"
                                            data-department-id="{{ $data['department_id'] }}"
                                            {{ $job->designation_id == $id ? 'selected' : '' }}>
                                            {{ $data['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('shift_id', 'Shift') !!}
                                {!! Form::select('shift_id', ['' => 'Select Shift'] + $shifts, null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'shift_id',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('employment_type', 'Employment Type') !!}
                                {!! Form::select('employment_type', $employmentTypes, null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'employment_type',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('location', 'Location') !!}
                                {!! Form::text('location', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('salary_range', 'Salary Range') !!}
                                {!! Form::text('salary_range', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('closing_date', 'Closing Date') !!}
                                {!! Form::date('closing_date', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('job_img', 'Thumbnail') !!}
                                {!! Form::file('job_img', ['id' => 'job_img', 'class' =>'form-control']) !!}
                                <img id="job_img_preview" src="{{ $job->job_img ? asset($job->job_img) : '' }}" style="{{ $job->job_img ? 'display: block;' : 'display: none;' }}" alt="Job Image Preview">
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::textarea('description', old('description'), [
                                    'id' => 'awardEditor',
                                    'cols' => 30,
                                    'rows' => 10,
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($job->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary setDisabled']) !!}
                        <a href="{{ route('jobs.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    img#job_img_preview {
    width: 100%;
    height: 240px;
    object-fit: contain;
    border: solid 1px #cccc;
    padding: 10px;
    margin-top: 20px;
    margin-bottom: 35px;
    border-radius: 10px;
    box-shadow: #00000024 0px 0px 10px 0px;
    transform: scale(1);
    transition: 1s ease;
}
    #job_img_preview {
        transition: 1s ease;
        transform: scale(1);
    }

    #job_img_preview:hover{
        transform: scale(1.040);
        transition: 1s ease;
    }
    span.select2-selection.select2-selection--single {
        height: 40px;
    }

    a.filepond--credits {
        display: none;
    }

    div#loadingSpinner {
        position: fixed;
        left: 0;
        right: 0;
        margin: auto;
        top: 0;
        bottom: 0;
        z-index: 99;
        background: #00000036;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    div#loadingSpinner i {
        color: #007bff;
    }
</style>
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.1.1/tinymce.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
<script>
    tinymce.init({
        selector: 'textarea#awardEditor',
        branding: false,
        plugins: 'code table lists',
        menubar: false,
        statusbar: false,
        toolbar: 'bold italic underline | fontsizeselect | forecolor | bullist numlist | alignleft aligncenter alignright | link | blocks',
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
</script>


{{-- <script>
    $(document).ready(function() {
        FilePond.registerPlugin(FilePondPluginFileValidateSize, FilePondPluginImagePreview);
        const job_img = FilePond.create(document.querySelector('input[name="job_img"]'));

        $('#createJobHandler, #updateJobHandler').submit(function(e) {
            e.preventDefault();

            const fields = [{   
                    name: 'title',
                    message: 'title is required'
                },
                {
                    name: 'department_id',
                    message: 'department id is required'
                },
                {
                    name: 'designation_id',
                    message: 'designation id is required'
                },
                {
                    name: 'shift_id',
                    message: 'shift id is required'
                },
                {
                    name: 'employment_type',
                    message: 'employment type is required'
                },
                {
                    name: 'location',
                    message: 'location is required'
                },
                {
                    name: 'salary_range',
                    message: 'salary range is required'
                },
                {
                    name: 'closing_date',
                    message: 'closing date is required'
                },
                {
                    name: 'description',
                    message: 'description is required'
                },
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

            const formData = new FormData(this);
            formData.append('job_img', job_img.getFiles()[0]?.file);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);

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
                .then((response) => {
                    toastr.success(response.message);
                    button.prop('disabled', false);
                    if ($(e.target).attr('id') === 'createJobHandler') {
                        $('#createJobHandler')[0].reset();
                        job_img.removeFiles();
                    }
                }).catch((err) => {
                    console.error(err, 'Job create failed');
                    toastr.error(response.message);
                    button.prop('disabled', false);
                });
        });


    });
</script> --}}


<!-- Add this to your Blade template -->
<script>
    $(document).ready(function() {
        document.getElementById('job_img').addEventListener('change', event => {
            const file = event.target.files[0];
            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const previewElement = document.getElementById('job_img_preview');

            if (file) {
                if (validImageTypes.includes(file.type)) {
                    const reader = new FileReader();
                    reader.onload = () => {
                        previewElement.src = reader.result;
                        previewElement.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    event.target.value = '';
                    previewElement.style.display = 'none';
                    alert('Invalid file type. Please upload a valid image file.');
                }
            }
        });

        $('#createJobHandler, #updateJobHandler').submit(function(e) {
            e.preventDefault();

            const fields = [{
                    name: 'title',
                    message: 'title is required'
                },
                {
                    name: 'department_id',
                    message: 'department id is required'
                },
                {
                    name: 'designation_id',
                    message: 'designation id is required'
                },
                {
                    name: 'shift_id',
                    message: 'shift id is required'
                },
                {
                    name: 'employment_type',
                    message: 'employment type is required'
                },
                {
                    name: 'location',
                    message: 'location is required'
                },
                {
                    name: 'salary_range',
                    message: 'salary range is required'
                },
                {
                    name: 'closing_date',
                    message: 'closing date is required'
                },
                {
                    name: 'description',
                    message: 'description is required'
                },
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

            const formData = new FormData(this);

            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);
            $('#loadingSpinner').show();

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
                .then((response) => {
                    $('#loadingSpinner').hide();
                    toastr.success(response.message);
                    button.prop('disabled', false);
                    if ($(e.target).attr('id') === 'createJobHandler') {
                        $('#createJobHandler')[0].reset();
                    }
                }).catch((err) => {
                    $('#loadingSpinner').hide();
                    console.error(err, 'Job create failed');
                    toastr.error(response.message);
                    button.prop('disabled', false);
                });
        });
    });
</script>
@endpush
