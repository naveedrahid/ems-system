@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Bank Details
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('bank-details.create') }}" class="btn btn-block btn-primary">
                    Insert Bank Detail
                </a>
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th width="15%">Employee Name</th>
                        <th width="10%">Bank Name</th>
                        <th width="10%">Account Title</th>
                        <th width="20%">Account Number</th>
                        <th width="5%">IBN</th>
                        <th width="10%">Branch Code</th>
                        <th width="10%">Branch Address</th>
                        <th width="10%">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($bankDetails) > 0)
                        @foreach ($bankDetails as $bankDetail)
                            <tr>
                                <td>{{ $employees[$bankDetail->user_id] ?? '' }}</td>
                                <td>{{ $bankDetail->bank_name }}</td>
                                <td>{{ $bankDetail->account_title }}</td>
                                <td>{{ $bankDetail->account_number }}</td>
                                <td>{{ $bankDetail->ibn }}</td>
                                <td>{{ $bankDetail->branch_code }}</td>
                                <td>{{ $bankDetail->branch_address }}</td>
                                <td>
                                    <button
                                        class="p-relative bank-toggle btn btn-{{ $bankDetail->status === 'active' ? 'info' : 'danger' }} btn-sm"
                                        data-id="{{ $bankDetail->id }}" data-status="{{ $bankDetail->status }}">
                                        <i class="fa fa-thumbs-{{ $bankDetail->status === 'active' ? 'up' : 'down' }}"></i>
                                        <img src="{{ asset('admin/images/loader.gif') }}" class="imgLoader" width="20"
                                            height="20" alt="Loading...">
                                    </button>
                                    <a href="{{ route('bank-details.edit', $bankDetail) }}"
                                        class="btn btn-info btn-flat btn-sm"> <i class="fa fa-edit"></i></a>
                                    <button class="delete-bank btn btn-danger btn-flat btn-sm"
                                        data-bank-id="{{ $bankDetail->id }}"
                                        data-delete-route="{{ route('bank-details.destroy', ':id') }}"><i
                                            class="fa-regular fa-trash-can"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No Bank Details found.</td>
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
        $('.delete-bank').on('click', function(e) {
            e.preventDefault();
            const bankId = $(this).data('bank-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', bankId);
            const $clickedElement = $(this);

            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this bank details!',
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
                            text: 'Failed to delete Bank Details.',
                        });
                    });
                }
            });
        });
        $('.bank-toggle').click(function() {
            const button = $(this);
            const id = button.data('id');
            const status = button.data('status');
            const newStatus = status === 'active' ? 'deactive' : 'active';
            const statusIcon = status === 'active' ? 'down' : 'up';
            const btnClass = status === 'active' ? 'danger' : 'info';
            const loader = button.find('img');

            $.ajax({
                url: 'bank-details/status/' + id,
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
                    button.data('status', newStatus);
                    loader.css('display', 'block');
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        },
                        willClose: () => {
                            loader.css('display', 'none');
                        }
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Status " + newStatus.charAt(0).toUpperCase() +
                            newStatus.slice(1) + " successfully"
                    });
                },
                error: function(xhr) {
                    console.error(xhr);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        },
                        willClose: () => {
                            loader.css('opacity', '0');
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
