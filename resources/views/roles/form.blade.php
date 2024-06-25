@extends('masterLayout.app')
@section('main')
@section('page-title')
    create Role
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    {!! Form::model($role, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => $role->exists ? 'editRoleForm' : 'addRoleForm',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="mb-3 form-group">
                        {!! Form::label('name', 'Role Name') !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        <div id="nameError" class="text-danger"></div>
                    </div>

                    <div class="box-footer">
                        {!! Form::submit($role->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('roles.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('#addRoleForm, #editRoleForm').submit(function(e) {
            e.preventDefault();

            const roleName = $('input[name="name"]').val().trim();

            if (roleName === '') {
                toastr.error('Role is required.');
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
                    console.log(response);
                    toastr.success(response.message);
                    if ($(e.target).attr('id') === 'addRoleForm') {
                        $('#addRoleForm')[0].reset();
                    }
                    button.prop('disabled', false);
                })
                .catch(function(err) {
                    console.error(err);
                    toastr.error('Failed to save Role.');
                    button.prop('disabled', false);
                });
        });
    });
</script>
@endpush
