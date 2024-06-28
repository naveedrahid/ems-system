@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Designation
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('designation.create') }}" class="btn btn-primary">
                    Insert Designations
                </a>
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #fff;">
                    <tr>
                        <th width="10%">Desingnation</th>
                        <th width="16%">Desingnation</th>
                        <th width="30%">Department Name</th>
                        <th width="20%">Status</th>
                        <th width="20%">Manage</th>
                    </tr>
                </thead>
                <tbody style="background: #fff;">
                    @if (count($designations) > 0)
                        @foreach ($designations as $designation)
                            <tr>
                                <td>{{ $designation->created_at->toFormattedDateString() }}</td>
                                <td>{{ $designation->designation_name }}</td>
                                <td>{{ $designation->department->department_name }}</td>
                                <td>
                                    <button
                                        class="status-toggle btn btn-{{ $designation->status === 'active' ? 'info' : 'danger' }} btn-sm"
                                        data-id="{{ $designation->id }}" data-status="{{ $designation->status }}">
                                        <i class="fa fa-thumbs-{{ $designation->status === 'active' ? 'up' : 'down' }}"></i>
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('designation.edit', $designation) }}"
                                        class="btn btn-info btn-flat btn-sm"> <i class="fa fa-edit"></i></a>
                                    <button class="delete-designation btn btn-danger btn-flat btn-sm"
                                        data-designation-id="{{ $designation->id }}"
                                        data-delete-route="{{ route('designation.destroy', ':id') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            {{ $designations->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('.delete-designation').on('click', function(e) {
            e.preventDefault();
            const leaveType = $(this).data('designation-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', leaveType);
            const targetElement = $(this);


            if (confirm('Are you sure you want to delete this designation?')) {
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(result) {
                    toastr.success(result.message);
                    targetElement.closest('tr').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }).catch(function(err) {
                    console.log(err);
                    toastr.error('Faild to delete Designation');
                });
            }
        });

        $('.status-toggle').click(function() {
            const button = $(this);
            const id = button.data('id');
            const status = button.data('status');
            const newStatus = status === 'active' ? 'deactive' : 'active';
            const statusIcon = status === 'active' ? 'down' : 'up';
            const btnClass = status === 'active' ? 'danger' : 'info';
            const btnSts = $('.status-toggle');
            btnSts.prop('disabled', true);
            $.ajax({
                    url: '/update-status/' + id,
                    method: 'PUT',
                    data: {
                        status: newStatus
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                })
                .then((response) => {
                    button.removeClass('btn-' + (status === 'active' ? 'info' : 'danger'))
                        .addClass('btn-' + btnClass);
                    button.find('i').removeClass('fa-thumbs-' + (status === 'active' ?
                        'up' : 'down')).addClass('fa-thumbs-' + statusIcon);

                    toastr.success(response.message);
                    button.data('status', newStatus);
                    btnSts.prop('disabled', false);
                }).catch((err) => {
                    console.log(err);
                    toastr.error('Faild to status Designation');
                    btnSts.prop('disabled', false);
                });
        });
    });
</script>
@endpush
