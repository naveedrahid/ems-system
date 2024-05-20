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
                    <form id="updateRoleForm" action="{{ route('role_update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="redirect-url" value="{{ route('roles') }}">
                        <div class="mb-3 form-group">
                            <label class="form-label">Edit Role</label>
                            <input type="text" name="add_role" id="add_role" class="form-control"
                                value="{{ $role->name }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
                    </form>
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
        $('#updateRoleForm').submit(function(e) {
            e.preventDefault();

            const dp_name = $('input[name="add_role"]').val().trim();
            if (dp_name === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Role Name cannot be empty.',
                });
                return;
            }
            const formData = new FormData(this);
            formData.append('_method', 'PUT');
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
                        text: 'Failed to update Role.',
                    });
                });
        });
    });
</script>
@endpush
