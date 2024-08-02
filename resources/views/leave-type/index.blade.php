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
                                    <a href="{{ route('leave-types.create') }}" class="btn btn-success text-bold"
                                        data-toggle="modal" data-target="#leaveTypeModal" data-type="add">
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
                        <tbody id="leaveTypesTable">
                            <div id="loadingSpinner" style="display: none; text-align: center;">
                                <i class="fas fa-spinner fa-spin fa-3x"></i>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="leaveTypeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Department Form</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i
                                class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="formContainer">
                            <div id="loadingSpinner2" style="display: none; text-align: center;">
                                <i class="fas fa-spinner fa-spin fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endsection

@push('css')
    <style>
        .modal-dialog {
            max-width: 600px !important;
            margin: 2.75rem auto !important;
            border-radius: 20px !important;

            .modal-title {
                font-size: 18px !important;
                font-weight: 600 !important;
            }

            .modal-content {
                border-radius: 20px;
                box-shadow: 0 .1rem .5rem rgba(0, 0, 0, .5);
            }

            label {
                font-weight: 600 !important;
                font-size: 15px;
            }

            .modal-header {
                padding-top: 0;
                padding-left: 7px;
                border: none;
            }

            .form-control {
                height: calc(2.25rem + 0px);
                font-size: 15px;
                border-radius: 10px;

                &:focus {
                    border: 1.5px solid #80BDFF;
                }
            }

            .btn-close {
                border: 0;
                background: none;
            }

            .btn-primary {
                font-weight: 600;
                border-radius: 10px;
            }

            .col-md-6 {
                padding-left: 0;
            }
        }

        .profile-box .profile-img {
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
        }

        div#loadingSpinner,
        div#loadingSpinner2 {
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

        div#loadingSpinner i,
        div#loadingSpinner2 i {
            color: #007bff;
        }
    </style>
@endpush
@push('js')
    <script>
        $(document).ready(function() {

            $('a[data-type="add"]').click(function(e) {
                e.preventDefault();
                $('#loadingSpinner2').show();
                $('#formContainer').load("{{ route('leave-types.create') }}", function() {
                    $('#loadingSpinner2').hide();
                    $('#formContainer form').attr('id', 'addLeave');

                });
            });

            $('#leaveTypesTable').on('click', '.edit-leaveType', function() {
                const leaveTypeId = $(this).data('id');
                $('#loadingSpinner2').show();

                $('#formContainer').load(`/leave-types/${leaveTypeId}/edit`, function() {
                    $('#loadingSpinner2').hide();
                    $('#formContainer form').attr('id', 'updateLeave');
                });
            });

            $(document).on('click', '.delete-leave-type', function(e) {
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
                        fetchLeaveTypesData();
                    }).catch(function(xhr) {
                        console.error(xhr);
                        $('#loadingSpinner').hide();
                        toastr.error('Failed to delete Leave type');
                    });
                }
            });

            $(document).on('click', '.leave-toggle', function(e) {
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

            const fetchLeaveTypesData = async () => {
                const url = "{{ route('leave-types.data') }}";
                $('#loadingSpinner').show();

                try {
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                        }
                    });

                    const tableData = $('#leaveTypesTable');
                    tableData.empty();

                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                        $('#loadingSpinner').hide();
                    }

                    const data = await response.json();

                    if (data.length === 0) {
                        tableData.append(
                            '<tr><td colspan="3" class="text-center">No record found</td></tr>');
                        $('#loadingSpinner').hide();
                    } else {
                        $.each(data, function(_, leaveType) {
                            $('#loadingSpinner').hide();
                            const row = `                                <tr>
                                    <td>${new Date(leaveType.created_at).toLocaleDateString()}</td>
                                    <td>${leaveType.name}</td>
                                    <td>4${leaveType.description}</td>
                                    <td>${leaveType.default_balance}</td>
                                    <td>
                                        <a href="#" class="leave-toggle" data-id="${leaveType.id}"
                                            data-status="${leaveType.status}">
                                            <span
                                                class="badges ${leaveType.status === 'active' ? 'active-badge' : 'deactive-badge'}">
                                                ${leaveType.status}
                                            </span>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="#" class="edit-leaveType edit-item"
                                                data-toggle="modal" data-target="#leaveTypeModal"
                                                data-id="${leaveType.id}"><i class="fa fa-edit"></i> edit
                                            </a>
                                            <a href="#">
                                                <div class="delete-item delete-leave-type"
                                                    data-leave-type-id="${leaveType.id}"
                                                    data-delete-route="/leave-types/${leaveType.id}">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>`;
                            tableData.append(row);
                        });
                    }

                } catch (error) {
                    console.error('Error:', error);
                    $('#loadingSpinner').hide();
                }
            }

            fetchLeaveTypesData();

            $(document).on('submit', '#addLeave , #updateLeave', function(e) {
                e.preventDefault();

                const name = $('input[name="name"]').val().trim();
                const defaultBalance = $('input[name="default_balance"]').val().trim();
                const status = $('select[name="status"]').val().trim();

                $('.text-danger').text('');
                const button = $('input[type="submit"]');
                button.prop('disabled', true);

                if (name === '' || defaultBalance === '' || status === '') {
                    if (name === '') {
                        toastr.error('Name is required.');
                    }

                    if (defaultBalance === '') {
                        toastr.error('Total Leaves is required.');
                    }

                    if (status === '') {
                        toastr.error('Status is required.');
                    }
                }

                const formData = new FormData(this);
                const url = $(this).attr('action');
                const token = $('meta[name="csrf-token"]').attr('content');
                $('#loadingSpinner').show();

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
                    .done(function(response) {
                        toastr.success(response.message);
                        button.prop('disabled', false);
                        if ($(e.target).attr('id') === 'addLeave') {
                            $('#addLeave')[0].reset();
                        }
                        fetchLeaveTypesData();
                        $('#loadingSpinner').hide();
                        $('#leaveTypeModal').modal('hide');
                    })
                    .fail(function(xhr) {
                        console.error(xhr);
                        toastr.error('Failed to save Leave Types.');
                        button.prop('disabled', false);
                        $('#loadingSpinner').hide();
                    })
                    .always(function() {
                        button.prop('disabled', false);
                        $('#loadingSpinner').hide();
                    });
            });
        });
    </script>
@endpush
