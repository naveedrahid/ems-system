@extends('masterLayout.app')
@section('main')
@section('page-title')
    Daily Time Logs
@endsection
@section('page-content')
    <div class="box">
        <div class="box-body">
            @if ($timeLog->count() > 0)
                <table class="table table-bordered">
                    <thead style="background-color: #F8F8F8;">
                        <tr>
                            <th width="10%">Date</th>
                            <th width="10%">Attendance ID</th>
                            <th width="40%">Employee Name</th>
                            <th width="10%">Start Time</th>
                            <th width="10%">Stop Time</th>
                            <th width="10%">Duration</th>
                            <th width="10%">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $user = auth()->user();
                        @endphp
                        @foreach ($timeLog as $item)
                            <tr>
                                <td>{{ $item->created_at->toFormattedDateString() }}</td>
                                <td>{{ $item->attendance_id }}</td>
                                <td>
                                    @if (isAdmin($user))
                                        {{ $item->user->name }}
                                    @elseif ($user->id == $item->user_id)
                                        {{ $user->name }}
                                    @endif
                                </td>
                                <td>{{ $item->start_time }}</td>
                                <td>{{ $item->end_time }}</td>
                                <td>{{ $item->duration }}</td>
                                <td>
                                    <button class="delete-timelog btn btn-danger btn-flat btn-sm"
                                        data-time-id="{{ $item->id }}"
                                        data-delete-route="{{ route('time-logs.destroy', ':id') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $timeLog->links('pagination::bootstrap-4') }}
            @else
                <table class="table table-bordered">
                    <thead style="background-color: #F8F8F8;">
                        <tr>
                            <div class="text-center">Record Not Found.</div>
                        </tr>
                    </thead>
                </table>
            @endif
        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('.delete-timelog').on('click', function(e) {
            e.preventDefault();
            const timelogId = $(this).data('time-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', timelogId);
            const targetElement = $(this);


            if (confirm('Are you sure you want to delete this Time Log?')) {
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(response) {
                    toastr.success(response.message);
                    targetElement.closest('tr').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }).catch(function(err) {
                    console.log(err);
                    toastr.error('Faild to delete Time Log');
                });
            }
        });
    });
</script>
@endpush
