@extends('masterLayout.app')
@section('main')
@section('page-title')
    View Role
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('roles.create') }}" class="btn btn-block btn-primary">
                    Create Roles
                </a>
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th width="20%">ID</th>
                        <th width="50%">Role Name</th>
                        <th width="20%">Manage</th>
                    </tr>
                </thead>
                @if (count($roles) > 0)
                    @foreach ($roles as $role)
                        <tr>
                            <td>#{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-info btn-flat btn-sm"> <i
                                        class="fa fa-edit"></i></a>
                                <button class="delete-role btn btn-danger btn-flat btn-sm"
                                    data-role-id="{{ $role->id }}"
                                    data-delete-route="{{ route('roles.destroy', ':id') }}"><i
                                        class="fa-regular fa-trash-can"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
        <div class="box-footer clearfix">

        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        // $('.role-toggle').click(function() {
        //     const button = $(this);
        //     const id = button.data('id');
        //     const status = button.data('status');
        //     const newStatus = status === 'active' ? 'deactive' : 'active';
        //     const statusIcon = status === 'active' ? 'down' : 'up';
        //     const btnClass = status === 'active' ? 'danger' : 'info';
        //     const loader = button.find('img');

        //     $.ajax({
        //         url: '/role-status/' + id,
        //         method: 'PUT',
        //         data: {
        //             status: newStatus
        //         },
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(response) {
        //             button.removeClass('btn-' + (status === 'active' ? 'info' : 'danger'))
        //                 .addClass('btn-' + btnClass);
        //             button.find('i').removeClass('fa-thumbs-' + (status === 'active' ?
        //                 'up' : 'down')).addClass('fa-thumbs-' + statusIcon);
        //             button.data('status', newStatus);
        //             loader.css('display', 'block');
        //             const Toast = Swal.mixin({
        //                 toast: true,
        //                 position: "top-end",
        //                 showConfirmButton: false,
        //                 timer: 1000,
        //                 timerProgressBar: true,
        //                 didOpen: (toast) => {
        //                     toast.onmouseenter = Swal.stopTimer;
        //                     toast.onmouseleave = Swal.resumeTimer;
        //                 },
        //                 willClose: () => {
        //                     loader.css('display', 'none');
        //                 }
        //             });
        //             Toast.fire({
        //                 icon: "success",
        //                 title: "Status " + newStatus.charAt(0).toUpperCase() +
        //                     newStatus.slice(1) + " successfully"
        //             });
        //         },
        //         error: function(xhr) {
        //             console.error(xhr);
        //             const Toast = Swal.mixin({
        //                 toast: true,
        //                 position: "top-end",
        //                 showConfirmButton: false,
        //                 timer: 1000,
        //                 timerProgressBar: true,
        //                 didOpen: (toast) => {
        //                     toast.onmouseenter = Swal.stopTimer;
        //                     toast.onmouseleave = Swal.resumeTimer;
        //                 },
        //                 willClose: () => {
        //                     loader.css('opacity', '0');
        //                 }
        //             });
        //             Toast.fire({
        //                 icon: "error",
        //                 title: "Failed to update status"
        //             });
        //         }
        //     });
        // });

        $('.delete-role').on('click', function(e) {
            e.preventDefault();
            const roleId = $(this).data('role-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', roleId);
            const $clickedElement = $(this);

            if (confirm('Are you sure you want to delete this Role?')) {
                const token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(response) {
                    console.log(response);
                    toastr.success(response.message);
                    $clickedElement.closest('tr').fadeOut('slow', function() {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                }).catch(function(xhr) {
                    console.error(xhr);
                    toastr.error('Failed to save Role Delete.');
                    button.prop('disabled', false);
                });
            }
        });
    });
</script>
@endpush
