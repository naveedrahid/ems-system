@extends('masterLayout.app')
@section('main')
@section('page-title')
    View Role
@endsection
@section('page-content')
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">All Roles</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('roles.create') }}" class="btn btn-success text-bold"
                                        data-toggle="modal" data-target="#roleModal" data-type="add">
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
                                <th width="50%">Role Name</th>
                                <th width="20%">Manage</th>
                            </tr>
                        </thead>
                        <tbody id="roleTableData">
                            <div id="loadingSpinner" style="display: none; text-align: center;">
                                <i class="fas fa-spinner fa-spin fa-3x"></i>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="roleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Shift Form</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div id="loadingSpinner2">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                    </div>
                    <div id="formContainer">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection

@push('css')
<style>
    #loadingSpinner2{
        display: none;
        text-align: center;
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

        $('a[data-type="add"]').click(function(e) {
            e.preventDefault();
            $('#loadingSpinner2').show();
            $('#formContainer').load("{{ route('roles.create') }}", function() {
                $('#formContainer form').attr('id', 'addRoleForm');
                $('#loadingSpinner2').hide();
            });
        });
        $('#roleTableData').on('click', '.edit-role', function(e) {
            e.preventDefault();

            const roleId = $(this).data('id');

            $('#loadingSpinner2').show();
            $('#formContainer').load(`/roles/${roleId}/edit`, function() {
                $('#formContainer form').attr('id', 'editRoleForm');
                $('#loadingSpinner2').hide();
            });
        });

        const fetchRolesData = async () => {
            const url = "{{ route('roles.data') }}";
            $('#loadingSpinner2').show();

            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                const roleTableData = $('#roleTableData');
                roleTableData.empty();

                if (!response.ok) {
                    throw new Error("Network response was not ok" + response.statusText);
                    $('#loadingSpinner2').hide();
                }

                const data = await response.json();

                if (data.length === 0) {
                    roleTableData.append(
                        '<tr><td colspan="3" class="text-center">No record found</td></tr>');
                    $('#loadingSpinner2').hide();
                } else {
                    $.each(data, function(data, role) {
                        const row = `<tr>
                                    <td>${new Date(role.created_at).toLocaleDateString()}</td>
                                    <td>${role.name}</td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="#" class="edit-role edit-item"
                                                data-toggle="modal" data-target="#roleModal"
                                                data-id="${role.id}"><i class="fa fa-edit"></i> edit
                                            </a>
                                            <a href="#">
                                                <div class="delete-item delete-role"
                                                    data-role-id="${role.id}"
                                                    data-delete-route="/roles/${role.id}">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>`;
                        roleTableData.append(row);
                        $('#loadingSpinner2').hide();
                    });
                }
            } catch (error) {
                console.log(error);
                $('#loadingSpinner2').hide();
            }
        };

        fetchRolesData();

        $(document).on('click','.delete-role', function(e) {
            e.preventDefault();
            const roleId = $(this).data('role-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', roleId);
            const $clickedElement = $(this);

            if (confirm('Are you sure you want to delete this Role?')) {
                $('#loadingSpinner').show();
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(response) {
                    fetchRolesData();
                    setTimeout(() => {
                        $('#loadingSpinner').hide();
                        toastr.success(response.message);
                    }, 1000);
                    $clickedElement.closest('tr').fadeOut('slow', function() {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                }).catch(function(xhr) {
                    $('#loadingSpinner').hide();
                    console.error(xhr);
                    toastr.error(response.message);
                    button.prop('disabled', false);
                });
            }
        });

        $(document).on('submit','#addRoleForm, #editRoleForm', function(e) {
            e.preventDefault();

            const roleName = $('input[name="name"]').val().trim();

            if (roleName === '') {
                toastr.error('Role is required.');
                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);
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
                .then(function(response) {
                    toastr.success(response.message);
                    if ($(e.target).attr('id') === 'addRoleForm') {
                        $('#addRoleForm')[0].reset();
                    }
                    fetchRolesData();
                    button.prop('disabled', false);
                    $('#loadingSpinner').hide();
                    $('#roleModal').modal('hide');
                })
                .catch(function(err) {
                    console.error(err);
                    toastr.error('Failed to save Role.');
                    button.prop('disabled', false);
                    $('#loadingSpinner').hide();
                });
        });
    });
</script>
@endpush
