@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{-- {!! Form::title() !!} --}}
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    {!! Form::model($holiday, [
                        'url' => $holiday->exists ? route('holidays.update', $holiday->id) : route('holidays.store'),
                        'method' => $formMethod,
                        'id' => $holiday->exists ? 'updateHolidays' : 'addHolidays',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    {!! Form::hidden('redirect-url', route('holidays.index'), ['id' => 'redirect-url']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Holiday Name') !!}
                                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('title', 'Select Date Range:') !!}
                                {!! Form::text('date', null, ['id' => 'daterange', 'class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Select Holiday Type') !!}

                                {!! Form::select(
                                    'holiday_type',
                                    ['' => 'Select Holiday Type'] + App\Models\Holiday::getStatusOptions(),
                                    old('holiday_type', $holiday->holiday_type ?? ''),
                                    [
                                        'class' => 'form-control form-select select2',
                                        'style' => 'width: 100%;',
                                        'required',
                                    ],
                                ) !!}


                            </div>
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


        // Update Holiday

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
