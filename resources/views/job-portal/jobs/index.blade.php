@extends('masterLayout.app')
@section('main')
@section('page-title')
    All Jobs
@endsection
@section('page-content')
    <div class="box">
        <div class="box-body">
            <div class="row">
                @if ($jobs)
                    @foreach ($jobs as $job)
                        <div class="col-md-6">
                            <div class="jobWrapper">
                                @if ($job->job_img)
                                    <img src="{{ asset($job->job_img) }}" alt="Award Image" class="img-fluid">
                                @endif
                                <h4>{{ $job->title }}</h4>
                                <p> <strong>Department:</strong> {{ $departments[$job->department_id] ?? 'N/A' }}</p>
                                <p> <strong>Designatio:</strong> {{ $designations[$job->designation_id] ?? 'N/A' }}</p>
                                <p> <strong>Shift:</strong> {{ $shifts[$job->shift_id] ?? 'N/A' }}</p>
                                @php
                                    $employmentTypes = explode(', ', $job->employment_type);
                                @endphp
                                <p>

                                    <strong>JobType:</strong>{{ ucwords(str_replace('_', ' ', implode(', ', $employmentTypes))) }}
                                </p>
                                <p>
                                    <strong>Location:</strong> {{ $job->location }}
                                </p>
                                <p>
                                    <strong>Salary:</strong>{{ $job->salary_range }}
                                </p>
                                <p>
                                    <strong>Job Last Date</strong>{{ $job->closing_date }}
                                </p>
                                {{-- @php
                                        $jobImg = str_replace('public/', 'storage/', $job->job_images) ?? '';
                                    @endphp --}}
                                <p>{!! $job->description !!}</p>

                            </div>
                            <div class="">
                                <button class="delete-job btn btn-danger btn-flat btn-sm" data-job-id="{{ $job->id }}"
                                    data-delete-route="{{ route('jobs.destroy', ':id') }}">
                                    Delete <i class="fas fa-trash-alt"></i>
                                </button>

                                <a href="{{ route('jobs.edit', $job->id) }}" class="status-toggle btn btn-primary btn-sm">
                                    edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
@endsection
@push('css')
<style>
    .jobWrapper {
        padding: 30px;
        border-radius: 10px;
        box-shadow: #0000006e 0px 0px 20px 0px;
        background: #fff;
        margin-bottom: 30px;
    }

    .jobWrapper p {
        color: #000;
        font-size: 18px !important;
    }

    .jobWrapper h4 {
        text-decoration: underline;
        margin: 20px 0px;
        font-weight: 700;
    }

    .col-md-6 {}

    .jobWrapper~div {
        display: flex;
    }

    .jobWrapper~div form {
        margin-right: 20px;
    }
</style>
@endpush
@push('js')
<script>
    $(document).ready(function() {
        $('.delete-job').on('click', function(e) {
            e.preventDefault();
            const jobId = $(this).data('job-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', jobId);
            const targetElement = $(this);


            if (confirm('Are you sure you want to delete this Job?')) {
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    method: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(result) {
                    toastr.success(result.message);
                    targetElement.closest('.col-md-6').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }).catch(function(err) {
                    console.log(err);
                    toastr.error('Faild to delete Job');
                });
            }
        });

        // $('.status-toggle').click(function() {
        //     const button = $(this);
        //     const id = button.data('id');
        //     const status = button.data('status');
        //     const newStatus = status === 'active' ? 'deactive' : 'active';
        //     const statusIcon = status === 'active' ? 'down' : 'up';
        //     const btnClass = status === 'active' ? 'danger' : 'info';
        //     const btnSts = $('.status-toggle');
        //     btnSts.prop('disabled', true);
        //     $.ajax({
        //             url: '/update-status/' + id,
        //             method: 'PUT',
        //             data: {
        //                 status: newStatus
        //             },
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //         })
        //         .then((response) => {
        //             button.removeClass('btn-' + (status === 'active' ? 'info' : 'danger'))
        //                 .addClass('btn-' + btnClass);
        //             button.find('i').removeClass('fa-thumbs-' + (status === 'active' ?
        //                 'up' : 'down')).addClass('fa-thumbs-' + statusIcon);

        //             toastr.success(response.message);
        //             button.data('status', newStatus);
        //             btnSts.prop('disabled', false);
        //         }).catch((err) => {
        //             console.log(err);
        //             toastr.error('Faild to status Designation');
        //             btnSts.prop('disabled', false);
        //         });
        // });
    });
</script>
@endpush
