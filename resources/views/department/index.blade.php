@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Department
@endsection
@section('page-content')
    {{-- <button type="button" class="btn btn-default badges pass" data-toggle="modal" data-target="#selectedNotes" data-type="add">
        Add
    </button> --}}

    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">All Department</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('department.create') }}" class="btn btn-success text-bold"
                                        data-toggle="modal" data-target="#selectedNotes" data-type="add">
                                        Add <i class="fas fa-plus" style="font-size: 13px;"></i>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap"id="departmentTable">
                        <thead>
                            <tr>
                                <th width="15%">Date</th>
                                <th width="60%">Department Name</th>
                                <th width="25%">Manage</th>
                            </tr>
                        </thead>
                        <tbody id="departments-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="selectedNotes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Department Form</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div id="loadingSpinner" style="display: none; text-align: center;">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                    </div>
                    <div id="formContainer">
                        @include('department.form', [
                            'department' => $editDepartment ?? $newDepartment,
                            'formMethod' => $editDepartment ? $editFormMethod : $createFormMethod,
                            'route' => $editDepartment ? $editRoute : $createRoute,
                        ])
                    </div>
                </div>
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
    $('a[data-type="add"]').click(function() {
        $('#loadingSpinner').show();
        $('#formContainer').load("{{ route('department.create') }}", function() {
            $('#loadingSpinner').hide();
            $('#formContainer form').attr('id', 'departmentData');
        });
    });

    // Use event delegation to bind the click event
    $('#departmentTable').on('click', '.edit-department', function() {
        const departmentId = $(this).data('id');
        $('#loadingSpinner').show();

        $('#formContainer').load("/department/" + departmentId + "/edit", function() {
            $('#loadingSpinner').hide();
            $('#formContainer form').attr('id', 'departmentDataUpdate');
        });
    });


    const fetchDepartmentData = async () => {
        const url = "{{ route('department.data') }}";
        try {
            const response = await fetch(url);
            const data = await response.json();
            const tableData = $('#departments-body');
            tableData.empty();

            if (data.length === 0) {
                tableData.append(
                    '<tr><td colspan="3" class="text-center">No record found</td></tr>');
            } else {
                $.each(data, function(_, department) {
                    const row = `
                    <tr>
                        <td>${new Date(department.created_at).toLocaleDateString()}</td>
                        <td>${department.department_name}</td>
                        <td>
                            <div class="manage-process">
                                <a href="#" class="edit-department edit-item"
                                    data-toggle="modal" data-target="#selectedNotes"
                                    data-id="${department.id}"><i class="fa fa-edit"></i> edit
                                <a href="#">
                                    <div class="delete-item delete-department"
                                        data-department-id="${department.id}"
                                        data-delete-route="/department/${department.id}">
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
        }
    };
    fetchDepartmentData();

    $(document).on('click', '.delete-department', function(e) {
        e.preventDefault();
        const departmentId = $(this).data('department-id');
        const deleteRoute = $(this).data('delete-route');
        const clickedElement = $(this);

        if (confirm('Are you sure you want to delete this Department?')) {
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
                });
                fetchDepartmentData();
            }).catch(function(xhr) {
                console.error(xhr);
                toastr.error('Faild to delete Department');
            });
        }
    });

    $('#formContainer').on('submit', '#departmentData, #departmentDataUpdate', function(e) {
        e.preventDefault();

        const dp_name = $('input[name="department_name"]').val().trim();
        const dp_status = $('select[name="status"]').val().trim();

        if (dp_name === '' || dp_status === '') {
            if (dp_name === '') {
                toastr.error('Department name is required.');
            }
            if (dp_status === '') {
                toastr.error('Department status is required.');
            }
            return;
        }

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
                console.log(response);
                toastr.success(response.message);
                button.prop('disabled', false);
                if ($(e.target).attr('id') === 'departmentData') {
                    $('#departmentData')[0].reset();
                }
                fetchDepartmentData();
                $('#selectedNotes').modal('hide');
            })
            .catch(function(err) {
                console.error(err);
                toastr.error('Failed to save Department.');
                button.prop('disabled', false);
            });
    });
});
</script>
@endpush
