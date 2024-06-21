@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Leave Types
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('leave-types.create') }}" class="btn btn-default btn-xm"><i class="fa fa-plus"></i></a>
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
                        <th width="10%">date</th>
                        <th width="20%">Name</th>
                        <th width="25%">Description</th>
                        <th width="20%">Leave Balance</th>
                        <th width="10%">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($leaveTypes) > 0)
                        @foreach ($leaveTypes as $leaveType)
                            <tr>
                                <td>{{ $leaveType->created_at->toFormattedDateString() }}</td>
                                <td>{{ $leaveType->name }}</td>
                                <td>{{ $leaveType->description }}</td>
                                <td>{{ $leaveType->default_balance }}</td>
                                <td>
                                    <button
                                        class="p-relative leave-toggle btn btn-{{ $leaveType->status === 'active' ? 'info' : 'danger' }} btn-sm"
                                        data-id="{{ $leaveType->id }}" data-status="{{ $leaveType->status }}">
                                        <i class="fa fa-thumbs-{{ $leaveType->status === 'active' ? 'up' : 'down' }}"></i>
                                        <img src="{{ asset('admin/images/loader.gif') }}" class="imgLoader" width="20"
                                            height="20" alt="Loading...">
                                    </button>
                                    <a href="{{ route('leave-types.edit', $leaveType->id) }}"
                                        class="btn btn-info btn-flat btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="delete-leave-type btn btn-danger btn-flat btn-sm"
                                        data-leave-type-id="{{ $leaveType->id }}"
                                        data-delete-route="{{ route('leave-types.destroy', ':id') }}">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        // $('.delete-bank').on('click', function(e) {
        //     e.preventDefault();
        //     const bankId = $(this).data('bank-id');
        //     const deleteRoute = $(this).data('delete-route').replace(':id', bankId);
        //     const $clickedElement = $(this);

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: 'You will not be able to recover this bank details!',
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, delete it!',
        //         reverseButtons: true
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             const token = $('meta[name="csrf-token"]').attr('content');

        //             $.ajax({
        //                 type: "DELETE",
        //                 url: deleteRoute,
        //                 headers: {
        //                     'X-CSRF-TOKEN': token
        //                 }
        //             }).then(function(response) {
        //                 console.log(response);
        //                 Swal.fire({
        //                     icon: 'success',
        //                     title: 'Success!',
        //                     text: response.message,
        //                 });
        //                 $clickedElement.closest('tr').fadeOut('slow', function() {
        //                     $(this).css('backgroundColor', 'red').remove();
        //                 });
        //             }).catch(function(xhr) {
        //                 console.error(xhr);
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Error!',
        //                     text: 'Failed to delete Bank Details.',
        //                 });
        //             });
        //         }
        //     });
        // });
        $('.leave-toggle').click(function() {
            const button = $(this);
            const id = button.data('id');
            const status = button.data('status');
            const newStatus = status === 'active' ? 'deactive' : 'active';
            const statusIcon = status === 'active' ? 'down' : 'up';
            const btnClass = status === 'active' ? 'danger' : 'info';
            const loader = button.find('img');

            $.ajax({
                url: 'leave-types/status/' + id,
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
                        timer: 1500,
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
                        timer: 1500,
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
    });
</script>
@endpush