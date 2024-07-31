@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Jobs
@endsection
@section('page-content')
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">All Jobs</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('jobs.create') }}" class="btn btn-success text-bold">
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
                                <th width="15%">Title</th>
                                <th width="10%">Shift</th>
                                <th width="10%">JobType</th>
                                <th width="10%">Location</th>
                                <th width="10%">Salary</th>
                                <th width="10%">Close Date</th>
                                <th width="10%">Desc</th>
                                <th width="15%">Image</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($jobs as $job)
                                <tr>
                                    <td>{{ $job->created_at->toFormattedDateString() }}</td>
                                    <td>
                                        <h6>{{ $job->title ?? '' }}</h6>
                                        <span>{{ $job->department->department_name ?? '' }}</span> -
                                        <span>{{ $job->designation->designation_name ?? '' }}</span>
                                        <div class="manage-process mt-3">
                                            <a href="{{ route('jobs.edit', $job->id) }}" >
                                                <div class="edit-item"><i class="fas fa-edit"></i> Edit</div>
                                            </a>
                                            <a href="#">
                                                <div class="delete-item delete-job" data-job-id="{{ $job->id }}"
                                                    data-delete-route="{{ route('jobs.destroy', ':id') }}">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ $job->shift->name }}</td>
                                    <td>{{ $job->employment_type }}</td>
                                    <td>{{ $job->location }}</td>
                                    <td>{{ $job->salary_range }}</td>
                                    <td>{{ $job->closing_date }}</td>
                                    <td>{!! $job->description !!}</td>
                                    <td>
                                        <img src="{{ asset($job->job_img) }}" width="60" height="60">
                                    </td>

                                @empty
                                <tr>
                                    <td class="text-center" colspan="9">Record not found</td>
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
    .jobWrapper img {
        height: 300px;
        width: 100%;
        object-fit: cover;
        object-position: center center;
    }

    .jobDESC {
        height: 0;
        opacity: 0;
        visibility: hidden;
        transition: 0.4s ease;
    }

    .jobWrapper:hover .jobDESC {
        opacity: 1;
        visibility: visible;
        height: 100%;
        transition: 0.4s ease;
    }

    .jobWrapper,
    .jobWrapper:hover {
        transition: 0.4s ease;
    }

    .jobtns {
        background: #777;
        padding: 10px 20px;
        border-radius: 0px 0px 10px 10px;
        justify-content: center;
    }

    .jobtns a,
    .jobtns button {
        width: 100px;
        margin: 0px 20px;
    }

    .jobWrapper {
        padding: 30px;
        border-radius: 10px 10px 0px 0px;
        box-shadow: #0000006e 0px 0px 20px 0px;
        background: #fff;
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
                    targetElement.closest('tr').fadeOut('slow', function() {
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
