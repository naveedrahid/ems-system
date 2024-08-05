@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Holidays
@endsection
@section('page-content')
    @php
        $user = auth()->user();
        $isAdmin = isAdmin($user);
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
                                <h4 class="text-bold">All Holidays</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            @if (isAdmin($user))
                                <div class="box-header pl-1">
                                    <h3 class="box-title">
                                        <a href="{{ route('holidays.create') }}" class="btn btn-success text-bold"
                                            data-toggle="modal" data-target="#holidayModal" data-type="add">
                                            Add <i class="fas fa-plus" style="font-size: 13px;"></i>
                                        </a>
                                    </h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="10%">Date</th>
                                <th width="15%">Holiday Name</th>
                                <th width="20%">Description</th>
                                <th width="20%">Date</th>
                                <th width="15%">Holiday Type</th>
                                <th width="20%">Manage</th>
                            </tr>
                        </thead>
                        <tbody id="holidaysTable">
                        </tbody>
                    </table>
                    <ul id="pagination"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Holiday Form</h5>
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
    <div id="isAdminContainer" data-is-admin="{{ $isAdmin }}"></div>
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

        const isAdmin = $('#isAdminContainer').data('is-admin');

        loadForm('a[data-type="add"]', '#formContainer', '{{ route('holidays.create') }}', 'addHolidays');
        loadForm('.edit-holidays', '#formContainer', '/holidays/{id}/edit', 'updateHolidays');

        $('#formContainer').on('form.loaded', function() {
            $('#daterange').daterangepicker({
                opens: 'right',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
        });

        function fetchHolidaysData() {
            const endPoint = "{{ route('holidays.data') }}";
            const targetTable = $('#holidaysTable');
            const targetPagination = $('#pagination');

            const htmlRowCallback = (holidays) => `
                                <tr>
                                    <td>${new Date(holidays.created_at).toLocaleDateString()}</td>
                                    <td>${holidays.name}</td>
                                    <td>${holidays.description}</td>
                                    <td>${holidays.date}</td>
                                    <td>${holidays.holiday_type}</td>
                                    ${isAdmin ? `
                                    <td>
                                        <div class="manage-process">
                                            <a style="width:70px;text-align:right;" href="#" class="holiday-toggle" data-id="${holidays.id}" data-status="${holidays.status}">
                                                <span class="badges ${holidays.status === 'active' ? 'active-badge' : holidays.status === 'deactive' ? 'deactive-badge' : 'active-badge'}">
                                                    ${holidays.status}
                                                </span>
                                            </a>
                                            <a href="#" class="edit-holidays edit-item" data-toggle="modal" data-target="#holidayModal" data-id="${holidays.id}"><i class="fa fa-edit"></i> Edit</a>
                                            <a href="#">
                                                <div class="delete-item delete-holiday" data-holiday-id="${holidays.id}" data-delete-route="/holidays/${holidays.id}">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </div>
                                            </a>
                                        </div>
                                    </td>` : ''}
                                </tr>`;

            fetchDataGlobal(1, endPoint, targetTable, targetPagination, htmlRowCallback);
            initializePaginationClickHandler(endPoint, targetTable, targetPagination, htmlRowCallback);
        }
        fetchHolidaysData();

        $(document).on('click', '.delete-holiday', function(e) {
            e.preventDefault();

            const holidayId = $(this).data('holiday-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', holidayId);
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
                    fetchHolidaysData();
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

        $(document).on('click', '.holiday-toggle', function(e) {
            e.preventDefault();

            const $this = $(this);
            const holidayId = $this.data('id');
            const currentStatus = $this.data('status');
            const newStatus = currentStatus === 'active' ? 'deactive' : 'active';
            const token = $('meta[name="csrf-token"]').attr('content');
            $('#loadingSpinner').show();

            $.ajax({
                    url: `/holidays-status/${holidayId}`,
                    method: 'PUT',
                    data: {
                        _token: token,
                        id: holidayId,
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
                });
        });

        $(document).on('submit', '#addHolidays, #updateHolidays', function(e) {
            e.preventDefault();
        
            const name = $('input[name="name"]').val().trim();
            const daterange = $('input[name="date"]').val().trim();
            const holidayType = $('select[name="holiday_type"]').val().trim();
        
            if (name === '' || daterange === '' || holidayType === '') {
                if (name === '') {
                    toastr.error('Name is required.');
                }
                if (daterange === '') {
                    toastr.error('Date is required.');
                }
                if (holidayType === '') {
                    toastr.error('Holiday Type is required.');
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
                    fetchHolidaysData();
                    button.prop('disabled', false);
                    toastr.success(response.message);
                    if ($(e.target).attr('id') === 'addHolidays') {
                        $('#addHolidays')[0].reset();
                    }
                    $('#holidayModal').modal('hide');
                })
                .catch(function(xhr) {
                    console.error(xhr);
                    toastr.error('Failed to create Holiday.');
                });
        });
    });
</script>
@endpush