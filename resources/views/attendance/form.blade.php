@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $attendance->exists ? 'Edit Attendance' : 'Add Attendance' }}
@endsection
@section('page-content')
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card small-box card-primary p-5">
                    {!! Form::model($attendance, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => $attendance->exists ? 'updateAttendance' : 'addAttendance',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Select User') !!}
                                @if ($isEdit)
                                    {{-- Display selected user in a read-only field --}}
                                    {!! Form::text('user_name', $attendance->user->name, [
                                        'class' => 'form-control',
                                        'readonly' => 'readonly',
                                    ]) !!}
                                    {!! Form::hidden('user_id', $attendance->user_id, ['id' => 'user_id']) !!}
                                @else
                                    {{-- Display dropdown for selecting user --}}
                                    {!! Form::select('user_id', ['' => 'Select User'] + $userNames->toArray(), null, [
                                        'class' => 'form-control form-select select2',
                                        'id' => 'user_id',
                                        'style' => 'width: 100%;',
                                    ]) !!}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Date') !!}
                                {!! Form::date('attendance_date', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'check In Time') !!}
                                {!! Form::time('check_in', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'check Out Time') !!}
                                {!! Form::time('check_out', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($attendance->exists ? 'update' : 'create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('attendance.report') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#addAttendance, #updateAttendance').submit(function(e) {
            e.preventDefault();
            const user_id = $('#user_id').val().trim();
            const attendance_date = $('input[name="attendance_date"]').val().trim();
            const check_in = $('input[name="check_in"]').val().trim();
            const check_out = $('input[name="check_out"]').val().trim();

            let hasError = false;

            if (user_id == '') {
                toastr.error('User name is required.');
                hasError = true;
            }

            if (attendance_date == '') {
                toastr.error('Attendance Date is required.');
                hasError = true;
            }

            if (check_in == '') {
                toastr.error('check in is required.');
                hasError = true;
            }
            if (check_out == '') {
                toastr.error('check out is required.');
                hasError = true;
            }

            if (hasError) return;

            const formData = new FormData(this);
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
                    button.prop('disabled', false);
                    if ($(this).attr('id') === 'addAttendance') {
                        $('#addAttendance')[0].reset();
                    }
                }).catch((err) => {
                    console.error(err);
                    toastr.error('Failed to save Attendance.');
                    button.prop('disabled', true);
                });
        });
    });
</script>
@endpush
