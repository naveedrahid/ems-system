@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Schedule Interview
@endsection
@section('page-content')
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">All Schedule Interviews</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('schedule-interviews.create') }}" class="btn btn-success text-bold">
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
                                <th width="10%">Name</th>
                                <th width="5%">Type</th>
                                <th width="10%">Status</th>
                                <th width="10%">Interviewer Name</th>
                                <th width="15%">Date / Time</th>
                                <th width="15%">Interviewer Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($schedule_interviews->count() > 0)
                                @foreach ($schedule_interviews as $schedule_interview)
                                    {{-- @dd($schedule_interview->interviewerRemarks) --}}
                                    <tr>
                                        <td>{{ $schedule_interview->created_at->toFormattedDateString() }}</td>
                                        <td>
                                            {{ $schedule_interview->job->title }}
                                            <div class="manage-process mt-4">
                                                <a href="{{ route('schedule-interviews.show', $schedule_interview->id) }}"
                                                    target="_blank">
                                                    <div class="edit-item"><i class="fas fa-eye"></i> Profile</div>
                                                </a>
                                                @if ($schedule_interview->interviewerRemarks->isNotEmpty())
                                                    @php
                                                        $interviewerRemark = $schedule_interview->interviewerRemarks->first();
                                                        $selectedNotes = $interviewerRemark->selected_notes;
                                                        $rejectedNotes = $interviewerRemark->rejected_notes;
                                                    @endphp

                                                    @if (is_null($selectedNotes) && is_null($rejectedNotes))
                                                        <!-- No remarks -->
                                                    @elseif($selectedNotes || $rejectedNotes)
                                                        <a href="{{ route('schedule-interviews.remarks', $schedule_interview->id) }}"
                                                            target="_blank">
                                                            <div class="edit-item"><i class="fas fa-eye"></i> Remark</div>
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $schedule_interview->candidate->first_name . ' ' . $schedule_interview->candidate->last_name }}
                                        </td>
                                        <td>{{ $schedule_interview->interview_type }}</td>
                                        <td>
                                            @if ($schedule_interview->interviewerRemarks->isNotEmpty())
                                                @php
                                                    $status = $schedule_interview->interviewerRemarks->first()->status;
                                                @endphp
                                                @if ($status === 'Rejected')
                                                    <span class="label label-danger">{{ $status }}</span>
                                                @elseif($status === 'Selected')
                                                    <span class="label label-success">{{ $status }}</span>
                                                @elseif($status === 'Pending')
                                                    <span class="label label-warning">Pending</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            {{ $schedule_interview->user->name }}
                                        </td>
                                        @php
                                            $interviewDate = $schedule_interview->interview_date;
                                            $interviewTime = $schedule_interview->interview_time;

                                            $date = new DateTime($interviewDate);
                                            $time = new DateTime($interviewTime);

                                            $formattedDate = $date->format('D F');
                                            $formattedTime = $time->format('g:ia');
                                        @endphp

                                        <td>{{ $formattedDate . ' - ' . $formattedTime }}</td>
                                        <td>
                                            {!! $schedule_interview->candidate_notes !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{-- {{ $schedule_interviews->links('pagination::bootstrap-4') }} --}}
                </div>
            </div>
        </div>
    @endsection
@endsection

@push('css')
    <style>
        .label {
            display: inline;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
        }

        .label-warning {
            background-color: #f39c12 !important;
        }

        .label-danger {
            background-color: #dd4b39 !important;
        }

        .label-success {
            background-color: #00a65a !important;
        }
    </style>
@endpush

@push('js')
    {{-- <script>
    $(document).ready(function() {
        $('.status-change').click(function(e) {
            e.preventDefault();
            const newStatus = $(this).data('status');
            const leaveId = $(this).data('id');
            const row = $(this).closest('.leave-application-row');
            const buttons = row.find('.setDisabled');

            buttons.prop('disabled', true);

            $.ajax({
                url: '/schedule-interviews/' + leaveId,
                type: 'POST',
                data: {
                    status: newStatus,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    let statusBtn = row.find('.status-btn');
                    statusBtn.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                    statusBtn.removeClass('btn-warning btn-success btn-danger');

                    if (newStatus === 'approved') {
                        statusBtn.addClass('btn-success');
                    } else if (newStatus === 'rejected') {
                        statusBtn.addClass('btn-danger');
                    } else {
                        statusBtn.addClass('btn-warning');
                    }

                    let dropdownToggle = row.find('.dropdown-toggle');
                    dropdownToggle.removeClass('btn-warning btn-success btn-danger');

                    if (newStatus === 'approved') {
                        dropdownToggle.addClass('btn-success');
                    } else if (newStatus === 'rejected') {
                        dropdownToggle.addClass('btn-danger');
                    } else {
                        dropdownToggle.addClass('btn-warning');
                    }

                    buttons.prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    buttons.prop('disabled', false);
                }
            });
        });
    });
</script> --}}
@endpush
