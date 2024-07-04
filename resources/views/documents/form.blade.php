@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $documentUser->exists ? 'Edit Document' : 'Create Document' }}
@endsection
@section('page-content')
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card small-box card-primary p-5">
                    {!! Form::model($documentUser, [
                        'url' => $route,
                        'method' => $formMethod,
                        'files' => true,
                        'id' => 'documentForm',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('department_id', 'Select Department') !!}
                                {!! Form::select(
                                    'department_id',
                                    $departments->pluck('department_name', 'id')->prepend('Select Department', ''),
                                    null,
                                    ['class' => 'form-control', 'id' => 'department_id'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('user_id', 'Select User') !!}
                                <select name="user_id" id="user_id" class="form-control">
                                    <option value="">Select User</option>
                                    @foreach ($departments as $department)
                                        @foreach ($department->employees as $employee)
                                            <option value="{{ $employee->user->id }}"
                                                data-department-id="{{ $department->id }}"
                                                {{ $employee->user->id == $documentUser->user_id ? 'selected' : '' }}>
                                                {{ $employee->user->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('nic_front', 'NIC Front Image') !!}
                                <input type="file" class="filepond" name="nic_front" multiple="false"
                                    data-allow-reorder="true" data-max-file-size="3MB" data-max-files="1">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('nic_back', 'NIC Back Image') !!}
                                <input type="file" class="filepond" name="nic_back" multiple="false"
                                    data-allow-reorder="true" data-max-file-size="3MB" data-max-files="1">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('resume', 'Add Resume') !!}
                                <input type="file" class="filepond" name="resume" multiple="false"
                                    data-allow-reorder="true" data-max-file-size="3MB" data-max-files="1">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('payslip', 'Add Payslip') !!}
                                <input type="file" class="filepond" name="payslip" multiple="false"
                                    data-allow-reorder="true" data-max-file-size="3MB" data-max-files="1">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('experience_letter', 'Add Experience Letter') !!}
                                <input type="file" class="filepond" name="experience_letter" multiple="false"
                                    data-allow-reorder="true" data-max-file-size="3MB" data-max-files="1">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('bill', 'Add bill Image') !!}
                                <input type="file" class="filepond" name="bill" multiple="false"
                                    data-allow-reorder="true" data-max-file-size="3MB" data-max-files="1">
                            </div>
                        </div>
                        <div class="box-footer">
                            {!! Form::submit($documentUser->exists ? 'Update' : 'Create', [
                                'class' => 'btn btn-primary',
                                'id' => 'submitBtn',
                            ]) !!}
                            <a href="{{ route('awards.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection

@push('css')
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview@^4/dist/filepond-plugin-image-preview.css" rel="stylesheet">
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
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size@^2/dist/filepond-plugin-file-validate-size.js">
</script>
<script src="https://unpkg.com/filepond-plugin-image-preview@^4/dist/filepond-plugin-image-preview.js"></script>


<script>
    $(document).ready(function() {
        FilePond.registerPlugin(FilePondPluginFileValidateSize, FilePondPluginImagePreview);

        const nicFront = FilePond.create(document.querySelector('input[name="nic_front"]'));
        const nicBack = FilePond.create(document.querySelector('input[name="nic_back"]'));
        const resume = FilePond.create(document.querySelector('input[name="resume"]'));
        const payslip = FilePond.create(document.querySelector('input[name="payslip"]'));
        const experienceLetter = FilePond.create(document.querySelector('input[name="experience_letter"]'));
        const bill = FilePond.create(document.querySelector('input[name="bill"]'));

        // Handle form submission
        $('#documentForm').submit(function(e) {
            e.preventDefault();

            // Validate files
            if (nicFront.getFiles().length === 0 || nicBack.getFiles().length === 0) {
                if (nicFront.getFiles().length === 0) {
                    toastr.error('NIC Front Image is required.');
                }
                if (nicBack.getFiles().length === 0) {
                    toastr.error('NIC Back Image is required.');
                }
                return;
            }

            const formData = new FormData(this);
            formData.append('nic_front', nicFront.getFiles()[0].file);
            formData.append('nic_back', nicBack.getFiles()[0].file);
            formData.append('resume', resume.getFiles()[0]?.file);
            formData.append('payslip', payslip.getFiles()[0]?.file);
            formData.append('experience_letter', experienceLetter.getFiles()[0]?.file);
            formData.append('bill', bill.getFiles()[0]?.file);

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
            }).then(function(response) {
                console.log(response);
                toastr.success(response.message);
                button.prop('disabled', false);
                $('#documentForm')[0].reset();
                nicFront.removeFiles();
                nicBack.removeFiles();
                resume.removeFiles();
                payslip.removeFiles();
                experienceLetter.removeFiles();
                bill.removeFiles();
            }).catch(function(err) {
                console.error(err);
                toastr.error('Failed to save document.');
                button.prop('disabled', false);
            });
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize select2 on both selects
        $('#department_id, #user_id').select2();

        // Hide all user options initially
        $('#user_id option').not(':first').each(function() {
            const option = $(this);
            option.data('select2-hidden', true);
        });

        // Update the template result to show/hide options
        function formatState(option) {
            if (!option.id) {
                return option.text;
            }
            if ($(option.element).data('select2-hidden')) {
                return null;
            }
            return option.text;
        }

        $('#user_id').select2({
            templateResult: formatState,
            templateSelection: formatState
        });

        // On change of department, show related users
        $('#department_id').on('change', function() {
            const selectedDepartmentId = $(this).val();

            // Hide all user options initially
            $('#user_id option').not(':first').each(function() {
                $(this).data('select2-hidden', true);
            });

            // Show only the users related to the selected department
            if (selectedDepartmentId) {
                $('#user_id option').each(function() {
                    if ($(this).data('department-id') == selectedDepartmentId) {
                        $(this).data('select2-hidden', false);
                    }
                });
            }

            // Update the select2 to reflect the changes
            $('#user_id').select2({
                templateResult: formatState,
                templateSelection: formatState
            });
        });
    });
</script>
@endpush
