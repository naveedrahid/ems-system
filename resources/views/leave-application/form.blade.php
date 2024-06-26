@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $leave_application->exists ? 'Edit Application' : 'Create Application' }}
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    {!! Form::model($leave_application, [
                        'url' => $route,
                        'method' => $formMethod,
                        'files' => true,
                        'id' => $leave_application->exists ? 'updateLeaveApplication' : 'addLeaveApplication',
                        ]) !!}
                
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    @php
                        $user = auth()->user();
                    @endphp
                
                    <div class="mb-3 form-group">
                        @if (isAdmin($user))
                            {!! Form::label('title', 'User Name') !!}
                            {!! Form::select('user_id', ['' => 'Select User'] + $users, $leave_application->user_id, [
                                'class' => 'form-control form-select select2',
                            ]) !!}
                        @else
                            {!! Form::hidden('user_id', $user->id) !!}
                            {!! Form::label('user_name', 'User Name') !!}
                            {!! Form::text('user_name', $user->name, ['class' => 'form-control', 'readonly']) !!}
                        @endif
                    </div>
                
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Leave Type') !!}
                        {!! Form::select('leave_type_id', ['' => 'Select Leave Type'] + $leaveTypes, null, [
                            'class' => 'form-control form-select select2',
                        ]) !!}
                    </div>
                
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Select Date:') !!}
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::date('start_date', null, ['class' => 'form-control', 'id' => 'start_date', 'placeholder' => 'YYYY-MM-DD']) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::date('end_date', null, ['class' => 'form-control', 'id' => 'end_date', 'placeholder' => 'YYYY-MM-DD']) !!}
                            </div>
                            {!! Form::hidden('total_leave', null, ['id' => 'total_leave']) !!}
                        </div>
                    </div>
                
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Reason') !!}
                        {!! Form::textarea('reason', null, [
                            'id' => 'reason',
                            'class' => 'form-control',
                            'cols' => 30,
                            'rows' => 10,
                        ]) !!}
                    </div>
                
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Upload') !!}
                        {!! Form::file('leave_image', ['id' => 'leave_image']) !!}
                    </div>
                
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Leave Status') !!}
                        @if (isAdmin($user))
                            {!! Form::select(
                                'status',
                                ['' => 'Select Leave Status'] + \App\Models\LeaveApplication::getStatusOptions(),
                                'Pending',
                                [
                                    'class' => 'form-control form-select select2',
                                ],
                            ) !!}
                        @endif
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($leave_application->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('leave-applications.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
    </div>
@endsection
@endsection

@push('js')
<script>
    $(function() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        $('#daterange').daterangepicker({
            opens: 'left',
            isInvalidDate: function(date) {
                return date.day() === 0 || date.day() === 6;
            },
            locale: {
                format: 'yyyy-mm-dd'
            },
            startDate: startDate || moment(),
            endDate: endDate || moment()
        }, function(start, end, label) {
            $('#start_date').val(start.format('yyyy-mm-dd'));
            $('#end_date').val(end.format('yyyy-mm-dd'));
        });
        // Customize invalid date cells
        // $('#daterange').on('show.daterangepicker', function(ev, picker) {
        //     setTimeout(function() {
        //         $('.daterangepicker td.off').each(function() {
        //             if ($(this).hasClass('off')) {
        //                 $(this).text('N/A');
        //             }
        //         });
        //     }, 0);
        // });
    });
    $(document).ready(function() {
        $(document).ready(function() {
            $('#updateLeaveApplication').on('submit', function(e) {
                e.preventDefault();
                const leave_type_id = $('select[name="leave_type_id"]').val().trim();
                const start_date = $('input[name="start_date"]').val().trim();
                const end_date = $('input[name="end_date"]').val().trim();
                const reason = $('textarea[name="reason"]').val().trim();
                if (leave_type_id === '' || start_date === ''|| end_date === '' || reason === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Leave Types Name, Start Date, End Date, or Reason cannot be empty.',
                    });
                    return;
                }
                const formData = new FormData(this);
                const url = $(this).attr('action');
                const token = $('meta[name="csrf-token"]').attr('content');
                const redirectUrl = $('#redirect-url').val();
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
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 2000);
                    })
                    .catch(function(xhr) {  
                        console.error(xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to create Leave Application.',
                        });
                    });
            });
            $('#addLeaveApplication').submit(function(e) {
                e.preventDefault();
                const leave_type_id = $('select[name="leave_type_id"]').val().trim();
                const start_date = $('input[name="start_date"]').val().trim();
                const end_date = $('input[name="end_date"]').val().trim();
                const reason = $('textarea[name="reason"]').val().trim();
                if (leave_type_id === '' || start_date === ''|| end_date === '' || reason === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Leave Types Name, Start Date, End Date, or Reason cannot be empty.',
                    });
                    return;
                }
                const formData = new FormData(this);
                const url = $(this).attr('action');
                const token = $('meta[name="csrf-token"]').attr('content');
                const redirectUrl = $('#redirect-url').val();
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
                        $('#addLeaveApplication')[0].reset();
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 2000);
                    })
                    .catch(function(xhr) {
                        console.error(xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to create Leave Type.',
                        });
                    });
            });
        });
    });
</script>
@endpush
