@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Interview
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('schedule-interviews.create') }}" class="btn btn-primary">
                    Create interview schedule
                </a>
            </h3>
        </div>
        <div class="box-body data-table table-responsive">
            <table class="table table-bordered table-hover">
                <thead style="background-color: #fff;">
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
                <tbody style="background: #fff;">
                    @if ($schedule_interviews->count() > 0)
                        @foreach ($schedule_interviews as $schedule_interview)
                            <tr>
                                <td>{{ $schedule_interview->created_at->toFormattedDateString() }}</td>
                                <td>
                                    {{ $schedule_interview->job->title }}
                                    <div class="manage-process mt-4">
                                        <a href="{{ route('schedule-interviews.show', $schedule_interview->id) }}"
                                            target="_blank">
                                            <div class="edit-item"><i class="fas fa-eye"></i> Profile</div>
                                        </a>
                                        @if ($schedule_interview->interviewerRemarks->isEmpty())
                                        @else
                                            <a href="{{ route('schedule-interviews.show', $schedule_interview->id) }}"
                                                target="_blank">
                                                <div class="edit-item"><i class="fas fa-eye"></i> Remark</div>
                                            </a>
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
                                        @else
                                            <span class="label label-succes">{{ $status }}</span>
                                        @endif
                                    @else
                                        <span class="label label-warning">Pending</span>
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
