@extends('masterLayout.app')
@section('main')
@section('page-title')
    View Role
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a class="btn btn-danger btn-xm"><i class="fa fa-trash"></i></a>
                <a href="{{ route('role_create') }}" class="btn btn-default btn-xm"><i class="fa fa-plus"></i></a>
            </h3>
            <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th width="4%"><input type="checkbox" name="" id="checkAll"></th>
                        <th width="16%">ID</th>
                        <th width="30%">Role Name</th>
                        <th width="20%">Status</th>
                        <th width="20%">Edit</th>
                    </tr>
                </thead>
                @if (count($roles) > 0)
                    @foreach ($roles as $role)
                        <tr>
                            <td><input type="checkbox" name="" id="" class="checkSingle"></td>
                            <td>#{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <button
                                    class="p-relative role-toggle btn btn-{{ $role->status === 'active' ? 'info' : 'danger' }} btn-sm"
                                    data-id="{{ $role->id }}" data-status="{{ $role->status }}">
                                    <i class="fa fa-thumbs-{{ $role->status === 'active' ? 'up' : 'down' }}"></i>
                                    <img src="{{ asset('admin/images/loader.gif') }}" class="imgLoader" width="20"
                                        height="20" alt="Loading...">
                                </button>
                            </td>
                            <td>
                                <a href="{{ route('role_edit', $role->id) }}" class="btn btn-info btn-flat btn-sm"> <i
                                        class="fa fa-edit"></i></a>
                                <button class="delete-role btn btn-danger btn-flat btn-sm"
                                    data-role-id="{{ $role->id }}"
                                    data-delete-route="{{ route('role_destroy', ':id') }}"><i
                                        class="fa-regular fa-trash-can"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
        <div class="box-footer clearfix">
            <div class="row">
                <div class="col-sm-6">
                    <span style="display:block;font-size:15px;line-height:34px;margin:20px 0;">
                        Showing 100 to 500 of 1000 entries
                    </span>
                </div>
                <div class="col-sm-6 text-right">
                    <ul class="pagination">
                        <li class="paginate_button previous"><a href="#">Previous</a></li>
                        <li class="paginate_button active"><a href="#">1</a></li>
                        <li class="paginate_button "><a href="#">2</a></li>
                        <li class="paginate_button "><a href="#">3</a></li>
                        <li class="paginate_button "><a href="#">4</a></li>
                        <li class="paginate_button "><a href="#">5</a></li>
                        <li class="paginate_button "><a href="#">6</a></li>
                        <li class="paginate_button next"><a href="#">Next</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('.role-toggle').click(function() {
            const button = $(this);
            const id = button.data('id');
            const status = button.data('status');
            const newStatus = status === 'active' ? 'deactive' : 'active';
            const statusIcon = status === 'active' ? 'down' : 'up';
            const btnClass = status === 'active' ? 'danger' : 'info';
            const loader = button.find('img');

            $.ajax({
                url: '/role-status/' + id,
                method: 'PUT',
                data: {
                    status: newStatus
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    button.removeClass('btn-' + (status === 'active' ? 'info' : 'danger'))
                        .addClass('btn-' + btnClass);
                    button.find('i').removeClass('fa-thumbs-' + (status === 'active' ?
                        'up' : 'down')).addClass('fa-thumbs-' + statusIcon);
                    button.data('status', newStatus);
                    loader.css('display', 'block');
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        },
                        willClose: () => {
                            loader.css('display', 'none');
                        }
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Status " + newStatus.charAt(0).toUpperCase() +
                            newStatus.slice(1) + " successfully"
                    });
                },
                error: function(xhr) {
                    console.error(xhr);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        },
                        willClose: () => {
                            loader.css('opacity', '0');
                        }
                    });
                    Toast.fire({
                        icon: "error",
                        title: "Failed to update status"
                    });
                }
            });
        });

        $('.delete-role').on('click', function(e) {
            e.preventDefault();
            const roleId = $(this).data('role-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', roleId);
            const $clickedElement = $(this);

            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this Role!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: "DELETE",
                        url: deleteRoute,
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    }).then(function(response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });
                        $clickedElement.closest('tr').fadeOut('slow', function() {
                            $(this).css('backgroundColor', 'red').remove();
                        });
                    }).catch(function(xhr) {
                        console.error(xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete Role.',
                        });
                    });
                }
            });
        });
    });
</script>
@endpush
