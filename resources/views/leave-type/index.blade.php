@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Leave Types
@endsection
@section('page-content')
    <div id="loadingSpinner" style="display: none; text-align: center;">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">All Leave Types</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('leave-types.create') }}" class="btn btn-success text-bold">
                                        Add <i class="fas fa-plus" style="font-size: 13px;"></i>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="10%">date</th>
                                <th width="20%">Name</th>
                                <th width="25%">Description</th>
                                <th width="20%">Leave Balance</th>
                                <th width="10%">Status</th>
                                <th width="15%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leaveTypes as $leaveType)
                                <tr>
                                    <td>{{ $leaveType->created_at->toFormattedDateString() }}</td>
                                    <td>{{ $leaveType->name }}</td>
                                    <td>{{ $leaveType->description }}</td>
                                    <td>{{ $leaveType->default_balance }}</td>
                                    <td>
                                        <a href="#" class="leave-toggle" data-id="{{ $leaveType->id }}"
                                            data-status="{{ $leaveType->status }}">
                                            <span
                                                class="badges {{ $leaveType->status === 'active' ? 'active-badge' : ($leaveType->status === 'pending' ? 'pending-badge' : 'deactive-badge') }}">
                                                {{ ucfirst($leaveType->status) }}
                                            </span>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="{{ route('leave-types.edit', $leaveType) }}">
                                                <div class="edit-item"><i class="fas fa-edit"></i> Edit</div>
                                            </a>
                                            <a href="#">
                                                <div class="delete-item delete-leave-type"
                                                    data-leave-type-id="{{ $leaveType->id }}"
                                                    data-delete-route="{{ route('leave-types.destroy', ':id') }}">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="8">No Record Found!.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
@endsection

@push('css')
    <style>
        div#loadingSpinner {
            position: fixed;
            left: 0;
            right: 0;
            margin: auto;
            top: 0;
            bottom: 0;
            z-index: 99;
            background: #00000036;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        div#loadingSpinner i {
            color: #007bff;
        }
    </style>
@endpush
@push('js')
    <script>
        $(document).ready(function() {

            $('.delete-leave-type').on('click', function(e) {
                e.preventDefault();
                const leaveTypeId = $(this).data('leave-type-id');
                const deleteRoute = $(this).data('delete-route').replace(':id', leaveTypeId);
                const clickedElement = $(this);

                if (confirm('You will not be able to recover this Leave type!')) {
                    $('#loadingSpinner').show();

                    const token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: "DELETE",
                        url: deleteRoute,
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    }).then(function(response) {
                        setTimeout(() => {
                            $('#loadingSpinner').hide();
                            toastr.success(response.message);
                        }, 1000);
                        clickedElement.closest('tr').fadeOut('slow', function() {
                            $(this).remove();
                        })
                    }).catch(function(xhr) {
                        console.error(xhr);
                        $('#loadingSpinner').hide();
                        toastr.error('Failed to delete Leave type');
                    });
                }
            });

            $('.leave-toggle').on('click', function(e) {
                e.preventDefault();

                const $this = $(this);
                const leaveTypeId = $this.data('id');
                const currentStatus = $this.data('status');
                const newStatus = currentStatus === 'active' ? 'deactive' : 'active';
                const token = $('meta[name="csrf-token"]').attr('content');
                $('#loadingSpinner').show();

                $.ajax({
                        url: `/leave-types/status/${leaveTypeId}`,
                        method: 'PUT',
                        data: {
                            _token: token,
                            id: leaveTypeId,
                            status: newStatus
                        },
                    })
                    .then((response) => {
                        setTimeout(() => {
                            $('#loadingSpinner').hide();
                            toastr.success(response.message);
                        }, 1000);

                        $this.data('status', newStatus);
                        $this.find('span').text(newStatus.charAt(0).toUpperCase() + newStatus.slice(
                            1));
                        $this.find('span').attr('class', 'badges ' + (newStatus === 'active' ?
                            'active-badge' : 'deactive-badge'));
                    }).catch((err) => {
                        console.error(err);
                        toastr.error(response.message);
                        toastr.error('Failed to update status. Please try again.');
                    });
            });










            // $('.leave-toggle').click(function(e) {
            //     e.preventDefault();

            //     const button = $(this);
            //     const id = button.data('id');
            //     const status = button.data('status');
            //     const newStatus = status === 'active' ? 'deactive' : 'active';
            //     const statusIcon = status === 'active' ? 'down' : 'up';
            //     const btnClass = status === 'active' ? 'danger' : 'info';
            //     const loader = button.find('img');

            //     $.ajax({
            //         url: 'leave-types/status/' + id,
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
            //                 timer: 1500,
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
            //                 timer: 1500,
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
        });
    </script>
@endpush
