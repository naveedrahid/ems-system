@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Department
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('department.create') }}" class="btn btn-primary">
                    Add Department
                </a>
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #fff;">
                    <tr>
                        <th width="10%">Date</th>
                        <th width="30%">Department Name</th>
                        <th width="20%">Status</th>
                        <th width="20%">Manage</th>
                    </tr>
                </thead>
                <tbody style="background-color: #fff;">
                    @if (count($departments) > 0)
                        @foreach ($departments as $department)
                            <tr>
                                <td>{{ $department->created_at->toFormattedDateString() }}</td>
                                <td>{{ $department->department_name }}</td>
                                <td>
                                    @if ($department->status !== 'active')
                                        <button class="btn btn-danger btn-sm"><i class="fa fa-thumbs-down"></i></button>
                                    @else
                                        <button class="btn btn-info btn-sm"><i class="fa fa-thumbs-up"></i></button>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('department.edit', $department) }}"
                                        class="btn btn-info btn-flat btn-sm"> <i class="fa fa-edit"></i></a>
                                    <button class="delete-department btn btn-danger btn-flat btn-sm"
                                        data-department-id="{{ $department->id }}"
                                        data-delete-route="{{ route('department.destroy', ':id') }}">
                                        <i class="fas fa-trash-alt"></i>
                                        </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No departments found.</td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        // Destroy Department
        $('.delete-department').on('click', function(e) {
            e.preventDefault();

            const departmentId = $(this).data('department-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', departmentId);
            const $clickedElement = $(this);

            if (confirm('Are you sure you want to delete this Department?'))  {
                    const token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: "DELETE",
                        url: deleteRoute,
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    }).then(function(response) {
                        toastr.success(response.message);
                        $clickedElement.closest('tr').fadeOut('slow', function () {
                            $(this).remove();
                        })
                    }).catch(function(xhr) {
                        console.error(xhr);
                        toastr.error('Faild to delete Department');
                    });
                }
        });
    });
</script>
@endpush
