@extends('masterLayout.app')
@section('main')
@section('page-title')
    <h1>{{ $shift->exists ? 'Edit Shift' : 'Create Shift' }}</h1>
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    {!! Form::model($shift, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => $shift->exists ? 'updateShift' : 'addShift',
                    ]) !!}

                    @if ($shift->exists === 'PUT')
                        @method('PUT')
                    @endif

                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Shift Name') !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        <div id="nameError" class="text-danger"></div>
                    </div>

                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Open Time') !!}
                        {!! Form::time('opening', null, ['class' => 'form-control']) !!}
                        <div id="openingError" class="text-danger"></div>
                    </div>
                    
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Close Time') !!}
                        {!! Form::time('closing', null, ['class' => 'form-control']) !!}
                        <div id="closingError" class="text-danger"></div>
                    </div>                    

                    <div class="box-footer">
                        {!! Form::submit($shift->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('shifts.index') }}" class="btn btn-danger">Cancel</a>
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
        $('#addShift, #updateShift').submit(function(e) {
            e.preventDefault();

            const name = $('input[name="name"]').val().trim();
            const opening = $('input[name="opening"]').val().trim();
            const closing = $('input[name="closing"]').val().trim();

            $('.text-danger').text('');
            const submitBtn = $(this).find('input[name="submit"]');
            submitBtn.prop('disabled', false)
            let hasError = false;

            if (name === '') {
                $('#nameError').text('Name is required')
                hasError = true;
            }
            if (opening === '') {
                $('#nameError').text('Name is required')
                hasError = true;
            }
            if (closing === '') {
                $('#nameError').text('Name is required')
                hasError = true;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');

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
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Your Shift has been saved",
                        showConfirmButton: false,
                        timer: 1500,
                        text: response.message,
                    });
                    $('#addShift')[0].reset();
                })
                .fail(function(xhr) {
                    console.error(xhr);
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#' + key + 'Error').text(value[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to save Shift.',
                        });
                    }
                })
                .always(function() {
                    submitBtn.prop('disabled', false);
                });
        });
    });
</script>
@endpush
