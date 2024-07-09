@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $job->exists ? 'Edit job' : 'Create job' }}
@endsection
@section('page-content')
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card small-box card-primary p-5">
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
                                {{-- {!! Form::file('job_img', ['class' => 'filepond']) !!} --}}

                                <input type="file" name="job_img" class="filepond" data-filepond>
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
<link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview@^4/dist/filepond-plugin-image-preview.min.css"
    rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    span.select2-selection.select2-selection--single {
        height: 40px;
    }

    a.filepond--credits {
        display: none;
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>

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

<script src="https://unpkg.com/filepond/dist/filepond.min.js" crossorigin="anonymous" referrerpolicy="no-referrer">
</script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size@^2/dist/filepond-plugin-file-validate-size.min.js"
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview@^4/dist/filepond-plugin-image-preview.min.js"
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js" crossorigin="anonymous" referrerpolicy="no-referrer">
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
        FilePond.registerPlugin(FilePondPluginFileValidateSize, FilePondPluginImagePreview);
        const job_img = FilePond.create(document.querySelector('input[name="job_img"]'));

        @if($job->job_img)
            const existingImageUrl = "{{ asset($job->job_img) }}";
            job_img.addFile(existingImageUrl);
        @endif

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
            const files = job_img.getFiles();
            if (files.length > 0) {
                formData.append('job_img', files[0].file);
            } else {
                formData.delete('job_img');
            }

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
</script>

@endpush
