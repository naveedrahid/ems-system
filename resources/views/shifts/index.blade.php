@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Shifts
@endsection
@section('page-content')
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="card-title text-bold">All Shifts</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('shifts.create') }}" class="btn btn-success text-bold"
                                        data-toggle="modal" data-target="#shiftModal" data-type="add">
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
                                <th>Date</th>
                                <th>Shift Name</th>
                                <th>Opening</th>
                                <th>Closing</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody id="shiftsData">
                            <div id="loadingSpinner" style="display: none; text-align: center;">
                                <i class="fas fa-spinner fa-spin fa-3x"></i>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="shiftModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Shift Form</h5>
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
    $('a[data-type="add"]').click(function() {
        $('#loadingSpinner2').show();
        $('#formContainer').load("{{ route('shifts.create') }}", function() {
            // $('#loadingSpinner2').hide();
            $('#formContainer form').attr('id', 'addShift');
        });
    });

    $('#shiftsData').on('click', '.edit-shift', function() {
        const shiftId = $(this).data('id');
        $('#loadingSpinner2').show();

        $('#formContainer').load(`/shifts/${shiftId}/edit`, function(response, status,
            xhr) {
            if (status === "error") {
                console.error('Error loading form:', xhr.statusText);
                alert('An error occurred while loading the form. Please try again.');
                $('#loadingSpinner').hide();
                return;
            }

            $('#loadingSpinner2').hide();
            $('#formContainer form').attr('id', 'updateShift');
        });
    });

    $(document).ready(function() {

        const fetchShiftsData = async () => {
            const url = "{{ route('shifts.data') }}";
            $('#loadingSpinner').show();

            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errorMessage = await response.json();
                    throw new Error(errorMessage.message || 'Error fetching data');
                }

                const data = await response.json();
                const shiftsData = $('#shiftsData');
                shiftsData.empty();

                if (data.length === 0) {
                    shiftsData.append(
                        '<tr><td colspan="5" class="text-center">No record found</td></tr>'
                    );
                    $('#loadingSpinner').hide();
                } else {
                    $('#loadingSpinner').hide();
                    $.each(data, function(_, shift) {
                        const row = `<tr>
                            <td>${new Date(shift.created_at).toLocaleDateString()}</td>
                    <td>${shift.name}</td>
                    <td>${shift.opening}</td>
                    <td>${shift.closing}</td>
                    <td>
                        <div class="manage-process">
                            <a href="#" class="edit-shift edit-item" data-toggle="modal" data-target="#shiftModal" data-id="${shift.id}">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="#">
                                <div class="delete-item delete-shift" data-shift-id="${shift.id}" data-delete-route="/shifts/${shift.id}">
                                    <i class="far fa-trash-alt"></i> Delete
                                    </div>
                            </a>
                        </div>
                    </td>
                </tr>`;
                        shiftsData.append(row);
                    });
                }
            } catch (error) {
                $('#loadingSpinner').hide();
                console.error('Error:', error);
                const shiftsData = $('#shiftsData');
                shiftsData.empty();
                shiftsData.append(
                    '<tr><td colspan="5" class="text-center">Error fetching data</td></tr>');
            }
        }
        fetchShiftsData();

        $(document).on('click', '.delete-shift', function(e) {
            e.preventDefault();
            const shiftId = $(this).data('shift-id');
            const deleteRoute = $(this).data('delete-route');
            const clickedElement = $(this);

            if (confirm('Are you sure you want to delete this Shift?')) {
                $('#loadingSpinner').show();
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(response) {
                    fetchShiftsData();
                    setTimeout(() => {
                        toastr.success(response.message);
                        $('#loadingSpinner').hide();
                    }, 1000);
                    clickedElement.closest('tr').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }).catch(function(xhr) {
                    toastr.error('Shift delete failed.');
                    $('#loadingSpinner').hide();
                    console.error(xhr);
                });
            }
        });


        $(document).on('submit', '#addShift, #updateShift', function(e) {
            e.preventDefault();

            const name = $('input[name="name"]').val().trim();
            const opening = $('input[name="opening"]').val().trim();
            const closing = $('input[name="closing"]').val().trim();

            $('.text-danger').text('');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);

            let hasError = false;

            if (name === '') {
                $('#nameError').text('Name is required')
                hasError = true;
            }
            if (opening === '') {
                $('#nameError').text('Name is required')
                hasError = true;
            }
            if (closing === '') {
                $('#nameError').text('Name is required')
                hasError = true;
            }

            $('#loadingSpinner').show();
            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .done(function(response) {
                    fetchShiftsData();
                    toastr.success(response.message);
                    if ($(e.target).attr('id') === 'addShift') {
                        $('#addShift')[0].reset();
                    }
                    $('#shiftModal').modal('hide');
                    $('#loadingSpinner').hide();
                    button.prop('disabled', false)
                })
                .fail(function(xhr) {
                    $('#loadingSpinner').hide();
                    console.error(xhr);
                })
                .always(function() {
                    button.prop('disabled', false);
                });
        });

    });
</script>
@endpush
