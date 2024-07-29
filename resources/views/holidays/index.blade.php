@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Holidays
@endsection
@section('page-content')
    @php
        $user = auth()->user();
    @endphp
    <div id="loadingSpinner" style="display: none; text-align: center;">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">All Holidays</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            @if (isAdmin($user))
                                <div class="box-header pl-1">
                                    <h3 class="box-title">
                                        <a href="{{ route('holidays.create') }}" class="btn btn-success text-bold">
                                            Add <i class="fas fa-plus" style="font-size: 13px;"></i>
                                        </a>
                                    </h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="10%">Date</th>
                                <th width="15%">Holiday Name</th>
                                <th width="20%">Description</th>
                                <th width="20%">Date</th>
                                <th width="15%">Holiday Type</th>
                                <th width="20%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($holidays as $holiday)
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
                                            <div class="manage-process">
                                                <a style="width:70px;text-align:right;" href="#"
                                                    class="status-toggle holiday-toggle" data-id="{{ $holiday->id }}"
                                                    data-status="{{ $holiday->status }}">
                                                    <span
                                                        class="badges {{ $holiday->status === 'active' ? 'active-badge' : ($holiday->status === 'pending' ? 'pending-badge' : 'deactive-badge') }}">
                                                        {{ ucfirst($holiday->status) }}
                                                    </span>
                                                </a>
                                                <a href="{{ route('holidays.edit', $holiday) }}">
                                                    <div class="edit-item"><i class="fas fa-edit"></i> Edit</div>
                                                </a>
                                                <a href="#">
                                                    <div class="delete-item delete-holiday"
                                                        data-holiday-id="{{ $holiday->id }}"
                                                        data-delete-route="{{ route('holidays.destroy', ':id') }}">
                                                        <i class="far fa-trash-alt"></i> Delete
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="8">Record Not Found!.</td>
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

@push('css')
<style>
    div#loadingSpinner {
        position: fixed;
        left: 0;
        right: 0;
        margin: auto;
        top: 0;
        bottom: 0;
        z-index: 99;
        background: #00000036;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    div#loadingSpinner i {
        color: #007bff;
    }
</style>
@endpush
@push('js')
<script>
    $(document).ready(function() {
        $('.delete-holiday').on('click', function(e) {
            e.preventDefault();

            const holidayId = $(this).data('holiday-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', holidayId);
            const token = $('meta[name="csrf-token"]').attr('content');
            const clickedElement = $(this);

            if (confirm('Are you sure? You will not be able to recover this holiday!')) {
                $('#loadingSpinner').show();

                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(response) {
                    setTimeout(() => {
                        $('#loadingSpinner').hide();
                        toastr.success(response.message);
                    }, 1000);
                    clickedElement.closest('tr').fadeOut('slow', function() {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                }).catch(function(xhr) {
                    console.error(xhr);
                    $('#loadingSpinner').hide();
                    toastr.error('Failed to delete Holiday');
                });
            }
        });

        $('.holiday-toggle').on('click', function(e) {
            e.preventDefault();

            const $this = $(this);
            const holidayId = $this.data('id');
            const currentStatus = $this.data('status');
            const newStatus = currentStatus === 'active' ? 'deactive' : 'active';
            const token = $('meta[name="csrf-token"]').attr('content');
            $('#loadingSpinner').show();

            $.ajax({
                    url: `/holidays-status/${holidayId}`,
                    method: 'PUT',
                    data: {
                        _token: token,
                        id: holidayId,
                        status: newStatus
                    },
                })
                .then((response) => {
                    setTimeout(() => {
                        $('#loadingSpinner').hide();
                        toastr.success(response.message);
                    }, 1000);

                    $this.data('status', newStatus);
                    $this.find('span').text(newStatus.charAt(0).toUpperCase() + newStatus.slice(
                        1));
                    $this.find('span').attr('class', 'badges ' + (newStatus === 'active' ?
                        'active-badge' : 'deactive-badge'));
                }).catch((err) => {
                    console.error(err);
                    toastr.error(response.message);
                });
        });

    });
</script>
@endpush
