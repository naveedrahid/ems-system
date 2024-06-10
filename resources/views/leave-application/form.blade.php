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
                        'url' => $leave_application->exists
                            ? route('leave-applications.update', $leave_application->id)
                            : route('leave-applications.store'),
                        'method' => $leave_application->exists ? 'PUT' : 'POST',
                        'id' => $leave_application->exists ? 'updateLeaveApplication' : 'addLeaveApplication',
                    ]) !!}

                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif

                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Leave Type') !!}
                        {!! Form::select('leave_type_id', ['' => 'Select Leave Type'] + $leaveTypes, null, [
                            'class' => 'form-control form-select select2',
                        ]) !!}
                    </div>

                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Select Date Range:') !!}
                        {!! Form::text('daterange', null, ['class' => 'form-control', 'id' => 'daterange']) !!}
                    </div>
                    {!! Form::hidden('start_date', $leave_application->start_date, ['id' => 'start_date']) !!}
                    {!! Form::hidden('end_date', $leave_application->end_date, ['id' => 'end_date']) !!}
                    {!! Form::hidden('total_leave', null, ['id' => 'total_leave']) !!}

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
                        @if (isAdmin(auth()->user()))
                        {!! Form::select(
                            'status',
                            ['' => 'Select Leave Status'] + array_combine(\App\Models\LeaveApplication::getStatusOptions(), \App\Models\LeaveApplication::getStatusOptions()),
                            $leave_application->status,
                            [
                                'class' => 'form-control form-select select2',
                            ]
                        ) !!}
                        
                        @endif
                    </div>

                    {!! Form::submit($leave_application->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
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
                format: 'YYYY-MM-DD'
            },
            startDate: startDate || moment(),
            endDate: endDate || moment()
        }, function(start, end, label) {
            $('#start_date').val(start.format('YYYY-MM-DD'));
            $('#end_date').val(end.format('YYYY-MM-DD'));
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
                const daterange = $('input[name="daterange"]').val().trim();
                const reason = $('textarea[name="reason"]').val().trim();

                if (leave_type_id === '' || daterange === '' || reason === '') {
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
                const daterange = $('input[name="daterange"]').val().trim();
                const reason = $('textarea[name="reason"]').val().trim();

                if (leave_type_id === '' || daterange === '' || reason === '') {
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
