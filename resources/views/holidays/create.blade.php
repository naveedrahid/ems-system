@extends('masterLayout.app')
@section('main')
@section('page-title')
    Create Holidays
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <form action="{{ route('holidays.store') }}" method="POST" id="addHolidays">
                        @csrf
                        <input type="hidden" id="redirect-url" value="{{ route('holidays.index') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Holiday Name</label>
                                    <input type="text" name="name" id="name" class="form-control "
                                        value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <label for="daterange">Select Date Range:</label>
                                    <input type="text" name="date" id="daterange" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Select Holiday Type</label>
                                    <select name="holiday_type" id="holiday_type" class="form-control form-select select2"
                                        style="width: 100%;">
                                        @foreach (App\Models\Holiday::getStatusOptions() as $status)
                                            <option value="{{ $status }}" {{old('status', $holiday->status ?? '') == $status ? 'selected' : ''}}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">description</label>
                                    <textarea name="description" id="description" cols="30" rows="10" class="form-control">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('holidays.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection

@push('js')
<script>
    $(function() {
        $('#daterange').daterangepicker({
            opens: 'right',
            locale: {
                format: 'YYYY-MM-DD'
            }
        })
    });

    // Add Holiday

    $(document).ready(function() {
        $('#addHolidays').submit(function(e) {
            e.preventDefault();

            const name = $('input[name="name"]').val().trim();
            const daterange = $('input[name="date"]').val().trim();
            const holidayType = $('select[name="holiday_type"]').val().trim();

            if (name === '' || daterange === '' || holidayType === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Holiday Name, Date, Holiday Type, cannot be empty.',
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
                    $('#addHolidays')[0].reset();
                    setTimeout(function() {
                        window.location.href = redirectUrl;
                    }, 2000);
                })
                .catch(function(xhr) {
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to create Holiday.',
                    });
                });
        });
    });
</script>
@endpush
