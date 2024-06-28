@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Complaints
@endsection
@section('page-content')
    <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #fff;">
                    <tr>
                        <th width="20%">Date</th>
                        <th width="30%">Ticker Number</th>
                        <th width="20%">User Name</th>
                        <th width="20%">Status</th>
                        <th width="10%">Manage</th>
                    </tr>
                </thead>
                <tbody style="background-color: #fff;">
                    @if ($allData['complaints']->count() > 0)
                        @foreach ($allData['complaints'] as $complaint)
                            <tr>
                                @php
                                    $employee = $allData['employees']->firstWhere('user_id', $complaint->user_id);
                                @endphp
                                <td>{{ $complaint->created_at->toFormattedDateString() }}</td>
                                <td>{{ $complaint->ticket_number }}</td>
                                <td>{{ $employee->user->name }}</td>
                                <td>
                                    @if (!isAdmin(auth()->user()))
                                        <button type="button"
                                            class="btn btn-{{ $complaint->complaint_status == 'resolved' ? 'success' : ($complaint->complaint_status == 'pending' ? 'warning' : 'primary') }}">
                                            {{ ucwords(str_replace('_', ' ', $complaint->complaint_status)) }}
                                        </button>
                                    @else
                                        <div class="complaint-row" data-id="{{ $complaint->id }}">
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-{{ $complaint->complaint_status == 'resolved' ? 'success' : ($complaint->complaint_status == 'pending' ? 'warning' : 'primary') }} status-btn setDisabled">
                                                    {{ ucwords(str_replace('_', ' ', $complaint->complaint_status)) }}
                                                </button>
                                                <button type="button"
                                                    class="btn btn-{{ $complaint->complaint_status == 'resolved' ? 'success' : ($complaint->complaint_status == 'pending' ? 'warning' : 'primary') }} setDisabled dropdown-toggle"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    @foreach ($allData['statuses'] as $status)
                                                        @if ($status !== 'pending')
                                                            @php
                                                                $formattedStatus = str_replace('_', ' ', $status);
                                                            @endphp
                                                            <li><a href="#" class="status-change"
                                                                    data-status="{{ $status }}"
                                                                    data-id="{{ $complaint->id }}">{{ ucwords($formattedStatus) }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#complaintModal" data-content="{{ $complaint->content }}"
                                        data-department="{{ $employee->department->department_name }}"
                                        data-type="{{ $employee->employeeType->type ?? '' }}"
                                        data-designation="{{ $employee->designation->designation_name }}">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    @php
                                        $user = auth()->user();
                                    @endphp
                                    @if (isAdmin($user))
                                        <button class="delete-complaint btn btn-danger btn-flat btn-sm"
                                            data-complaint="{{ $complaint->id }}"
                                            data-delete-route="{{ route('complaints.destroy', $complaint->id) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @else
                        <tr><td class="text-center" colspan="8">No Record Found</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal Structure -->
    <div class="modal modal-white fade in" id="complaintModal" tabindex="-1" role="dialog"
        aria-labelledby="complaintModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="complaintModalLabel">Complaint Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Department:</strong> <span id="employeeDepartment"></span></p>
                    <p><strong>Designation:</strong> <span id="employeeDesignation"></span></p>
                    <p><strong>Employee Type:</strong> <span id="employeeEmployeeType"></span></p>
                    <div id="complaintContent"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('#complaintModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const content = button.data('content');
            const designation = button.data('designation');
            const department = button.data('department');
            const employeeType = button.data('type');

            // Update the modal's content.
            const modal = $(this);
            modal.find('#employeeDepartment').text(department);
            modal.find('#employeeDesignation').text(designation);
            modal.find('#employeeEmployeeType').text(employeeType);
            modal.find('#complaintContent').html(content);
        });

        $('.status-change').click(function(e) {
            e.preventDefault();

            const token = $('meta[name="csrf-token"]').attr('content');
            const newStatus = $(this).data('status');
            const complaintId = $(this).data('id');
            const row = $(this).closest('.complaint-row');
            const buttons = row.find('.setDisabled');

            buttons.prop('disabled', true);

            $.ajax({
                url: '/complaints/' + complaintId,
                type: 'POST',
                data: {
                    complaint_status: newStatus,
                    _token: token
                },
                success: function(response) {
                    let statusBtn = row.find('.status-btn');
                    statusBtn.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                    statusBtn.removeClass('btn-warning btn-success btn-danger');

                    if (newStatus === 'resolved') {
                        statusBtn.addClass('btn-success');
                    } else if (newStatus === 'pending') {
                        statusBtn.addClass('btn-warning');
                    } else {
                        statusBtn.addClass('btn-primary');
                    }

                    let dropdownToggle = row.find('.dropdown-toggle');
                    dropdownToggle.removeClass('btn-warning btn-success btn-danger');

                    if (newStatus === 'resolved') {
                        dropdownToggle.addClass('btn-success');
                    } else if (newStatus === 'pending') {
                        dropdownToggle.addClass('btn-warning');
                    } else {
                        dropdownToggle.addClass('btn-primary');
                    }
                    toastr.success(response.message);
                    buttons.prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    toastr.success(response.message);
                    console.error(xhr.responseText);
                    buttons.prop('disabled', false);
                }
            });
        });

        $('.delete-complaint').on('click', function(e) {
            e.preventDefault();

            const token = $('meta[name="csrf-token"]').attr('content');
            const complaintId = $(this).data('complaint-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', complaintId);
            const $clickedElement = $(this);
            if (confirm('Are you sure you want to delete this complaint?')) {
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
                        $(this).remove();
                    });
                }).catch(function(xhr) {
                    console.error(xhr);
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred while trying to delete the complaint.');
                    }
                });
            }
        });
    });
</script>
@endpush
