@extends('masterLayout.app')
@section('main')
@section('page-title')
    View All Employees
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
                <div class="card-header" style="margin-bottom: 10px;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">All Employees</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('employees.create') }}" class="btn btn-success text-bold">
                                        Add <i class="fas fa-plus" style="font-size: 13px;"></i>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0" id="employees-table_wrapper">
                    <table class="table table-hover text-nowrap" id="employees-table">
                        <thead>
                            <tr>
                                <th width="10%">Image</th>
                                <th width="30%">Name</th>
                                <th width="20%">Email</th>
                                <th width="10%">Employee Type</th>
                                <th width="10%">Shift</th>
                                @if (isAdmin($user))
                                    <th width="15%">Manage</th>
                                @endif
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection

@push('css')
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"> --}}
<style>
    #employees-table_wrapper {
        .col-md-5 {
            display: none !important;
        }

        .col-md-7 {
            min-width: 100%;
            display: flex;
            justify-content: center;
            padding: 8px !important;
        }

        .paginate_button {
            padding: 6px 12px !important;
            border: 1px solid #C1C1C1;
            font-size: 14px !important;
            display: inline;
        }

        .next {
            margin-left: 10px;
            border-radius: 10px !important;

            &:hover {
                background: transparent !important;
                color: #F59E0B !important;
            }
        }

        .previous {
            margin-right: 10px;
            border-radius: 10px !important;

            &: hover {
                background: transparent !important;
                color: #F59E0B !important;
            }

            ;
            /* height: auto !important; */
        }

        .dataTables_paginate {
            display: flex;
            gap: 450px;

            span {
                padding: 0px 0px !important;
                border: 1px solid #C1C1C1;
                border-radius: 10px;

                a {
                    margin: 0 !important;
                    border-radius: 0;
                    border: 0 !important;
                    border-left: 1px solid #C1C1C1 !important;

                    &:hover {
                        background: transparent !important;
                        color: #F59E0B !important;
                    }

                    &:first-child {
                        border-left: 0 !important;
                    }
                }

                .current {
                    background: none !important;
                    box-shadow: none !important;
                    color: #F59E0B !important;
                }
            }
        }

        input {
            position: relative;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: white;
            background: url('../../../admin/images/searchicon.png');
            background-position: 10px 10px;
            background-repeat: no-repeat;
            padding-left: 40px;
        }

        input::before {
            position: absolute;
            content: "Search";
            font-size: 10px;
        }
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

    /* Custom table header style */
    #employees-table thead {
        background-color: #f8f8f8;
        color: #333;
    }

    /* Custom row hover effect */
    #employees-table tbody tr:hover {
        background-color: #e0f7fa;
    }

    /* Custom button style */
    .btn-flat {
        border-radius: 0;
        font-weight: bold;
    }

    .btn-info {
        background-color: #5bc0de;
        border-color: #46b8da;
    }

    .btn-danger {
        background-color: #d9534f;
        border-color: #d43f3a;
    }

    .dataTables_processings {
        background: #ffffff60 url('{{ asset('admin/images/loader.gif') }}') no-repeat center center !important;
        background-size: contain !important;
        color: transparent !important;
        height: 100% !important;
        width: 100% !important;
        font-size: 0px !important;
        position: absolute !important;
        top: 0 !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        margin: auto !important;
    }
</style>
@endpush
@push('js')
<script>
    $(function() {
        const table = $('#employees-table').DataTable({
            processing: true,
            responsive: true,
            searchDelay: 300,
            serverSide: true,
            ajax: '{{ route('employees.data') }}',
            columns: [{
                    data: 'employee_img',
                    name: 'employee_img',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'employee_type_id',
                    name: 'employee_type_id',
                    orderable: false,
                    searchable: true
                },
                {
                    data: 'shift_id',
                    name: 'shift_id',
                    orderable: false,
                    searchable: true
                },
                @if (isAdmin($user))
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                @endif
            ],
            lengthMenu: [
                [10, 15, 50, 100],
                [10, 15, 50, 100]
            ],
            language: {
                processing: '<div class="dataTables_processings"></div>',
            },
            dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });

        table.on('xhr', function() {
            table.ajax.json();
        });
    });


    $(document).ready(function() {

        $(document).on('click', '.status-toggle', function(e) {
            e.preventDefault();

            const $this = $(this);
            const employeeId = $this.data('id');
            const currentStatus = $this.data('status');
            const newStatus = currentStatus === 'active' ? 'deactive' : 'active';
            const token = $('meta[name="csrf-token"]').attr('content');
            $('#loadingSpinner').show();

            $.ajax({
                    url: `/employees-status/${employeeId}`,
                    method: 'PUT',
                    data: {
                        _token: token,
                        id: employeeId,
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
                    $('#loadingSpinner').hide();
                    console.error(err);
                    toastr.error('Failed to update status. Please try again.');
                });
        });

        $(document).on('click', '.delete-employee', function(e) {
            e.preventDefault();

            const deleteRoute = $(this).data('delete-route');
            const clickedElement = $(this);

            if (confirm('Are you sure you want to delete this Employee?')) {
                $('#loadingSpinner').show();
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    method: "DELETE",
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
                }).catch(function(xhr) {
                    $('#loadingSpinner').hide();
                    console.error(xhr);
                    toastr.error('Failed to delete Employee'); // Fix typo: "Faild" to "Failed"
                });
            }
        });

    });
</script>
@endpush
