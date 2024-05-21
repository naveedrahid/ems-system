@extends('masterLayout.app')
@section('main')
@section('page-title')
    Edit Holidays
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <form action="{{ route('holidays.update', $holiday->id) }}" method="POST" id="updateHolidays">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="redirect-url" value="{{ route('holidays.index') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Holiday Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ $holiday->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="daterange">Select Date Range:</label>
                                    <input type="text" name="date" id="daterange" class="form-control"
                                        value="{{ $holiday->date_range }}">
                                </div>
                                <input type="hidden" name="date_old" id="date_old" value="{{ $holiday->date_range }}">
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Select Holiday Type</label>
                                    <select name="holiday_type" id="holiday_type" class="form-control form-select select2"
                                        style="width: 100%;">
                                        @foreach (App\Models\Holiday::getStatusOptions() as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status', $holiday->status ?? '') == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" id="description" cols="30" rows="10" class="form-control">{{ old('description', $holiday->description) }}</textarea>
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
        const dateOld = $('#date_old').val();
        const dates = dateOld.split(' - ');

        const startDate = moment(dates[0], 'YYYY-MM-DD', true).isValid() ? dates[0] : 'Invalid date';
        const endDate = moment(dates[1], 'YYYY-MM-DD', true).isValid() ? dates[1] : 'Invalid date';

        $('#daterange').daterangepicker({
            opens: 'left',
            locale: {
                format: 'YYYY-MM-DD'
            },
            startDate: startDate !== 'Invalid date' ? startDate : moment(),
            endDate: endDate !== 'Invalid date' ? endDate : moment(),
            isInvalidDate: function(date) {
                return (startDate === 'Invalid date' || endDate === 'Invalid date');
            }
        }, function(start, end, label) {
            $('#date_old').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        });

        if (startDate === 'Invalid date' && endDate === 'Invalid date') {
            $('#daterange').val('Invalid date - Invalid date');
        }
    });


    // Add Holiday

    $(document).ready(function() {
        $('#updateHolidays').submit(function(e) {
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
                    setTimeout(function() {
                        window.location.href = redirectUrl;
                    }, 2000);
                })
                .catch(function(xhr) {
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update Holiday.',
                    });
                });
        });
    });
</script>
@endpush
