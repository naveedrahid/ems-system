@extends('masterLayout.app')
@section('main')
@section('page-title')
    <h1>{{ $leave_type->exists ? 'Edit Leave Types' : 'Create Leave Types' }}</h1>
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    {!! Form::model($leave_type, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => $leave_type->exists ? 'updateLeave' : 'addLeave',
                    ]) !!}

                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif

                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Leave Types Name') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'leave_name']) !!}
                        <div id="leave_nameError" class="text-danger"></div>
                    </div>

                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Total Leave') !!}
                        {!! Form::text('default_balance', null, ['class' => 'form-control', 'id' => 'default_balance']) !!}
                        <div id="default_balanceError" class="text-danger"></div>
                    </div>

                    <div class="mb-3 form-group">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select(
                            'status',
                            ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'],
                            $leave_type->status,
                            ['class' => 'form-control form-select select2', 'id' => 'status'],
                        ) !!}
                        <div id="statusError" class="text-danger"></div>
                    </div>

                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Description') !!}
                        {!! Form::textarea('description', old('description'), [
                            'id' => 'description',
                            'cols' => 30,
                            'rows' => 10,
                            'class' => 'form-control',
                        ]) !!}
                        <div id="branch_addressError" class="text-danger"></div>
                    </div>

                    <div class="box-footer">
                        {!! Form::submit() !!}
                        <a href="{{ route('leave-types.index') }}" class="btn btn-danger">Cancel</a>
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
    $(document).ready(function() {
        $('#addLeave, #updateLeave').submit(function(e) {
            e.preventDefault();

            const name = $('input[name="name"]').val().trim();
            const defaultBalance = $('input[name="default_balance"]').val().trim();
            const status = $('select[name="status"]').val().trim();

            $('.text-danger').text('');
            const submitButton = $(this).find('input[type="submit"]');
            submitButton.prop('disabled', true);
            let hasError = false;

            if (name === '') {
                $('#leave_nameError').text('Name is required.');
                hasError = true;
            }

            if (defaultBalance === '') {
                $('#default_balanceError').text('Total Leaves is required.');
                hasError = true;
            }

            if (status === '') {
                $('#statusError').text('Status is required.');
                hasError = true;
            }

            if (hasError) {
                submitButton.prop('disabled', false);
                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
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
                .done(function(response) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Your Leave Type has been saved",
                        showConfirmButton: false,
                        timer: 1500,
                        text: response.message,
                    });
                    $('#addLeave')[0].reset();
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
                            text: 'Failed to save Leave Types.',
                        });
                    }
                })
                .always(function() {
                    submitButton.prop('disabled', false);
                });
        });

    });
</script>
@endpush
