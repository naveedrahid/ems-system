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
                    <div class="row {{ $document->exists ? 'editDocuments' : '' }}">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                @if ($fromEmployee)
                                    {!! Form::label('department_id', 'Department') !!}
                                    {!! Form::text('departmentname', $employeeDepartment->department_name, [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                    ]) !!}
                                    {!! Form::hidden('department_id', $employeeDepartment->id) !!}
                                @elseif($document->exists)
                                    @php
                                        $departName = $departments->where('id', $document->department_id)->first();
                                    @endphp

                                    {!! Form::label('department_id', 'Department') !!}
                                    {!! Form::text('departmentname', $departName ? $departName->department_name : '', [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                    ]) !!}
                                    {!! Form::hidden('department_id', $document->department_id) !!}
                                @else
                                    {!! Form::label('department_id', 'Select Department') !!}
                                    {!! Form::select(
                                        'department_id',
                                        $departments->pluck('department_name', 'id')->prepend('Select Department', ''),
                                        old('department_id', $document->department_id ?? null),
                                        ['class' => 'form-control select2', 'id' => 'department_id'],
                                    ) !!}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                @php
                                    $userData = $users->where('id', $document->user_id)->first();
                                @endphp
                                @if ($fromEmployee)
                                    {!! Form::label('department_id', 'User Name') !!}
                                    {!! Form::text('username', $userName, [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                    ]) !!}
                                    {!! Form::hidden('user_id', $fromEmployee) !!}
                                @elseif ($document->exists)
                                    {!! Form::label('department_id', 'User Name') !!}
                                    {!! Form::text('username', $userData ? $userData->name : '', [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                    ]) !!}
                                    {!! Form::hidden('user_id', $document->user_id) !!}
                                @else
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
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <div class="imgShow"></div>
                                {!! Form::label('nic_front', 'NIC Front Image') !!}
                                {!! Form::file('nic_front', ['class' => 'form-control', 'id' => 'nic_front']) !!}
                                @if ($document->nic_front)
                                    @php $nic_frontUrl = asset($document->nic_front); @endphp
                                @endif
                                <img id="nic_front_preview" class="imagePreview"
                                    src="{{ isset($nic_frontUrl) ? $nic_frontUrl : '#' }}" alt="Image Preview"
                                    style="display:{{ $document->exists ? 'block' : 'none' }};" />
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <div class="imgShow"></div>
                                {!! Form::label('nic_back', 'NIC Back Image') !!}
                                {!! Form::file('nic_back', ['class' => 'form-control', 'id' => 'nic_back']) !!}
                                @if ($document->nic_back)
                                    @php $nic_backUrl = asset($document->nic_back); @endphp
                                @endif
                                <img id="nic_back_preview" class="imagePreview"
                                    src="{{ isset($nic_backUrl) ? $nic_backUrl : '#' }}" alt="Image Preview"
                                    style="display:{{ $document->exists ? 'block' : 'none' }};" />
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                @php
                                    $filePath = $document->resume;
                                    $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
                                    $isPdf = strtolower($fileType) === 'pdf';
                                @endphp

                                {!! Form::label('resume', 'Add Resume') !!}
                                {!! Form::file('resume', ['class' => 'form-control', 'id' => 'resume']) !!}

                                @if ($document->exists)
                                    @if ($isPdf)
                                        <iframe id="resume_preview_pdf" src="{{ asset($filePath) }}"
                                            style="display: block; width: 100%; height: 400px;" frameborder="0">
                                        </iframe>
                                        <div id="resume_preview_text" style="display: none;"></div>
                                    @else
                                        <div id="resume_preview_text" style="display: block;">
                                            {{ basename($filePath) }}
                                        </div>
                                        <iframe id="resume_preview_pdf" style="display: none; width: 100%; height: 400px;"
                                            frameborder="0"></iframe>
                                    @endif
                                @else
                                    <iframe id="resume_preview_pdf" style="display: none; width: 100%; height: 400px;"
                                        frameborder="0"></iframe>
                                    <div id="resume_preview_text" style="display: none;"></div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <div class="imgShow"></div>
                                {!! Form::label('payslip', 'Add Payslip') !!}
                                {!! Form::file('payslip', ['class' => 'form-control', 'id' => 'payslip']) !!}
                                @if ($document->payslip)
                                    @php $payslipUrl = asset($document->payslip); @endphp
                                @endif
                                <img id="payslip_preview" class="imagePreview"
                                    src="{{ isset($payslipUrl) ? $payslipUrl : '' }}" alt="Image Preview"
                                    style="display:{{ $document->exists ? 'block' : 'none' }};" />
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                @php
                                    $filePath = $document->experience_letter;
                                    $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
                                    $isPdf = strtolower($fileType) === 'pdf';
                                @endphp

                                {!! Form::label('experience_letter', 'Add Experience Letter') !!}
                                {!! Form::file('experience_letter', ['class' => 'form-control', 'id' => 'experience_letter']) !!}

                                @if ($document->exists)
                                    @if ($isPdf)
                                        <iframe id="experience_letter_preview_pdf" src="{{ asset($filePath) }}"
                                            style="display: block; width: 100%; height: 400px;" frameborder="0">
                                        </iframe>
                                        <div id="experience_letter_preview_text" style="display: none;"></div>
                                    @else
                                        <div id="experience_letter_preview_text" style="display: block;">
                                            {{ basename($filePath) }}
                                        </div>
                                        <iframe id="experience_letter_preview_pdf"
                                            style="display: none; width: 100%; height: 400px;" frameborder="0"></iframe>
                                    @endif
                                @else
                                    <iframe id="experience_letter_preview_pdf"
                                        style="display: none; width: 100%; height: 400px;" frameborder="0"></iframe>
                                    <div id="experience_letter_preview_text" style="display: none;"></div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <div class="imgShow"></div>
                                {!! Form::label('bill', 'Add Bill Image') !!}
                                {!! Form::file('bill', ['class' => 'form-control', 'id' => 'bill']) !!}
                                @if ($document->bill)
                                    @php $billUrl = asset($document->bill); @endphp
                                @endif
                                <img id="bill_preview" class="imagePreview" src="{{ isset($billUrl) ? $billUrl : '' }}"
                                    alt="Image Preview" style="display:{{ $document->exists ? 'block' : 'none' }};" />
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    iframe#experience_letter_preview_pdf,
    iframe#resume_preview_pdf {
        height: 240px !important;
        border: solid 1px #cccc;
        padding: 10px;
        margin-top: 20px;
        margin-bottom: 35px;
        border-radius: 10px;
        box-shadow: #00000024 0px 0px 10px 0px;
    }

    div#resume_preview_text,
    div#experience_letter_preview_text {
        background: #00000082;
        color: #fff;
        padding: 10px 20px;
        border-radius: 10px;
        margin-top: 20px;
        margin-bottom: 30px;
        box-shadow: #0000003b 0px 0px 10px 0px;
    }

    span.select2-selection.select2-selection--single {
        height: 40px;
    }

    label {
        margin-left: 3px;
        display: inline-block;
        margin-bottom: 4px;
    }

    label:not(.form-check-label):not(.custom-file-label) {
        font-weight: 500 !important;
    }

    .imagePreview {
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

    #experience_letter_preview_pdf,
    #resume_preview_pdf {
        transition: 1s ease;
        transform: scale(1);
    }

    #experience_letter_preview_pdf:hover,
    #resume_preview_pdf:hover,
    .imagePreview:hover {
        transform: scale(1.040);
        transition: 1s ease;
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = ['nic_front', 'nic_back', 'resume', 'payslip', 'experience_letter', 'bill'];
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        const validDocTypes = ['application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        fileInputs.forEach(inputId => {
            document.getElementById(inputId).addEventListener('change', event => {
                const file = event.target.files[0];
                const previewId = inputId + '_preview';
                const previewPdfId = inputId + '_preview_pdf';
                const previewTextId = inputId + '_preview_text';

                if (file) {
                    if (validImageTypes.includes(file.type) && ['nic_front', 'nic_back',
                            'payslip', 'bill'
                        ].includes(inputId)) {
                        const reader = new FileReader();
                        reader.onload = () => {
                            const previewImgElement = document.getElementById(previewId);
                            if (previewImgElement) {
                                previewImgElement.src = reader.result;
                                previewImgElement.style.display = 'block';
                            }
                            const previewPdfElement = document.getElementById(previewPdfId);
                            if (previewPdfElement) {
                                previewPdfElement.style.display = 'none';
                            }
                            const previewTextElement = document.getElementById(
                                previewTextId);
                            if (previewTextElement) {
                                previewTextElement.style.display = 'none';
                            }
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf' && ['resume',
                            'experience_letter'
                        ].includes(inputId)) {
                        const fileURL = URL.createObjectURL(file);
                        const previewPdfElement = document.getElementById(previewPdfId);
                        if (previewPdfElement) {
                            previewPdfElement.src = fileURL;
                            previewPdfElement.style.display = 'block';
                        }
                        const previewImgElement = document.getElementById(previewId);
                        if (previewImgElement) {
                            previewImgElement.style.display = 'none';
                        }
                        const previewTextElement = document.getElementById(previewTextId);
                        if (previewTextElement) {
                            previewTextElement.style.display = 'none';
                        }
                    } else if (validDocTypes.includes(file.type) && ['resume',
                            'experience_letter'
                        ].includes(inputId)) {
                        const previewTextElement = document.getElementById(previewTextId);
                        if (previewTextElement) {
                            previewTextElement.innerText = file.name;
                            previewTextElement.style.display = 'block';
                        }
                        const previewPdfElement = document.getElementById(previewPdfId);
                        if (previewPdfElement) {
                            previewPdfElement.style.display = 'none';
                        }
                        const previewImgElement = document.getElementById(previewId);
                        if (previewImgElement) {
                            previewImgElement.style.display = 'none';
                        }
                    } else {
                        event.target.value = '';
                        toastr.error('Invalid file type. Please upload the correct format.');
                    }
                }
            });
        });

        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            const results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        const documentUser = getUrlParameter('document_user');

        $('#documentUploadHandler, #documentUpdateHandler').submit(function(e) {
            e.preventDefault();
            let department_id, user_id;

            if (documentUser) {
                department_id = $('input[name="department_id"]').val().trim();
                user_id = $('input[name="user_id"]').val().trim();
            } else if ($(e.target).attr('id') === 'documentUploadHandler') {
                department_id = $('select[name="department_id"]').val().trim();
                user_id = $('select[name="user_id"]').val().trim();
            } else {
                department_id = $('input[name="department_id"]').val().trim();
                user_id = $('input[name="user_id"]').val().trim();
            }

            if (department_id === '' || user_id === '') {
                if (department_id === '')
                    toastr.error('Department is required.');

                if (user_id === '')
                    toastr.error('User name is required.');

                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);


            let valid = true;


            fileInputs.forEach(function(inputId) {
                const file = document.getElementById(inputId).files[0];
                if (file) {
                    if (inputId === 'resume' || inputId === 'experience_letter') {
                        if (!['application/pdf', 'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                            ].includes(file.type)) {
                            valid = false;
                            toastr.error('Invalid file type for ' + inputId.replace('_', ' ') +
                                '. Only PDF, DOC, and DOCX are allowed.');
                        }
                    } else {
                        if (!file.type.startsWith('image/')) {
                            valid = false;
                            toastr.error('Invalid file type for ' + inputId.replace('_', ' ') +
                                '. Only image files are allowed.');
                        }
                    }
                }
            });

            if (!valid) {
                button.prop('disabled', false);
                return;
            }

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
                    button.prop('disabled', false);
                    if ($(e.target).attr('id') === 'documentUploadHandler') {
                        $('#documentUploadHandler')[0].reset();
                        fileInputs.forEach(function(inputId) {
                            const previewImgElement = document.getElementById(inputId +
                                '_preview');
                            if (previewImgElement) previewImgElement.style.display = 'none';
                            const previewPdfElement = document.getElementById(inputId +
                                '_preview_pdf');
                            if (previewPdfElement) previewPdfElement.style.display = 'none';
                            const previewTextElement = document.getElementById(inputId +
                                '_preview_text');
                            if (previewTextElement) previewTextElement.style.display =
                                'none';
                        });
                    }
                }).catch((err) => {
                    console.error("Error response: ", err);
                    if (err.responseJSON && err.responseJSON.errors) {
                        $.each(err.responseJSON.errors, function(key, value) {
                            toastr.error(value);
                        });
                    } else {
                        toastr.error('Error updating document photo');
                    }
                    button.prop('disabled', false);
                });
        });
    });
</script>

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
