@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Job Offer
@endsection
@section('page-content')
    <div class="row position-relative">
        <div id="loadingSpinner" style="display: none; text-align: center;">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
        </div>
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">Manage Designation</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('job-offers.create') }}" class="btn btn-success text-bold">
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
                                <th width="15%">Job Title</th>
                                <th width="15%">Candidate Name</th>
                                <th width="10%">Salary</th>
                                <th width="20%">Offer</th>
                                <th width="10%">Send Email</th>
                                <th width="20%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($job_offers as $job_offer)
                                <tr>
                                    <td>{{ $job_offer->created_at->toFormattedDateString() }}</td>
                                    <td>{{ $job_offer->job->title }}</td>
                                    <td>{{ $job_offer->candidate->first_name . ' ' . $job_offer->candidate->last_name }}
                                    </td>
                                    <td>{{ $job_offer->candidate_salary }}</td>
                                    <td>{!! $job_offer->candidate_offer !!}</td>
                                    <td>
                                        <button id="sendJobOfferEmail" class="status-toggle" data-id="{{ $job_offer->id }}">
                                            <i class="far fa-paper-plane"></i> Send Email
                                        </button>
                                    </td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="{{ route('job-offers.edit', $job_offer) }}">
                                                <div class="edit-item"><i class="fas fa-edit"></i> Edit</div>
                                            </a>
                                            <a href="#">
                                                <div class="delete-item" data-offer-id="{{ $job_offer->id }}"
                                                    data-delete-route="{{ route('job-offers.destroy', ':id') }}">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No Job Offer Found!</td>
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
        #sendJobOfferEmail{
            background:transparent;
            border:none;
            padding:0;
        }
    </style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        $('#sendJobOfferEmail').click(function(e) {
            e.preventDefault();

            const jobId = $(this).data('id');
            $(this).prop('disabled', true);
            const token = $('meta[name="csrf-token"]').attr('content');           
            $('#loadingSpinner').show();
            $.ajax({
                method: 'POST',
                url: '/portal/job-offers/send-email/' + jobId,
                data: {
                    _token: token
                }
                // success: function(response) {
                //     alert(response.message);
                // },
                // error: function(xhr) {
                //     alert('An error occurred while sending the email.');
                // }
            })
            .then((response) => {
                $('#loadingSpinner').hide();
                $(this).prop('disabled', false);
                toastr.success(response.message);
            }).catch((err) => {
                $(this).prop('disabled', false);
                $('#loadingSpinner').hide();
                console.error(err);
                toastr.error('Failed Offer Email.');
            });
        });
    });
</script>
@endpush
