@extends('masterLayout.app')
@section('main')
@section('page-title')
    View All Users
@endsection
@section('page-content')
    @php
        $roleId = auth()->user()->role_id;
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
                                <h4 class="text-bold">All Users</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('users.create') }}" class="btn btn-success text-bold">
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
                                <th width="20%">Name</th>
                                <th width="20%">Email</th>
                                <th width="10%">Role</th>
                                @if ($roleId == 0)
                                    <th width="10%">Status</th>
                                @endif
                                <th width="10%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>
                                        @if ($employee->role_id == 0)
                                            {{ 'Super Admin' }}
                                        @else
                                            {{ $employee->role->name }}
                                        @endif
                                    </td>
                                    @if ($roleId == 0)
                                        <td>
                                            <a href="#" class="user-toggle" data-id="{{ $employee->id }}"
                                                data-status="{{ $employee->status }}">
                                                <span
                                                    class="badges {{ $employee->status === 'active' ? 'active-badge' : ($employee->status === 'pending' ? 'pending-badge' : 'deactive-badge') }}">
                                                    {{ ucfirst($employee->status) }}
                                                </span>
                                            </a>
                                        </td>
                                    @endif
                                    <td>
                                        @if ($roleId == 0 || $employee->role_id == $roleId)
                                            <div class="manage-process">
                                                <a href="{{ route('users.edit', $employee) }}">
                                                    <div class="edit-item"><i class="fas fa-edit"></i> Edit</div>
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="8">No record found!</td>
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
        $('.user-toggle').on('click', function(e) {
            e.preventDefault();

            const $this = $(this);
            const noticeId = $this.data('id');
            const currentStatus = $this.data('status');
            const newStatus = currentStatus === 'active' ? 'deactive' : 'active';
            const token = $('meta[name="csrf-token"]').attr('content');
            $('#loadingSpinner').show();

            $.ajax({
                    url: `/user-status/${noticeId}`,
                    method: 'PUT',
                    data: {
                        _token: token,
                        id: noticeId,
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
                    $('#loadingSpinner').hide();
                    console.error(err);
                    toastr.error(response.message);
                });
        });
    });
</script>
@endpush
