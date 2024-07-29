@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Leave Applications
@endsection
@section('page-content')
    @php
        $user = auth()->user();
    @endphp
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
                                <h4 class="text-bold">Leave Application</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('leave-applications.create') }}" class="btn btn-success text-bold">
                                        Add <i class="fas fa-plus" style="font-size: 13px;"></i>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="15%">Employee Name</th>
                                <th width="10%">Leave Type</th>
                                <th width="20%">Date</th>
                                <th width="10%">Image</th>
                                <th width="20%">Reason</th>
                                <th width="5%">Days</th>
                                <th width="10%">Leave Status</th>
                                @if (isAdmin($user))
                                    <th width="15%">Manage</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leaveApplications as $leaveApplication)
                                <tr>
                                    <td>{{ $leaveApplication->user->name ?? '' }}</td>
                                    <td>{{ $leaveApplication->leaveType->name ?? '' }}</td>
                                    <td>{{ $leaveApplication->start_date }} - {{ $leaveApplication->end_date }}</td>
                                    <td>
                                        @if (!$leaveApplication->leave_image)
                                            <img src="{{ asset('admin/images/image-not-found.png') }}" width="70"
                                                height="70" alt="">
                                        @else
                                            <img src="{{ asset('upload/' . $leaveApplication->leave_image) }}"
                                                width="70" height="70" alt="">
                                        @endif
                                    </td>
                                    <td>{{ $leaveApplication->reason }}</td>
                                    <td>{{ $leaveApplication->total_leave }}</td>
                                    <td>
                                        @php
                                            $statusUpdate = $leaveApplication->status;
                                        @endphp
                                        @if (!isAdmin($user))
                                            <button type="button"
                                                class="btn btn-{{ $statusUpdate == 'Approved' ? 'success' : ($statusUpdate == 'Pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($leaveApplication->status) }}
                                            </button>
                                        @else
                                            <div class="leave-application-row" data-id="{{ $leaveApplication->id }}">
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-{{ $statusUpdate == 'Approved' ? 'success' : ($statusUpdate == 'Pending' ? 'warning' : 'danger') }} status-btn setDisabled">
                                                        {{ ucfirst($leaveApplication->status) }}
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-{{ $statusUpdate == 'Approved' ? 'success' : ($statusUpdate == 'Pending' ? 'warning' : 'danger') }} setDisabled dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="#" class="status-change" data-status="approved"
                                                                data-id="{{ $leaveApplication->id }}">Approved</a></li>
                                                        <li><a href="#" class="status-change" data-status="rejected"
                                                                data-id="{{ $leaveApplication->id }}">Rejected</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    @if (isAdmin($user))
                                        <td>
                                            <div class="manage-process">
                                                <a href="{{ route('leave-applications.edit', $leaveApplication) }}"
                                                    class="edit-item">
                                                    <i class="fa fa-edit"></i> edit
                                                </a>
                                                <a href="#">
                                                    <div class="delete-item delete-leave-application"
                                                        data-leave-app-id="{{ $leaveApplication->id }}"
                                                        data-delete-route="{{ route('leave-applications.destroy', ':id') }}">
                                                        <i class="far fa-trash-alt"></i> Delete
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="9">No record found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $leaveApplications->links('pagination::bootstrap-4') }}
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

            $('.status-change').click(function(e) {
                e.preventDefault();
                const newStatus = $(this).data('status');
                const leaveId = $(this).data('id');
                const row = $(this).closest('.leave-application-row');
                const buttons = row.find('.setDisabled');

                buttons.prop('disabled', true);

                $.ajax({
                    url: '/leave-applications/' + leaveId,
                    type: 'POST',
                    data: {
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        let statusBtn = row.find('.status-btn');
                        statusBtn.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                        statusBtn.removeClass('btn-warning btn-success btn-danger');

                        if (newStatus === 'approved') {
                            statusBtn.addClass('btn-success');
                        } else if (newStatus === 'rejected') {
                            statusBtn.addClass('btn-danger');
                        } else {
                            statusBtn.addClass('btn-warning');
                        }

                        let dropdownToggle = row.find('.dropdown-toggle');
                        dropdownToggle.removeClass('btn-warning btn-success btn-danger');

                        if (newStatus === 'approved') {
                            dropdownToggle.addClass('btn-success');
                        } else if (newStatus === 'rejected') {
                            dropdownToggle.addClass('btn-danger');
                        } else {
                            dropdownToggle.addClass('btn-warning');
                        }

                        buttons.prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        buttons.prop('disabled', false);
                    }
                });
            });

            $('.delete-leave-application').on('click', function(e) {
                e.preventDefault();

                const leaveId = $(this).data('leave-app-id');
                const deleteRoute = $(this).data('delete-route').replace(':id', leaveId);
                const token = $('meta[name="csrf-token"]').attr('content');
                const clickedElement = $(this);

                if (confirm('Are you sure? You will not be able to recover this holiday!')) {
                    $('#loadingSpinner').show();

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
                            $(this).css('backgroundColor', 'red').remove();
                        });
                    }).catch(function(xhr) {
                        console.error(xhr);
                        $('#loadingSpinner').hide();
                        toastr.error('Failed to delete Holiday');
                    });
                }
            });
        });
    </script>
@endpush
