@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $document->exists ? 'Edit Document' : 'Create Document' }}
@endsection
@section('page-content')
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card small-box card-primary p-5">
                    {!! Form::model($document, [
                        'url' => $route,
                        'method' => $formMethod,
                        'files' => true,
                        'id' => $document->exists ? 'documentUpdateHandler' : 'documentUploadHandler',
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
                                    old('department_id', $document->department_id ?? null),
                                    ['class' => 'form-control select2', 'id' => 'department_id']
                                ) !!}
                                
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('user_id', 'Select User') !!}
                                <select name="user_id" id="user_id" class="form-control select2">
                                    <option value="">Select User</option>
                                    @foreach ($departments as $department)
                                        @foreach ($department->employees as $employee)
                                            @if ($employee->user)
                                                <option value="{{ $employee->user->id }}"
                                                    data-department-id="{{ $department->id }}"
                                                    {{ $employee->user->id == $document->user_id ? 'selected' : '' }}>
                                                    {{ $employee->user->name ?? '' }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('nic_front', 'NIC Front Image') !!}
                                <input type="file" class="filepond" name="nic_front" data-filepond>
                                {!! Form::hidden('nic_front', old('nic_front'), ['id' => 'nicFrontPath']) !!}
                            </div>
                        </div>

                        <!-- Dropzone for NIC Back Image -->
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('nic_back', 'NIC Back Image') !!}
                                <input type="file" class="filepond" name="nic_back" data-filepond>
                                {!! Form::hidden('nic_back', old('nic_back'), ['id' => 'nicBackPath']) !!}
                            </div>
                        </div>

                        <!-- Dropzone for Resume -->
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('resume', 'Add Resume') !!}
                                <input type="file" class="filepond" name="resume" data-filepond>
                                {!! Form::hidden('resume', old('resume'), ['id' => 'resumePath']) !!}
                            </div>
                        </div>

                        <!-- Dropzone for Payslip -->
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('payslip', 'Add Payslip') !!}
                                <input type="file" class="filepond" name="payslip" data-filepond>
                                {!! Form::hidden('payslip', old('payslip'), ['id' => 'payslipPath']) !!}
                            </div>
                        </div>

                        <!-- Dropzone for Experience Letter -->
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('experience_letter', 'Add Experience Letter') !!}
                                <input type="file" class="filepond" name="experience_letter" data-filepond>
                                {!! Form::hidden('experience_letter', old('experience_letter'), ['id' => 'experienceLetterPath']) !!}
                            </div>
                        </div>

                        <!-- Dropzone for Bill -->
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('bill', 'Add Bill Image') !!}
                                <input type="file" class="filepond" name="bill" data-filepond>
                                {!! Form::hidden('bill', old('bill'), ['id' => 'billPath']) !!}
                            </div>
                        </div>
                        <div class="box-footer">
                            {!! Form::submit($document->exists ? 'Update' : 'Create', [
                                'class' => 'btn btn-primary',
                                'id' => 'submitBtn',
                            ]) !!}
                            <a href="{{ route('documents.index') }}" class="btn btn-danger">Cancel</a>
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
<link href="https://unpkg.com/filepond@^4/dist/filepond.min.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview@^4/dist/filepond-plugin-image-preview.min.css"
    rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    span.select2-selection.select2-selection--single {
        height: 40px;
    }
</style>
@endpush

@push('js')
<script src="https://unpkg.com/filepond/dist/filepond.min.js" crossorigin="anonymous" referrerpolicy="no-referrer">
</script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size@^2/dist/filepond-plugin-file-validate-size.min.js"
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview@^4/dist/filepond-plugin-image-preview.min.js"
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js" crossorigin="anonymous" referrerpolicy="no-referrer">
</script>

<script>
    // Dropzone.autoDiscover = false;

    $(document).ready(function() {
        FilePond.registerPlugin(FilePondPluginFileValidateSize, FilePondPluginImagePreview);

        const nic_front = FilePond.create(document.querySelector('input[name="nic_front"]'));
        const nic_back = FilePond.create(document.querySelector('input[name="nic_back"]'));
        const resume = FilePond.create(document.querySelector(
            'input[name="resume"]'));
        const payslip = FilePond.create(document.querySelector('input[name="payslip"]'));
        const experience_letter = FilePond.create(document.querySelector('input[name="experience_letter"]'));
        const bill = FilePond.create(document.querySelector('input[name="bill"]'));

        // Fetch existing files
        @if ($document->nic_front)
            nic_front.addFile("{{ asset($document->nic_front) }}");
        @endif

        @if ($document->nic_back)
            nic_back.addFile("{{ asset($document->nic_back) }}");
        @endif

        @if ($document->resume)
            resume.addFile("{{ asset($document->resume) }}");
        @endif

        @if ($document->payslip)
            payslip.addFile("{{ asset($document->payslip) }}");
        @endif

        @if ($document->experience_letter)
            experience_letter.addFile("{{ asset($document->experience_letter) }}");
        @endif

        @if ($document->bill)
            bill.addFile("{{ asset($document->bill) }}");
        @endif

        $('#documentUploadHandler').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            const uploadFields = [{
                    pond: nic_front,
                    name: 'nic_front'
                },
                {
                    pond: nic_back,
                    name: 'nic_back'
                },
                {
                    pond: resume,
                    name: 'resume'
                },
                {
                    pond: payslip,
                    name: 'payslip'
                },
                {
                    pond: experience_letter,
                    name: 'experience_letter'
                },
                {
                    pond: bill,
                    name: 'bill'
                }
            ];

            let isEmptyFile = true;

            uploadFields.forEach(field => {
                if (field.pond.getFiles().length > 0) {
                    isEmptyFile = false;
                    field.pond.getFiles().forEach(file => {
                        formData.append(field.name, file.file);
                    });
                }
            });

            if (isEmptyFile) {
                toastr.error('Please select at least one file to upload.');
                return;
            }

            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);

            $.ajax({
                    method: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                })
                .then((response) => {
                    toastr.success(response.message);

                    // // Redirect to the new URL
                    // window.location.href = response.url;

                    button.prop('disabled', false);
                    $('#documentUploadHandler')[0].reset();
                    nic_front.removeFiles();
                    nic_back.removeFiles();
                    resume.removeFiles();
                    payslip.removeFiles();
                    experience_letter.removeFiles();
                    bill.removeFiles();
                }).catch((err) => {
                    console.error("Error response: ", err);
                    if (err.responseJSON && err.responseJSON.errors) {
                        $.each(err.responseJSON.errors, function(key, value) {
                            toastr.error(value);
                        });
                    } else {
                        toastr.error('Error updating profile photo');
                    }
                    button.prop('disabled', false);
                });

        });


        $('#documentUpdateHandler').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            const nic_frontFiles = nic_front.getFiles();
            const nic_backFiles = nic_back.getFiles();
            const resumeFiles = resume.getFiles();
            const payslipFiles = payslip.getFiles();
            const experience_letterFiles = experience_letter.getFiles();
            const billFiles = bill.getFiles();

            if (nic_frontFiles.length > 0) {
                formData.append('nic_front', nic_frontFiles[0].file);
            } else {
                formData.delete('nic_frontFiles');
            }

            if (nic_backFiles.length > 0) {
                formData.append('nic_back', nic_backFiles[0].file);
            } else {
                formData.delete('nic_backFiles');
            }

            if (resumeFiles.length > 0) {
                formData.append('resume', resumeFiles[0].file);
            } else {
                formData.delete('resumeFiles');
            }

            if (payslipFiles.length > 0) {
                formData.append('payslip', payslipFiles[0].file);
            } else {
                formData.delete('payslipFiles');
            }

            if (experience_letterFiles.length > 0) {
                formData.append('experience_letter', experience_letterFiles[0].file);
            } else {
                formData.delete('experience_letterFiles');
            }

            if (billFiles.length > 0) {
                formData.append('bill', billFiles[0].file);
            } else {
                formData.delete('billFiles');
            }

            const uploadFields = [{
                    pond: nic_front,
                    name: 'nic_front'
                },
                {
                    pond: nic_back,
                    name: 'nic_back'
                },
                {
                    pond: resume,
                    name: 'resume'
                },
                {
                    pond: payslip,
                    name: 'payslip'
                },
                {
                    pond: experience_letter,
                    name: 'experience_letter'
                },
                {
                    pond: bill,
                    name: 'bill'
                }
            ];

            let isEmptyFile = true;

            uploadFields.forEach(field => {
                if (field.pond.getFiles().length > 0) {
                    isEmptyFile = false;
                    field.pond.getFiles().forEach(file => {
                        formData.append(field.name, file.file);
                    });
                }
            });

            if (isEmptyFile) {
                toastr.error('Please select at least one file to upload.');
                return;
            }

            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);

            $.ajax({
                    method: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                })
                .then((response) => {
                    toastr.success(response.message);

                    // // Redirect to the new URL
                    // window.location.href = response.url;

                    button.prop('disabled', false);

                }).catch((err) => {
                    console.error("Error response: ", err);
                    if (err.responseJSON && err.responseJSON.errors) {
                        $.each(err.responseJSON.errors, function(key, value) {
                            toastr.error(value);
                        });
                    } else {
                        toastr.error('Error updating profile photo');
                    }
                    button.prop('disabled', false);
                });

        });
    });
</script>


{{-- <script>
    $(document).ready(function() {
        FilePond.registerPlugin(FilePondPluginFileValidateSize, FilePondPluginImagePreview);

        const nic_front = FilePond.create(document.querySelector('input[name="nic_front"]'));
        const nic_back = FilePond.create(document.querySelector('input[name="nic_back"]'));
        const resume = FilePond.create(document.querySelector('input[name="resume"]'));
        const payslip = FilePond.create(document.querySelector('input[name="payslip"]'));
        const experience_letter = FilePond.create(document.querySelector('input[name="experience_letter"]'));
        const bill = FilePond.create(document.querySelector('input[name="bill"]'));

        @if ($document->nic_front)
            nic_front.setOptions({
                files: [{
                    source: "{{ asset($document->nic_front) }}"
                }]
            });
        @endif
        @if ($document->nic_back)
            nic_back.setOptions({
                files: [{
                    source: "{{ asset($document->nic_back) }}"
                }]
            });
        @endif
        @if ($document->resume)
            resume.setOptions({
                files: [{
                    source: "{{ asset($document->resume) }}"
                }]
            });
        @endif
        @if ($document->payslip)
            payslip.setOptions({
                files: [{
                    source: "{{ asset($document->payslip) }}"
                }]
            });
        @endif
        @if ($document->experience_letter)
            experience_letter.setOptions({
                files: [{
                    source: "{{ asset($document->experience_letter) }}"
                }]
            });
        @endif
        @if ($document->bill)
            bill.setOptions({
                files: [{
                    source: "{{ asset($document->bill) }}"
                }]
            });
        @endif

        $('#documentForm, #documentUpdateForm').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            const appendFiles = (name, pond) => {
                const files = pond.getFiles();
                if (files.length > 0 && files[0].file instanceof File) {
                    formData.append(name, files[0].file);
                }
            };
            const nic_front_files = nic_front.getFiles();
            const nic_back_files = nic_back.getFiles();
            const resume_files = resume.getFiles();
            const payslip_files = payslip.getFiles();
            const experience_letter_files = experience_letter.getFiles();
            const bill_files = bill.getFiles();
            const department_id = $('select[name="department_id"]').val().trim();
            const user_id = $('select[name="user_id"]').val().trim();

            if (nic_front_files.length === 0 || nic_back_files.length === 0 || resume_files.length === 0 || payslip_files.length === 0 || experience_letter_files.length === 0 || bill_files.length === 0 || department_id === '' || user_id === '') {
                
                if (department_id === '') toastr.error('Department is required.');
                if (user_id === '') toastr.error('User name is required.');
                if (nic_front_files.length === 0) toastr.error('NIC front image is required.');
                if (nic_back_files.length === 0) toastr.error('NIC back image is required.');
                if (resume_files.length === 0) toastr.error('Resume is required.');
                if (payslip_files.length === 0) toastr.error('Payslip is required.');
                if (experience_letter_files.length === 0) toastr.error('Experience letter is required.');
                if (bill_files.length === 0) toastr.error('Bill is required.');
                
                return;
            }
            

            appendFiles('nic_front', nic_front);
            appendFiles('nic_back', nic_back);
            appendFiles('resume', resume);
            appendFiles('payslip', payslip);
            appendFiles('experience_letter', experience_letter);
            appendFiles('bill', bill);
            
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
            }).then((response) => {
                toastr.success(response.message);
                button.prop('disabled', false);

                if ($(e.target).attr('id') === 'documentForm') {
                    $(e.target)[0].reset();
                    [nic_front, nic_back, resume, payslip, experience_letter, bill].forEach(
                        pond => {
                            pond.removeFiles();
                        });
                }
            }).catch((err) => {
                console.error(err);
                toastr.error('Error updating document');
                button.prop('disabled', false);
            });
        });
    });
</script> --}}

{{-- <script>
    $(document).ready(function() {
        FilePond.registerPlugin(FilePondPluginFileValidateSize, FilePondPluginImagePreview);

        const nic_front = FilePond.create(document.querySelector('input[name="nic_front"]'));
        const nic_back = FilePond.create(document.querySelector('input[name="nic_back"]'));
        const resume = FilePond.create(document.querySelector('input[name="resume"]'));
        const payslip = FilePond.create(document.querySelector('input[name="payslip"]'));
        const experience_letter = FilePond.create(document.querySelector('input[name="experience_letter"]'));
        const bill = FilePond.create(document.querySelector('input[name="bill"]'));

        const documentData = @json($document);

        if (documentData.nic_front) {
            nic_front.setOptions({
                files: [{
                    source: "{{ asset($document->nic_front) }}"
                }]
            });
        }

        if (documentData.nic_back) {
            nic_back.setOptions({
                files: [{
                    source: "{{ asset($document->nic_back) }}"
                }]
            });
        }

        if (documentData.resume) {
            resume.setOptions({
                files: [{
                    source: "{{ asset($document->resume) }}"
                }]
            });
        }

        if (documentData.payslip) {
            payslip.setOptions({
                files: [{
                    source: "{{ asset($document->payslip) }}"
                }]
            });
        }

        if (documentData.experience_letter) {
            experience_letter.setOptions({
                files: [{
                    source: "{{ asset($document->experience_letter) }}"
                }]
            });
        }

        if (documentData.bill) {
            bill.setOptions({
                files: [{
                    source: "{{ asset($document->bill) }}"
                }]
            });
        }

    $('#documentForm, #documentUpdateForm').submit(function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Clear existing file entries in FormData
        formData.delete('nic_front');
        formData.delete('nic_back');
        formData.delete('resume');
        formData.delete('payslip');
        formData.delete('experience_letter');
        formData.delete('bill');

        const nic_frontFiles = nic_front.getFiles();
        const nic_backFiles = nic_back.getFiles();
        const resumeFiles = resume.getFiles();
        const payslipFiles = payslip.getFiles();
        const experience_letterFiles = experience_letter.getFiles();
        const billFiles = bill.getFiles();

        if (nic_frontFiles.length > 0) {
            formData.append('nic_front', nic_frontFiles[0].file);
        }

        if (nic_backFiles.length > 0) {
            formData.append('nic_back', nic_backFiles[0].file);
        }

        if (resumeFiles.length > 0) {
            formData.append('resume', resumeFiles[0].file);
        }

        if (payslipFiles.length > 0) {
            formData.append('payslip', payslipFiles[0].file);
        }

        if (experience_letterFiles.length > 0) {
            formData.append('experience_letter', experience_letterFiles[0].file);
        }

        if (billFiles.length > 0) {
            formData.append('bill', billFiles[0].file);
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
        }).then((response) => {
            toastr.success(response.message);
            button.prop('disabled', false);
            if ($(e.target).attr('id') === 'documentForm') {
                $('#documentForm')[0].reset();
                nic_front.removeFiles();
                nic_back.removeFiles();
                resume.removeFiles();
                payslip.removeFiles();
                experience_letter.removeFiles();
                bill.removeFiles();
            }
        }).catch((err) => {
            console.error(err);
            toastr.error('Error updating document');
            button.prop('disabled', false);
        });
    });
</script> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

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

        function updateUsers(departmentId) {
            $('#user_id option').not(':first').each(function() {
                const option = $(this);
                if (option.data('department-id') == departmentId) {
                    option.data('select2-hidden', false);
                } else {
                    option.data('select2-hidden', true);
                }
            });

            $('#user_id').select2({
                templateResult: formatState,
                templateSelection: formatState
            });
        }

        $('#department_id').on('change', function() {
            const selectedDepartmentId = $(this).val();
            updateUsers(selectedDepartmentId);
        });

        const initialDepartmentId = $('#department_id').val();
        if (initialDepartmentId) {
            updateUsers(initialDepartmentId);
        }
    });
</script>
@endpush
