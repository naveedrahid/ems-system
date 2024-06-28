@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Holidays
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            @php
                $user = auth()->user();
            @endphp
            @if (isAdmin($user))
                <h3 class="box-title">
                    <a href="{{ route('holidays.create') }}" class="btn btn-primary">
                        Add Holidays
                    </a>
                </h3>
            @endif
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #fff;">
                    <tr>
                        <th width="10%">Date</th>
                        <th width="15%">Holiday Name</th>
                        <th width="20%">Description</th>
                        <th width="20%">Date</th>
                        <th width="15%">Holiday Type</th>
                        <th width="20%">Manage</th>
                    </tr>
                </thead>
                <tbody style="background-color: #fff;">
                    @if (count($holidays) > 0)
                        @foreach ($holidays as $holiday)
                            <tr>
                                <td>{{ $holiday->created_at->toFormattedDateString() }}</td>
                                <td>{{ $holiday->name }}</td>
                                <td>
                                    {{ $holiday->description }}
                                </td>
                                <td>
                                    {{ $holiday->date }}
                                </td>
                                <td>
                                    {{ $holiday->holiday_type }}
                                </td>
                                @php
                                    $user = auth()->user();
                                @endphp
                                @if (isAdmin($user))
                                    <td>
                                        <button
                                            class="p-relative holiday-toggle btn btn-{{ $holiday->status === 'active' ? 'info' : 'danger' }} btn-sm"
                                            data-id="{{ $holiday->id }}" data-status="{{ $holiday->status }}">
                                            <i class="fa fa-thumbs-{{ $holiday->status === 'active' ? 'up' : 'down' }}"></i>
                                            <img src="{{ asset('admin/images/loader.gif') }}" class="imgLoader"
                                                width="20" height="20" alt="Loading...">
                                        </button>
                                        <a href="{{ route('holidays.edit', $holiday) }}"
                                            class="btn btn-info btn-flat btn-sm">
                                            <i class="fa fa-edit"></i></a>
                                        <button class="delete-holiday btn btn-danger btn-flat btn-sm"
                                            data-holiday-id="{{ $holiday->id }}"
                                            data-delete-route="{{ route('holidays.destroy', ':holiday') }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="8">Record Not Found!.</td>
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
        $('.delete-holiday').on('click', function(e) {
            e.preventDefault();

            const holidayId = $(this).data('holiday-id');
            const deleteRoute = $(this).data('delete-route').replace(':holiday', holidayId);
            const token = $('meta[name="csrf-token"]').attr('content');
            const clickedElement = $(this);

            if (confirm('Are you sure? You will not be able to recover this holiday!')) {
                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(response) {
                    console.log(response);
                    toastr.success(response.message);
                    clickedElement.closest('tr').fadeOut('slow', function() {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                }).catch(function(xhr) {
                    console.error(xhr);
                    toastr.error('Failed to delete Holiday');
                });
            }
        });
        $('.holiday-toggle').click(function() {
            const button = $(this);
            const id = button.data('id');
            const status = button.data('status');
            const newStatus = status === 'active' ? 'deactive' : 'active';
            const statusIcon = status === 'active' ? 'down' : 'up';
            const btnClass = status === 'active' ? 'danger' : 'info';

            $.ajax({
                url: '/holidays-status/' + id,
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
