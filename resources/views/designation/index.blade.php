@extends('masterLayout.app')
@section('main')
    {{-- @section('page-title')
    Manage Designation
@endsection --}}
@section('page-content')
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">Manage Designation</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('designation.create') }}" class="btn btn-success text-bold">
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
                                <th width="10%">Date</th>
                                <th width="16%">Designation</th>
                                <th width="30%">Department Name</th>
                                <th width="20%">Status</th>
                                <th width="20%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($designations) > 0)
                                @foreach ($designations as $designation)
                                    <tr>
                                        <td>{{ $designation->created_at->toFormattedDateString() }}</td>
                                        <td>{{ $designation->designation_name }}</td>
                                        <td>{{ $designation->department->department_name ?? '' }}</td>
                                        <td>
                                            <a href="#" class="status-toggle" data-id="{{ $designation->id }}"
                                                data-status="{{ $designation->status }}">
                                                <span
                                                    class="badges {{ $designation->status === 'active' ? 'active-badge' : ($designation->status === 'pending' ? 'pending-badge' : 'deactive-badge') }}">
                                                    {{ ucfirst($designation->status) }}
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="manage-process">
                                                <a href="{{ route('designation.edit', $designation) }}">
                                                    <div class="edit-item"><i class="fas fa-edit"></i> Edit</div>
                                                </a>
                                                <a href="#">
                                                    <div class="delete-item" data-designation-id="{{ $designation->id }}"
                                                        data-delete-route="{{ route('designation.destroy', ':id') }}">
                                                        <i class="far fa-trash-alt"></i> Delete
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('.delete-item').on('click', function(e) {
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
        $('.status-toggle').click(function(event) {
            event.preventDefault();
            const button = $(this);
            const id = button.data('id');
            const status = button.data('status');
            const newStatus = status === 'active' ? 'deactive' : 'active';
            const btnSpan = button.find('span');
            btnSpan.removeClass(status + '-badge')
                .addClass(newStatus + '-badge');
            button.prop('disabled', true);
            $.ajax({
                url: '/update-status/' + id,
                method: 'PUT',
                data: {
                    status: newStatus
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    button.find('span').text(newStatus.charAt(0).toUpperCase() + newStatus
                        .slice(1));
                    button.data('status', newStatus);
                    button.prop('disabled', false);
                },
                error: function(err) {
                    console.log(err);
                    alert('Failed to update status.');
                    button.prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
