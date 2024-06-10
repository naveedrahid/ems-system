@extends('masterLayout.app')
@section('main')
@section('page-title')
    View All Employees
@endsection
@section('page-content')
    <div class="box">
        <!-- Box header and search form -->
        <div class="box-body">
            <table class="table table-bordered" id="employees-table">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Designation</th>
                        @if (Auth::user()->id < 3)
                            <th>Status</th>
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
<style>
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
            searchDelay: 1000,
            serverSide: false,
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
                    data: 'designation',
                    name: 'designation',
                    orderable: false,
                    searchable: true
                },
                @if (Auth::user()->id < 3)
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                @endif
            ],
            lengthMenu: [
                [5, 15, 50, 100],
                [5, 15, 50, 100]
            ],
            language: {
                processing: '<div class="dataTables_processings"></div>',
            }
        });
    });
</script>
@endpush
