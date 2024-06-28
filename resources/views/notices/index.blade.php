@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Notice
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('notices.create') }}" class="btn btn-primary">
                    Insert Notice
                </a>
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #fff;">
                    <tr>
                        <th width="20%">Title</th>
                        <th width="15%">Notice Type</th>
                        <th width="15%">Department Name</th>
                        <th width="40%">Description</th>
                        <th width="10%">Manage</th>
                    </tr>
                </thead>
                <tbody style="background-color: #fff;">
                    @if (count($notices) > 0)
                        @foreach ($notices as $notice)
                            <tr>
                                <td>{{ $notice->name }}</td>
                                <td>{{ $notice->notice_type }}</td>
                                <td>
                                    @if ($notice->department_id == 0)
                                        All
                                    @else
                                        {{ $notice->department->department_name }}
                                    @endif
                                </td>
                                <td>{!! $notice->description !!}</td>
                                <td>
                                    <button
                                        class="notice-toggle btn btn-{{ $notice->status === 'active' ? 'info' : 'danger' }} btn-sm"
                                        data-id="{{ $notice->id }}" data-status="{{ $notice->status }}">
                                        <i class="fa fa-thumbs-{{ $notice->status === 'active' ? 'up' : 'down' }}"></i>
                                    </button>
                                    <a href="{{ route('notices.edit', $notice) }}" class="btn btn-info btn-flat btn-sm"> <i
                                            class="fa fa-edit"></i></a>
                                    <button class="delete-notice btn btn-danger btn-flat btn-sm"
                                        data-notice-id="{{ $notice->id }}"
                                        data-delete-route="{{ route('notices.destroy', ':id') }}">
                                        <i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="8">No Record Found!</td>
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
        $('.delete-notice').on('click', function(e) {
            e.preventDefault();

            const noticeId = $(this).data('notice-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', noticeId);
            const $clickedElement = $(this);

            if (confirm('Are you sure you want to delete this Award?')) {
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(response) {
                    console.log(response);
                    toastr.success(response.message);
                    $clickedElement.closest('tr').fadeOut('slow', function() {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                }).catch(function(xhr) {
                    console.error(xhr);
                    toastr.error('Failed to Delete Notice');
                });
            }

        });
        $('.notice-toggle').click(function() {
            const button = $(this);
            const id = button.data('id');
            const status = button.data('status');
            const newStatus = status === 'active' ? 'deactive' : 'active';
            const statusIcon = status === 'active' ? 'down' : 'up';
            const btnClass = status === 'active' ? 'danger' : 'info';

            $.ajax({
                url: '/notices/notices-status/' + id,
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
                        title: "Failed to Notice update status"
                    });
                }
            });
        });
    });
</script>
@endpush
