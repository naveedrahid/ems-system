@extends('masterLayout.app')
@section('main')
@section('page-title')
    create Leave Applicatation
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form action="{{ route('leave_application.store') }}" method="POST" enctype="multipart/form-data"
                        id="addLeaveApplication">
                        @csrf
                        <input type="hidden" id="redirect-url" value="{{ route('leave_application.index') }}">
                        <div class="mb-3 form-group">
                            <label class="form-label">Select Leave Type</label>
                            <select name="leave_type_id" id="leave_type_id" class="form-control form-select select2"
                                style="width: 100%;">
                                @foreach ($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->id }}">
                                        {{ $leaveType->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="daterange">Select Date Range:</label>
                            <input type="text" name="daterange" id="daterange" class="form-control">
                        </div>
                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                        <div class="mb-3 form-group">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" id="reason" cols="30" rows="10" class="form-control">{{ old('reason') }}</textarea>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Upload</label>
                            <input type="file" name="leave_image" id="leave_image">
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('leave_application.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
    </div>
@endsection
@endsection
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
@endpush
@push('js')
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(function() {
        $('#daterange').daterangepicker({
            opens: 'left',
            isInvalidDate: function(date) {
                return date.day() === 0 || date.day() === 6;
            },
            locale: {
                format: 'YYYY-MM-DD'
            }
        }, function(start, end, label) {
            $('#start_date').val(start.format('YYYY-MM-DD'));
            $('#end_date').val(end.format('YYYY-MM-DD'));
        });
    });
    // Add Leave Application
    $(document).ready(function() {
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
</script>
@endpush
