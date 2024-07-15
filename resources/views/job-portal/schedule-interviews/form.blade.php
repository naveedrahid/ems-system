@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ __('Create Interview Schedual') }}
@endsection
@section('page-content')
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card small-box card-primary p-5 position-relative">
                    <div id="loadingSpinner" style="display: none; text-align: center;">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                    </div>
                    {!! Form::model($schedule_interview, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => 'interviewCreateHandler',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
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
                                {!! Form::label('interviewer_id', 'Interviewer Name') !!}
                                <select id="user_id" name="interviewer_id" class="form-control form-select select2"
                                    style="width: 100%;">
                                    <option value="">Select User</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->user_id }}"
                                            data-department-id="{{ $employee->department_id }}"
                                            {{ $schedule_interview->user_id == $employee->user_id ? 'selected' : '' }}>
                                            {{ $employee->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('job_title', 'Select Job') !!}
                                {!! Form::select('job_id', ['' => 'Select job'] + $job, null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'job_id',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('candidate_id', 'Candidate') !!}
                                <select id="candidate_id" name="candidate_id" class="form-control form-select select2"
                                    style="width: 100%;">
                                    <option value="">Select User</option>
                                    @foreach ($candidate as $candidateSelected)
                                        <option value="{{ $candidateSelected->id }}"
                                            data-job-id="{{ $candidateSelected->job->id }}"
                                            {{ $candidateSelected->job_id == $candidateSelected->job->job_id ? 'selected' : '' }}>
                                            {{ $candidateSelected->first_name . ' ' . $candidateSelected->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('interview_types', 'Select Interview Types') !!}
                                {!! Form::select('interview_types', array_combine($interview_types, $interview_types), null, [
                                    'class' => 'form-control form-select select2',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('interview_date', 'Interview Date') !!}
                                {!! Form::date('interview_date', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('interview_time', 'Interview Time') !!}
                                {!! Form::time('interview_time', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-12">
                            {!! Form::label('interviewer_notes', 'Interviewer Notes') !!}
                            <div>
                                <small>
                                    <strong>Note:</strong> Your notes will be sent in the email body to the interviewer.
                                    Please provide detailed and relevant information about the candidate to assist the
                                    interviewer in their evaluation.
                                </small>
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::textarea('interviewer_notes', old('interviewer_notes'), [
                                    'id' => 'interviewer_notes',
                                    'cols' => 30,
                                    'rows' => 10,
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-12">
                            {!! Form::label('candidate_notes', 'Candidate Notes') !!}
                            <div>
                                <small>
                                    <strong>Note:</strong> Please use this field to inform the candidate about their
                                    selection status and provide details about the upcoming interview.
                                </small>
                            </div>
                            <div class="mb-3 form-group">
                                {!! Form::textarea('candidate_notes', old('candidate_notes'), [
                                    'id' => 'candidate_notes',
                                    'cols' => 30,
                                    'rows' => 10,
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($schedule_interview->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary setDisabled']) !!}
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
    span.select2-selection.select2-selection--single {
        height: 40px;
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
        selector: 'textarea#interviewer_notes, textarea#candidate_notes',
        branding: false,
        plugins: 'code table lists',
        menubar: false,
        statusbar: false,
        toolbar: 'bold italic underline | fontsizeselect | forecolor | bullist numlist | alignleft aligncenter alignright | link | blocks',
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>

{{-- <script>
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
</script> --}}

<script>
    $(document).ready(function() {
        // Initialize select2
        $('.select2').select2();

        function departmentByUser() {
            $('#user_id option').not(':first').each(function() {
                const option = $(this);
                option.data('select2-hidden', true);
            });

            $('#user_id').select2({
                templateResult: function(option) {
                    if ($(option.element).data('select2-hidden')) {
                        return null;
                    }
                    return option.text;
                }
            });

            $('#department_id').on('change', function() {
                const selectedDepartmentId = $(this).val();

                $('#user_id option').not(':first').each(function() {
                    const option = $(this);
                    option.data('select2-hidden', true);
                });

                if (selectedDepartmentId) {
                    $('#user_id option').each(function() {
                        if ($(this).data('department-id') == selectedDepartmentId) {
                            $(this).data('select2-hidden', false);
                        }
                    });
                }

                $('#user_id').select2({
                    templateResult: function(option) {
                        if ($(option.element).data('select2-hidden')) {
                            return null;
                        }
                        return option.text;
                    }
                });
            });
        }

        departmentByUser();

        function jobByCandidate() {
            $('#candidate_id option').not(':first').each(function() {
                const option = $(this);
                option.data('select2-hidden', true);
            });

            $('#candidate_id').select2({
                templateResult: function(option) {
                    if ($(option.element).data('select2-hidden')) {
                        return null;
                    }
                    return option.text;
                }
            });

            $('#job_id').on('change', function() {
                const selectjobByCandidate = $(this).val();

                $('#candidate_id option').not(':first').each(function() {
                    const option = $(this);
                    option.data('select2-hidden', true);
                });

                if (selectjobByCandidate) {
                    $('#candidate_id option').each(function() {
                        if ($(this).data('job-id') == selectjobByCandidate) {
                            $(this).data('select2-hidden', false);
                        }
                    });
                }

                $('#candidate_id').select2({
                    templateResult: function(option) {
                        if ($(option.element).data('select2-hidden')) {
                            return null;
                        }
                        return option.text;
                    }
                });
            });
        }

        jobByCandidate();

        $('#interviewCreateHandler').submit(function(e) {
            e.preventDefault();

            const department_id = $('select[name="department_id"]').val().trim();
            const job_id = $('select[name="job_id"]').val().trim();
            const candidate_id = $('select[name="candidate_id"]').val().trim();
            const interviewer_id = $('select[name="interviewer_id"]').val().trim();
            const interview_types = $('select[name="interview_types"]').val().trim();
            const interviewer_notes = $('textarea[name="interviewer_notes"]').val().trim();
            const candidate_notes = $('textarea[name="candidate_notes"]').val().trim();
            const interview_date = $('input[name="interview_date"]').val().trim();
            const interview_time = $('input[name="interview_time"]').val().trim();

            if (department_id === '' || job_id === '' || candidate_id === '' || interviewer_id === '' ||
                interview_types === '' || interviewer_notes === '' || candidate_notes === '' ||
                interview_date === '' || interview_time === '') {

                if (department_id === '') toastr.error('Department field is required.');
                if (job_id === '') toastr.error('Job field is required.');
                if (candidate_id === '') toastr.error('Candidate field is required.');
                if (interviewer_id === '') toastr.error('Interviewer field is required.');
                if (interview_types === '') toastr.error('Interview type field is required.');
                if (interviewer_notes === '') toastr.error('Interviewer Notes field is required.');
                if (candidate_notes === '') toastr.error('Candidate Notes field is required.');
                if (interview_date === '') toastr.error('Date field is required.');
                if (interview_time === '') toastr.error('Time field is required.');

                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);
            $('#loadingSpinner').show();

            $.ajax({
                    method: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .done(function(response) {
                    toastr.success(response.message);
                    $('#interviewCreateHandler')[0].reset();
                    button.prop('disabled', false);
                    $('#loadingSpinner').hide();
                })
                .fail(function(xhr, status, error) {
                    const err = xhr.responseJSON.errors;
                    for (let key in err) {
                        toastr.error(err[key]);
                    }
                    button.prop('disabled', false);
                    $('#loadingSpinner').hide();
                });
        });


    });
</script>
@endpush
