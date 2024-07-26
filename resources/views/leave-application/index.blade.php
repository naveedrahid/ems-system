@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Leave Applications
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('leave-applications.create') }}" class="btn btn-primary">
                    Request a leave
                </a>
            </h3>
        </div>
        @php
            $user = auth()->user();
        @endphp
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #fff;">
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
                <tbody style="background: #fff;">
                    @if ($leaveApplications->count() > 0)
                        @foreach ($leaveApplications as $leaveApplication)
                            <tr>
                                <td>{{ $leaveApplication->user->name ?? '' }}</td>
                                <td>{{ $leaveApplication->leaveType->name ?? '' }}</td>
                                <td>{{ $leaveApplication->start_date }} - {{ $leaveApplication->end_date }}</td>
                                <td>
                                    @if (!$leaveApplication->leave_image)
                                        <img src="{{ asset('admin/images/image-not-found.png') }}" width="70"
                                            height="70" alt="">
                                    @else
                                        <img src="{{ asset('upload/' . $leaveApplication->leave_image) }}" width="70"
                                            height="70" alt="">
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
                                        <a href="{{ route('leave-applications.edit', $leaveApplication) }}"
                                            class="btn btn-info btn-flat btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button class="delete-leave-application btn btn-danger btn-flat btn-sm"
                                            data-leave-app-id="{{ $leaveApplication->id }}"
                                            data-delete-route="{{ route('leave-applications.destroy', ':id') }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            {{ $leaveApplications->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
@endsection
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
                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(response) {
                    toastr.success(response.message);
                    clickedElement.closest('tr').fadeOut('slow', function() {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                }).catch(function(xhr) {
                    console.error(xhr);
                    toastr.error('Failed to delete Holiday');
                });
            }
        });
    });
</script>
@endpush
