@extends('masterLayout.app')
@section('main')
@section('page-title')
    View Role
@endsection
@section('page-content')
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
                                <h4 class="text-bold">All Roles</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('roles.create') }}" class="btn btn-success text-bold">
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
                                <th width="20%">Date</th>
                                <th width="50%">Role Name</th>
                                <th width="20%">Manage</th>
                            </tr>
                        </thead>
                        @if (count($roles) > 0)
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->created_at->toFormattedDateString() }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="{{ route('roles.edit', $role) }}">
                                                <div class="edit-item"><i class="fas fa-edit"></i> Edit</div>
                                            </a>

                                            <a href="#">
                                                <div class="delete-item delete-role" data-role-id="{{ $role->id }}"
                                                    data-delete-route="{{ route('roles.destroy', ':id') }}">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
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

        $('.delete-role').on('click', function(e) {
            e.preventDefault();
            const roleId = $(this).data('role-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', roleId);
            const $clickedElement = $(this);

            if (confirm('Are you sure you want to delete this Role?')) {
                $('#loadingSpinner').show();
                const token = $('meta[name="csrf-token"]').attr('content');

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
                    $clickedElement.closest('tr').fadeOut('slow', function() {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                }).catch(function(xhr) {
                    $('#loadingSpinner').hide();
                    console.error(xhr);
                    toastr.error(response.message);
                    button.prop('disabled', false);
                });
            }
        });
    });
</script>
@endpush
