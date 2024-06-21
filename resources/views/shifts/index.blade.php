@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Shifts
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('shifts.create') }}" class="btn btn-default btn-xm"><i class="fa fa-plus"></i></a>
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th width="10%">Date</th>
                        <th width="30%">Shift Name</th>
                        <th width="20%">Opening</th>
                        <th width="20%">Closing</th>
                        <th width="20%">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($shifts) > 0)
                        @foreach ($shifts as $shift)
                            <tr>
                                <td>{{ $shift->created_at->toFormattedDateString() }}</td>
                                <td>{{ $shift->name }}</td>
                                <td>{{ $shift->opening }}</td>
                                <td>{{ $shift->closing }}</td>
                                <td>
                                    <a href="{{ route('shifts.edit', $shift) }}" class="btn btn-info btn-flat btn-sm">
                                        <i class="fa fa-edit"></i></a>
                                    <button class="delete-shift btn btn-danger btn-flat btn-sm"
                                        data-holiday-id="{{ $shift->id }}"
                                        data-delete-route="{{ route('shifts.destroy', $shift->id) }}">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
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
        $('.delete-shift').on('click', function(e) {
            e.preventDefault();
            const bankId = $(this).data('shift-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', bankId);
            const $clickedElement = $(this);

            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this shift details!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: "DELETE",
                        url: deleteRoute,
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    }).then(function(response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });
                        $clickedElement.closest('tr').fadeOut('slow', function() {
                            $(this).css('backgroundColor', 'red').remove();
                        });
                    }).catch(function(xhr) {
                        console.error(xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete shift Details.',
                        });
                    });
                }
            });
        });
    });
</script>
@endpush
