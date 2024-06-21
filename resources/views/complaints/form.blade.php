@extends('masterLayout.app')
@section('main')
@section('page-title')
    <h1>{{ $complaint->exists ? 'Edit Complaint' : 'Create Complaint' }}</h1>
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    {!! Form::model($complaint, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => 'complaintHandler',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Employee Name') !!}
                                <div class="EmpInfo">
                                    {{ auth()->user()->name }}
                                    {!! Form::hidden('employee_id', $employee_id) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Employee Type') !!}
                                <div class="EmpInfo">
                                    {{ $employeeTypeName }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Department Name') !!}
                                <div class="EmpInfo">
                                    {{ $departmentName }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Designation Name') !!}
                                <div class="EmpInfo">
                                    {{ $designationName }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 form-group text-center">
                            {!! Form::hidden('ticket_number', $ticketNumber) !!}
                            {!! Form::label('title', 'Ticker Number') !!}
                            <div class="mb-3 form-group text-center btn btn-block btn-default btn-flat">
                                <strong>{{ $ticketNumber }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-12">
                        <div class="mb-3 form-group">
                            @php
                                $complaintTypesFormat = array_map(function ($status) {
                                    return ucfirst(str_replace('_', ' ', $status));
                                }, $complaintTypes);
                            @endphp
                            {!! Form::label('title', 'Complaint Types') !!}
                            {{-- {!! Form::select(
                                'complaint_type',
                                ['' => 'Select Type'] + $complaintTypesFormat,
                                old('complaint_type', $complaintTypes ?? ''),
                                ['class' => 'form-control form-select select2'],
                            ) !!} --}}
                            {!! Form::select(
                                'complaint_type',
                                ['' => 'Select Type'] + array_combine($complaintTypes, $complaintTypesFormat),
                                old('complaint_type'),
                                ['class' => 'form-control form-select select2'],
                            ) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-12">
                        <div class="mb-3 form-group">
                            {!! Form::textarea('content', old('content'), [
                                'id' => 'complaintEditor',
                                'cols' => 30,
                                'rows' => 10,
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! Form::submit($complaint->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary setDisabled']) !!}
                    <a href="{{ route('complaints.index') }}" class="btn btn-danger">Cancel</a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    </div>
@endsection
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.1.1/tinymce.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
<script>
    tinymce.init({
        selector: 'textarea#complaintEditor',
        branding: false,
        plugins: 'code table lists',
        menubar: false,
        statusbar: false,
        toolbar: 'bold italic underline | fontsizeselect | forecolor | bullist numlist | alignleft aligncenter alignright | link | blocks',
    });
    $(document).ready(function() {
        $('#complaintHandler').submit(function(e) {
            e.preventDefault();


            const complaint_type = $('select[name="complaint_type"]').val().trim();
            const content = $('textarea[name="content"]').val().trim();

            if (complaint_type === '' || content === '') {
                if (complaint_type === '') {
                    toastr.error('Complaint type is required.');
                }

                if (content === '') {
                    toastr.error('Content is required.');
                }
                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('.setDisabled');
            button.prop('disabled', true);

            $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .done(function(response) {

                    toastr.success(response.message);
                    $('#complaintHandler')[0].reset();
                })
                .fail(function(xhr) {
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Failed to save complaint.');
                    }
                })
                .always(function() {
                    button.prop('disabled', false);
                });
        });
        toastr.options = {
            "closeButton": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "2000"
        }
    });
</script>
@endpush
