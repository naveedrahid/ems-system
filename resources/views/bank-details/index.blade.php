@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Bank Details
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
                                <h4 class="text-bold">All Bank Details</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('bank-details.create') }}" class="btn btn-success text-bold">
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
                                <th width="10%">Date</th>
                                <th width="15%">Employee Name</th>
                                <th width="10%">Bank Name</th>
                                <th width="10%">Account Title</th>
                                <th width="15%">Account Number</th>
                                <th width="5%">IBN</th>
                                <th width="10%">Branch Code</th>
                                <th width="10%">Branch Address</th>
                                <th width="15%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bankDetails as $bankDetail)
                                <tr>
                                    <td>{{ $bankDetail->created_at->toFormattedDateString() }}</td>
                                    <td>{{ $employees[$bankDetail->user_id] ?? '' }}</td>
                                    <td>{{ $bankDetail->bank_name }}</td>
                                    <td>{{ $bankDetail->account_title }}</td>
                                    <td>{{ $bankDetail->account_number }}</td>
                                    <td>{{ $bankDetail->ibn }}</td>
                                    <td>{{ $bankDetail->branch_code }}</td>
                                    <td>{{ $bankDetail->branch_address }}</td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="#" class="bank-toggle" data-id="{{ $bankDetail->id }}"
                                                data-status="{{ $bankDetail->status }}">
                                                <span
                                                    class="badges {{ $bankDetail->status === 'active' ? 'active-badge' : ($bankDetail->status === 'pending' ? 'pending-badge' : 'deactive-badge') }}">
                                                    {{ ucfirst($bankDetail->status) }}
                                                </span>
                                            </a>
                                            <a href="{{ route('bank-details.edit', $bankDetail) }}" class="edit-item"> <i
                                                    class="fa fa-edit"></i> edit</a>
                                            <a href="#">
                                                <div class="delete-item delete-bank" data-bank-id="{{ $bankDetail->id }}"
                                                    data-delete-route="{{ route('bank-details.destroy', ':id') }}">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="9">No record found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
            
            $('.delete-bank').on('click', function(e) {
                e.preventDefault();
                const bankId = $(this).data('bank-id');
                const deleteRoute = $(this).data('delete-route').replace(':id', bankId);
                const token = $('meta[name="csrf-token"]').attr('content');
                const $clickedElement = $(this);
                $('#loadingSpinner').show();
                
                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(response) {
                    $('#loadingSpinner').hide();
                    toastr.success(response.message);
                    $clickedElement.closest('tr').fadeOut('slow', function() {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                }).catch(function(xhr) {
                    console.error(xhr);
                    $('#loadingSpinner').hide();
                    toastr.error(response.message);
                });
            });

            $('.bank-toggle').click(function() {
                const $this = $(this);
                const bankId = $this.data('id');
                const currentStatus = $this.data('status');
                const newStatus = currentStatus === 'active' ? 'deactive' : 'active';
                const token = $('meta[name="csrf-token"]').attr('content');
                $('#loadingSpinner').show();

                $.ajax({
                        url: `/bank-details/status/${bankId}`,
                        method: 'PUT',
                        data: {
                            _token: token,
                            id: bankId,
                            status: newStatus
                        },
                    })
                    .then((response) => {
                        $('#loadingSpinner').hide();
                        toastr.success(response.message);

                        $this.data('status', newStatus);
                        $this.find('span').text(newStatus.charAt(0).toUpperCase() + newStatus.slice(
                            1));
                        $this.find('span').attr('class', 'badges ' + (newStatus === 'active' ?
                            'active-badge' : 'deactive-badge'));

                    })
                    .catch((err) => {
                        console.log(err);
                        $('#loadingSpinner').hide();
                        toastr.error('Failed to update status. Please try again.');
                    });
            });

        });
    </script>
@endpush
