@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $holiday->exists ? 'Edit Holiday' : 'Create Holiday' }}
@endsection
@section('page-content')
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card small-box card-primary p-5">
                    {!! Form::model($holiday, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => $holiday->exists ? 'updateHolidays' : 'addHolidays',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Holiday Name') !!}
                                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Select Holiday Type') !!}
                                {!! Form::select(
                                    'holiday_type',
                                    ['' => 'Select Holiday Type'] + App\Models\Holiday::getStatusOptions(),
                                    old('holiday_type', $holiday->holiday_type ?? ''),
                                    [
                                        'class' => 'form-control form-select select2',
                                        'style' => 'width: 100%;'
                                    ],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Select Date Range:') !!}
                                {!! Form::text('date', null, ['id' => 'daterange', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'description') !!}
                                {!! Form::textarea('description', old('description'), [
                                    'id' => 'description',
                                    'cols' => 30,
                                    'rows' => 10,
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($holiday->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('holidays.index') }}" class="btn btn-danger">Cancel</a>
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
        $('#addHolidays, #updateHolidays').submit(function(e) {
            e.preventDefault();

            const name = $('input[name="name"]').val().trim();
            const daterange = $('input[name="date"]').val().trim();
            const holidayType = $('select[name="holiday_type"]').val().trim();

            if (name === '' || daterange === '' || holidayType === '') {
                if (name === '') {
                    toastr.error('Name is required.');
                }
                if (daterange === '') {
                    toastr.error('Date is required.');
                }
                if (holidayType === '') {
                    toastr.error('Holiday Type is required.');
                }
                return;
            }

            const formData = new FormData(this);
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
                })
                .then(function(response) {
                    button.prop('disabled', false);
                    toastr.success(response.message);
                    if ($(e.target).attr('id') === 'addHolidays') {
                        $('#addHolidays')[0].reset();
                    }
                })
                .catch(function(xhr) {
                    console.error(xhr);
                    toastr.error('Failed to create Holiday.');
                });
        });
    });
</script>
@endpush
