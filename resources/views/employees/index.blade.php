@extends('masterLayout.app')
@section('main')
@section('page-title')
    View All Employees
@endsection
@section('page-content')
    <div class="box">
        @php
            $user = auth()->user();
        @endphp
        <!-- Box header and search form -->
        <div class="box-body">
            <table class="table table-bordered" id="employees-table">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Employee Type</th>
                        <th>Shift</th>
                        <th>Designation</th>
                        @if (isAdmin($user))
                            <th>Manage</th>
                        @endif
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@endsection
@push('css')
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"> --}}
<style>
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
        $('#employees-table').DataTable({
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
                    data: 'department',
                    name: 'department',
                    orderable: false,
                    searchable: true
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
                {
                    data: 'designation',
                    name: 'designation',
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
    });

    $(document).ready(function() {
        $(document).on('click', '.employee-toggle', function() {
            const button = $(this);
            const id = button.data('id');
            const status = button.data('status');
            const newStatus = status === 'active' ? 'deactive' : 'active';
            const statusIcon = status === 'active' ? 'down' : 'up';
            const btnClass = status === 'active' ? 'danger' : 'info';

            $.ajax({
                url: '/employees-status/' + id,
                method: 'PUT',
                data: {
                    status: newStatus
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    button.removeClass('btn-' + (status === 'active' ? 'info' : 'danger'))
                        .addClass('btn-' + btnClass);
                    button.find('i').removeClass('fa-thumbs-' + (status === 'active' ?
                        'up' : 'down')).addClass('fa-thumbs-' + statusIcon);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Status " + newStatus.charAt(0).toUpperCase() +
                            newStatus.slice(1) + " successfully"
                    });
                    button.data('status', newStatus);
                },
                error: function(xhr) {
                    console.error(xhr);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "error",
                        title: "Failed to update status"
                    });
                }
            });
        });
    });
</script>
@endpush
