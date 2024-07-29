@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ 'Candidates' }}
@endsection
@section('page-content')
    <div id="loadingSpinner" style="display: none; text-align: center;">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead id="candidates-table">
                            <tr>
                                <th width="10%">Date</th>
                                <th width="20%">Name</th>
                                <th width="20%">Email</th>
                                <th width="5%">Gender</th>
                                <th width="15%">Interview Levels</th>
                                <th width="20%">Status</th>
                                <th width="10%">Manage</th>
                            </tr>
                        </thead>
                        @if ($candidates->count() > 0)
                            <tbody>
                                @foreach ($candidates as $candidate)
                                    <tr>
                                        <td>{{ $candidate->created_at->toFormattedDateString() }}</td>
                                        <td>{{ $candidate->first_name . ' ' . $candidate->last_name }}</td>
                                        <td>{{ $candidate->email }}</td>
                                        <td>{{ $candidate->gender }}</td>
                                        <td>
                                            @if ($candidate->scheduleInterviews->isEmpty())
                                                No interviews
                                            @else
                                                @foreach ($candidate->scheduleInterviews as $interview)
                                                    @php
                                                        $level = $interview->interview_type;
                                                    @endphp
                                                    <div
                                                        class="btn btn-block btn-outline-{{ $level == 'initial' ? 'primary' : ($level == 'technical' ? 'dark' : 'success') }} btn-sm">
                                                        {{ ucfirst($level) }} complete
                                                    </div>
                                                @endforeach
                                            @endif
                                        </td>
                                        {{-- <td>
                                        @if ($candidate->resume)
                                            <a target="_blank" href="{{ asset('storage/' . $candidate->resume) }}">View CV</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($candidate->cover_letter)
                                            <a href="{{ asset('storage/' . $candidate->cover_letter) }}">View CV</a>
                                        @endif
                                    </td> --}}
                                        <td>
                                            @php
                                                $status = $candidate->application_status;
                                            @endphp
                                            @switch($status)
                                                @case('Pending')
                                                    @php $statusClass = 'warning' @endphp
                                                @break

                                                @case('Selected')
                                                    @php $statusClass = 'success' @endphp
                                                @break

                                                @case('Rejected')
                                                    @php $statusClass = 'danger' @endphp
                                                @break

                                                @case('Interview Scheduled')
                                                    @php $statusClass = 'primary' @endphp
                                                @break

                                                @default
                                            @endswitch
                                            <div class="candidate-row" data-id="{{ $candidate->id }}">
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-{{ $statusClass }} status-btn setDisabled">
                                                        {{ $status }}
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-{{ $statusClass }} setDisabled dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        @foreach ($applicationStatuses as $item)
                                                            <li
                                                                @if ($item === $status) style="display:none;" @endif>
                                                                <a href="#" class="candidate-status"
                                                                    data-status="{{ $item }}"
                                                                    data-id="{{ $candidate->id }}">
                                                                    {{ $item }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="manage-process">
                                                <a href="{{ route('candidates.show', $candidate->id) }}">
                                                    <div class="edit-item"><i class="fas fa-eye"></i> View</div>
                                                </a>
                                                <a href="#">
                                                    <div class="delete-item delete-candidate"
                                                        data-id="{{ $candidate->id }}"
                                                        data-delete-route="{{ route('candidates.destroy', $candidate->id) }}">
                                                        <i class="far fa-trash-alt"></i> Delete
                                                    </div>
                                                </a>
                                                {{-- <button class="delete-candidate btn btn-danger btn-flat btn-sm"
                                            data-id="{{ $candidate->id }}"
                                            data-delete-route="{{ route('candidates.destroy', $candidate->id) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="9">
                                        {{ $candidates->links('pagination::bootstrap-4') }}
                                    </td>
                                </tr>
                            </tfoot>
                        @else
                            <tbody>
                                <tr>
                                    <td colspan="9">No Candidate Found!</td>
                                </tr>
                            </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <iframe src="{{ asset($documentUser->experience_letter) }}" width="100" height="100" style="border: none;"></iframe> --}}
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

    .candidate-row ul.dropdown-menu {
        background: #e5e5e5;
        padding: 15px;
    }

    .candidate-row ul.dropdown-menu li {
        padding: 5px 0px !important;
        border-top: solid 1px #cccc;
    }

    .candidate-row ul.dropdown-menu li a {
        color: #000;
        font-size: 14px;
    }

    .candidate-row ul.dropdown-menu li:first-child {
        border-top: none;
    }

    .candidate-row ul.dropdown-menu li a[data-disabled="true"] {
        pointer-events: none;
        color: #80808080;
        cursor: not-allowed;
        text-decoration: none;
        user-select: none;
    }
</style>
@endpush
@push('js')
<script>
    $(document).ready(function() {

        $('.candidate-status').click(function(e) {
            e.preventDefault();
            const newStatus = $(this).data('status');
            const candidateId = $(this).data('id');
            const row = $(this).closest('.candidate-row');
            const buttons = row.find('.setDisabled');
            const token = $('meta[name="csrf-token"]').attr('content');

            buttons.prop('disabled', true);
            $('#loadingSpinner').show();

            $.ajax({
                    url: `/portal/candidates/status/${candidateId}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        status: newStatus
                    },
                })
                .then((response) => {
                    let statusBtn = row.find('.status-btn');
                    statusBtn.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                    statusBtn.removeClass('btn-warning btn-success btn-danger btn-primary');

                    if (newStatus === 'Pending') {
                        statusBtn.addClass('btn-warning');
                    } else if (newStatus === 'Selected') {
                        statusBtn.addClass('btn-success');
                    } else if (newStatus === 'Rejected') {
                        statusBtn.addClass('btn-danger');
                    } else if (newStatus === 'Interview Scheduled') {
                        statusBtn.addClass('btn-primary');
                    }

                    let dropdownToggle = row.find('.dropdown-toggle');
                    dropdownToggle.removeClass(
                        'btn-warning btn-success btn-danger btn-primary');

                    if (newStatus === 'Pending') {
                        dropdownToggle.addClass('btn-warning');
                    } else if (newStatus === 'Selected') {
                        dropdownToggle.addClass('btn-success');
                    } else if (newStatus === 'Rejected') {
                        dropdownToggle.addClass('btn-danger');
                    } else if (newStatus === 'Interview Scheduled') {
                        dropdownToggle.addClass('btn-primary');
                    }

                    buttons.prop('disabled', false);
                    $('#loadingSpinner').hide();
                }).catch((err) => {
                    console.error(err);
                    buttons.prop('disabled', false);
                    $('#loadingSpinner').hide();
                });

        });

        $('.delete-candidate').on('click', function(e) {
            e.preventDefault();
            // const documentID = $(this).data('document-id');
            const deleteRoute = $(this).data('delete-route');
            const targetElement = $(this);
            const button = $(this);
            button.prop('disabled', true);

            if (confirm('Are you sure you want to delete this candidate?')) {

                $('#loadingSpinner').show();
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    method: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(response) {
                    setTimeout(() => {
                        toastr.success(response.message);
                        $('#loadingSpinner').hide();
                    }, 1000);
                    button.prop('disabled', false);
                    targetElement.closest('tr').fadeOut('slow', function() {
                        $(this).css('background', 'red');
                        $(this).remove();
                    });
                }).catch(function(err) {
                    $('#loadingSpinner').hide();
                    console.log(err);
                    button.prop('disabled', false);
                    toastr.error('Failed to delete candidate');
                });
            } else {
                button.prop('disabled', false);
            }
        });
    });
</script>
@endpush
