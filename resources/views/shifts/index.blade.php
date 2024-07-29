@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Shifts
@endsection
@section('page-content')
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="card-title text-bold">All Shifts</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('shifts.create') }}" class="btn btn-success text-bold">
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
                                <th>Date</th>
                                <th>Shift Name</th>
                                <th>Opening</th>
                                <th>Closing</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($shifts as $shift)
                                <tr>
                                    <td>{{ $shift->created_at->toFormattedDateString() }}</td>
                                    <td>{{ $shift->name }}</td>
                                    <td>{{ $shift->opening }}</td>
                                    <td>{{ $shift->closing }}</td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="{{ route('shifts.edit', $shift) }}" >
                                                <div class="edit-item"><i class="fas fa-edit"></i> Edit</div>
                                            </a>
                                            <div class="delete-shift delete-item" 
                                                style="cursor: pointer;"
                                                data-shift-id="{{ $shift->id }}"
                                                data-delete-route="{{ route('shifts.destroy', $shift->id) }}">
                                                <i class="fas fa-trash-alt"></i>
                                        </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="9">No Shift Found!</td>
                                </tr>
                            @endforelse
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
        $('.delete-shift').on('click', function(e) {
            e.preventDefault();
            const shiftId = $(this).data('shift-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', shiftId);
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
