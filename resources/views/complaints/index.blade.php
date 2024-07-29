@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Complaints
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
                                <h4 class="text-bold">All Complaints</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('complaints.create') }}" class="btn btn-success text-bold">
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
                                <th width="20%">Date</th>
                                <th width="30%">Ticker Number</th>
                                <th width="20%">User Name</th>
                                <th width="20%">Status</th>
                                <th width="10%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($allData['complaints']->count() > 0)
                                @foreach ($allData['complaints'] as $complaint)
                                    <tr>
                                        @php
                                            $employee = $allData['employees']->firstWhere(
                                                'user_id',
                                                $complaint->user_id,
                                            );
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
                                                                        $formattedStatus = str_replace(
                                                                            '_',
                                                                            ' ',
                                                                            $status,
                                                                        );
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
                                            <div class="manage-process">
                                                <a href="#" data-toggle="modal" data-target="#complaintModal"
                                                    data-content="{{ $complaint->content }}"
                                                    data-department="{{ $employee->department->department_name }}"
                                                    data-type="{{ $employee->employeeType->type ?? '' }}"
                                                    data-designation="{{ $employee->designation->designation_name }}">
                                                    <div class="edit-item"><i class="fas fa-eye"></i> View</div>
                                                </a>
                                                @php
                                                    $user = auth()->user();
                                                @endphp
                                                @if (isAdmin($user))
                                                    <a href="#">
                                                        <div class="delete-item delete-complaint"
                                                            data-complaint="{{ $complaint->id }}"
                                                            data-delete-route="{{ route('complaints.destroy', $complaint->id) }}">
                                                            <i class="far fa-trash-alt"></i> Delete
                                                        </div>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
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

@push('css')
<style>
    .modal-body p {
        color: #000;
        font-size: 15px !important;
    }

    div#complaintContent {
        border: solid 1px #cccc;
        border-radius: 5px;
        margin-top: 15px;
        padding: 10px 10px;
    }

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
            $('#loadingSpinner').show();
            buttons.prop('disabled', true);

            $.ajax({
                    url: `/complaints/${complaintId}`,
                    method: 'POST',
                    data: {
                        complaint_status: newStatus,
                        _token: token
                    },
                })
                .then((response) => {
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
                    buttons.prop('disabled', false);
                    $('#loadingSpinner').hide();
                    toastr.success(response.message);

                }).catch((err) => {
                    toastr.error(response.message);
                    console.error(err);
                    buttons.prop('disabled', false);
                });
        });

        $('.delete-complaint').on('click', function(e) {
            e.preventDefault();

            const token = $('meta[name="csrf-token"]').attr('content');
            const complaintId = $(this).data('complaint-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', complaintId);
            const $clickedElement = $(this);

            if (confirm('Are you sure you want to delete this complaint?')) {
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

                    $clickedElement.closest('tr').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }).catch(function(xhr) {
                    $('#loadingSpinner').hide();
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
