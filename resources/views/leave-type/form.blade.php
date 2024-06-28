@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $leave_type->exists ? 'Edit Leave Types' : 'Create Leave Types' }}
@endsection
@section('page-content')
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card small-box card-primary p-5">
                    {!! Form::model($leave_type, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => $leave_type->exists ? 'updateLeave' : 'addLeave',
                    ]) !!}

                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Leave Types Name') !!}
                                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'leave_name']) !!}
                                <div id="leave_nameError" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Total Leave') !!}
                                {!! Form::text('default_balance', null, ['class' => 'form-control', 'id' => 'default_balance']) !!}
                                <div id="default_balanceError" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
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
                        </div>
                        <div class="col-md-12 col-12">
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
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($leave_type->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('leave-types.index') }}" class="btn btn-danger">Cancel</a>
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
        $('#addLeave, #updateLeave').submit(function(e) {
            e.preventDefault();

            const name = $('input[name="name"]').val().trim();
            const defaultBalance = $('input[name="default_balance"]').val().trim();
            const status = $('select[name="status"]').val().trim();

            $('.text-danger').text('');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);

            if (name === '' || defaultBalance === '' || status === '') {
                if (name === '') {
                    toastr.error('Name is required.');
                }

                if (defaultBalance === '') {
                    toastr.error('Total Leaves is required.');
                }

                if (status === '') {
                    toastr.error('Status is required.');
                }
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
                    toastr.success(response.message);
                    button.prop('disabled', false);
                    if ($(e.target).attr('id') === 'addLeave') {
                        $('#addLeave')[0].reset();
                    }
                })
                .fail(function(xhr) {
                    console.error(xhr);
                    toastr.error('Failed to save Leave Types.');
                    button.prop('disabled', false);
                })
                .always(function() {
                    button.prop('disabled', false);
                });
        });

    });
</script>
@endpush
