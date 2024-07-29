@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Notice
@endsection
@section('page-content')
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">Manage Notices</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('notices.create') }}" class="btn btn-success text-bold">
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
                                <th width="15%">Title</th>
                                <th width="15%">Notice Type</th>
                                <th width="15%">Department Name</th>
                                <th width="20%">Description</th>
                                <th width="12%">Status</th>
                                <th width="18%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <div id="loadingSpinner" style="display: none; text-align: center;">
                                <i class="fas fa-spinner fa-spin fa-3x"></i>
                            </div>
                            @forelse ($notices as $notice)
                                <tr>
                                    <td>{{ $notice->name }}</td>
                                    <td>{{ $notice->notice_type }}</td>
                                    <td>
                                        @if ($notice->department_id == 0)
                                            All
                                        @else
                                            {{ $notice->department->department_name ?? '' }}
                                        @endif
                                    </td>
                                    <td>{!! $notice->description !!}</td>
                                    <td>
                                        <a href="#" class="status-toggle notice-toggle" data-id="{{ $notice->id }}"
                                            data-status="{{ $notice->status }}">
                                            <span
                                                class="badges {{ $notice->status === 'active' ? 'active-badge' : ($notice->status === 'pending' ? 'pending-badge' : 'deactive-badge') }}">
                                                {{ ucfirst($notice->status) }}
                                            </span>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="{{ route('notices.edit', $notice) }}">
                                                <div class="edit-item"><i class="fas fa-edit"></i> Edit</div>
                                            </a>
                                            <a href="#">
                                                <div class="delete-item delete-notice" data-notice-id="{{ $notice->id }}"
                                                    data-delete-route="{{ route('notices.destroy', ':id') }}">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="8">No Record Found!</td>
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

        $('.notice-toggle').on('click', function(e) {
            e.preventDefault();

            const $this = $(this);
            const noticeId = $this.data('id');
            const currentStatus = $this.data('status');
            const newStatus = currentStatus === 'active' ? 'deactive' : 'active';
            const token = $('meta[name="csrf-token"]').attr('content');
            $('#loadingSpinner').show();

            $.ajax({
                    url: `/notices/notices-status/${noticeId}`,
                    method: 'PUT',
                    data: {
                        _token: token,
                        id: noticeId,
                        status: newStatus
                    },
                })
                .then((response) => {
                    if (response.success) {
                        setTimeout(() => {
                            $('#loadingSpinner').hide();
                            toastr.success('Status updated successfully!');
                        }, 1000);

                        $this.data('status', newStatus);
                        $this.find('span').text(newStatus.charAt(0).toUpperCase() + newStatus.slice(
                            1));
                        $this.find('span').attr('class', 'badges ' + (newStatus === 'active' ?
                            'active-badge' : 'deactive-badge'));
                    }
                }).catch((err) => {
                    console.error(err);
                    toastr.error('Failed to update status. Please try again.');
                });
        });
    });
</script>
@endpush
