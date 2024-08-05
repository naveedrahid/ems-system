@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Designation
@endsection
@section('page-content')
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">All Designation</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('designation.create') }}" class="btn btn-success text-bold"
                                        data-toggle="modal" data-target="#selectedNotes" data-type="add">
                                        Add <i class="fas fa-plus" style="font-size: 13px;"></i>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0" id="designationWrapper">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="10%">Date</th>
                                <th width="16%">Designation</th>
                                <th width="30%">Department Name</th>
                                <th width="20%">Status</th>
                                <th width="20%">Manage</th>
                            </tr>
                        </thead>
                        <tbody id="designations-body">
                            <div id="loadingSpinner" style="display: none; text-align: center;">
                                <i class="fas fa-spinner fa-spin fa-3x"></i>
                            </div>
                        </tbody>
                    </table>
                    <ul id="pagination"></ul>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="selectedNotes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Designation Form</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i
                            class="fas fa-times"></i></button>
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
    #designationWrapper ul#pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 15px 0px;
    }

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
        loadForm('a[data-type="add"]', '#formContainer', '{{ route('designation.create') }}',
            'designationData');
        loadForm('.edit-designation', '#formContainer', '/designation/{id}/edit', 'designationDataUpdate');

        function fetchDesignationData(){
            const endPoint = "{{ route('designation.data') }}";
            const targetTable = $('#designations-body');
            const targetPagination = $('#pagination');
    
            const htmlRowCallback = (designation) => `
                                                    <tr>
                                                        <td>${new Date(designation.created_at).toLocaleDateString()}</td>
                                                        <td>${designation.designation_name}</td>
                                                        <td>${designation.department ? designation.department.department_name : 'N/A'}</td>
                                                        <td>
                                                            <div class="manage-process">
                                                                <a style="width:70px;text-align:right;" href="#"
                                                                    class="designation-toggle" data-id="${designation.id}"
                                                                    data-status="${designation.status}">
                                                                    <span class="badges ${designation.status === 'active' ? 'active-badge' : designation.status === 'deactive' ? 'deactive-badge' : 'active-badge'}">
                                                                        ${designation.status}
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="manage-process">
                                                                <a href="#" class="edit-designation edit-item" data-toggle="modal" data-target="#selectedNotes" data-id="${designation.id}"><i class="fa fa-edit"></i> Edit</a>
                                                                <a href="#">
                                                                    <div class="delete-item delete-designation" data-designation-id="${designation.id}" data-delete-route="/designation/${designation.id}">
                                                                        <i class="far fa-trash-alt"></i> Delete
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>`;
    
            fetchDataGlobal(1, endPoint, targetTable, targetPagination, htmlRowCallback);
            initializePaginationClickHandler(endPoint, targetTable, targetPagination, htmlRowCallback);
        }
        fetchDesignationData();

        $(document).on('click', '.delete-designation', function(e) {
            e.preventDefault();
            const leaveType = $(this).data('designation-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', leaveType);
            const targetElement = $(this);
            if (confirm('Are you sure you want to delete this designation?')) {
                $('#loadingSpinner2').show();
                const token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(result) {
                    setTimeout(() => {
                        $('#loadingSpinner2').hide();
                        toastr.success(result.message);
                    }, 1000);
                    targetElement.closest('tr').fadeOut('slow', function() {
                        $(this).remove();
                    });
                    fetchDesignationData();
                }).catch(function(err) {
                    console.log(err);
                    toastr.error('Faild to delete Designation');
                    $('#loadingSpinner2').hide();
                });
            }
        });

        $(document).on('click', '.designation-toggle', function(e) {
            e.preventDefault();

            const $this = $(this);
            const designationId = $this.data('id');
            const currentStatus = $this.data('status');
            const newStatus = currentStatus === 'active' ? 'deactive' : 'active';
            const token = $('meta[name="csrf-token"]').attr('content');
            $('#loadingSpinner').show();

            $.ajax({
                url: '/designation-status/' + designationId,
                method: 'PUT',
                data: {
                    status: newStatus
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    setTimeout(() => {
                        $('#loadingSpinner').hide();
                        toastr.success(response.message);
                    }, 1000);
                    const $span = $this.find('span');
                    $span.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                    $span.removeClass('active-badge deactive-badge');
                    $span.addClass(newStatus === 'active' ? 'active-badge' :
                        'deactive-badge');
                    $this.data('status', newStatus);
                },
                error: function(err) {
                    console.log(err);
                    $('#loadingSpinner').hide();
                    toastr.error('Designation status failed.');
                }
            });
        });

        $('#formContainer').on('submit', '#designationData, #designationDataUpdate', function(e) {
            e.preventDefault();

            const departmentId = $('select[name="department_id"]').val().trim();
            const designationName = $('input[name="designation_name"]').val().trim();
            const status = $('select[name="status"]').val().trim();
            let hasError = false;

            if (departmentId == '') {
                toastr.error('Name is required.');
                hasError = true;
            }
            if (designationName == '') {
                toastr.error('Designation is required.');
                hasError = true;
            }
            if (status == '') {
                toastr.error('Status is required.');
                hasError = true;
            }

            if (hasError) return;

            $('#loadingSpinner2').show();
            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);

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
                    fetchDesignationData();
                    toastr.success(response.message);
                    button.prop('disabled', false);
                    if ($(e.target).attr('id') === 'designationData') {
                        $('#designationData')[0].reset();
                    }
                    $('#selectedNotes').modal('hide');
                    $('#loadingSpinner2').hide();
                })
                .catch(function(err) {
                    console.error(err);
                    toastr.error('Failed to save Designation.');
                    button.prop('disabled', false);
                    $('#loadingSpinner2').hide();
                });
        });
    });
</script>
@endpush
