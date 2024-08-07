@extends('masterLayout.app')
@section('main')
@section('page-title')
    Interviewer Remarks
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
                                <h4 class="text-bold">All Candidate Interviews Remarks</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead style="background-color: #fff;">
                            <tr>
                                <th width="10%">Date</th>
                                <th width="20%">Job Title</th>
                                <th width="10%">Type</th>
                                <th width="15%">Candidate Name</th>
                                <th width="10%">Status</th>
                                <th width="10%">CV / Cover letter</th>
                                <th width="15%">selected Notes / Rejected Notes</th>
                                <th width="10%">Manage</th>
                            </tr>
                        </thead>
                        <tbody style="background: #fff;">
                            @forelse ($interviewer_remarks as $interviewer_remark)
                                {{-- @if ($schedule_interview->interviewer_id === auth()->user()->id) --}}
                                <tr>
                                    <td>
                                        {{ $interviewer_remark->created_at ? $interviewer_remark->created_at->toFormattedDateString() : '' }}
                                    </td>
                                    <td>{{ $interviewer_remark->job->title }}</td>
                                    <td>{{ $interviewer_remark->scheduleInterview->interview_type }}</td>
                                    <td>{{ $interviewer_remark->candidate->first_name . ' ' . $interviewer_remark->candidate->last_name }}
                                    </td>
                                    <td>
                                        @php
                                            $status = $interviewer_remark->status;
                                            $badgeClass =
                                                $status === 'Pending'
                                                    ? 'warning'
                                                    : ($status === 'Selected'
                                                        ? 'active'
                                                        : 'deactive');
                                        @endphp
                                        <a href="{{ route('interviewer.status', $interviewer_remark->id) }}"
                                            class="status-toggle" data-id="{{ $interviewer_remark->id }}"
                                            data-status="{{ $interviewer_remark->status }}">
                                            <span class="badges {{ $badgeClass }}-badge">
                                                {{ $interviewer_remark->status }}
                                            </span>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="{{ asset($interviewer_remark->candidate->resume) }}" target="_blank"
                                                class="edit-item">
                                                <u>CV</u>
                                            </a>
                                            <a href="{{ asset($interviewer_remark->candidate->cover_letter) }}"
                                                target="_blank" class="edit-item">
                                                <u>Coverletter</u>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $selected = $interviewer_remark->selected_notes;
                                            $rejected = $interviewer_remark->rejected_notes;
                                        @endphp
                                        @if (!$selected && !$rejected)
                                            <button type="button" class="btn btn-default badges pass" data-toggle="modal"
                                                data-target="#selectedNotes">
                                                Selected Notes
                                            </button>
                                            <button type="button" class="btn btn-default badges reject" data-toggle="modal"
                                                data-target="#rejectedNotes">
                                                Rejected Notes
                                            </button>
                                        @elseif($selected && !$rejected)
                                            <button type="button" class="btn btn-default badges pass" data-toggle="modal"
                                                data-target="#selectedNotes"
                                                data-notes="{{ $interviewer_remark->selected_notes }}">
                                                Selected Notes
                                            </button>
                                        @elseif(!$selected && $rejected)
                                            <button type="button" class="btn btn-default badges reject" data-toggle="modal"
                                                data-target="#rejectedNotes"
                                                data-notes="{{ $interviewer_remark->rejected_notes }}">
                                                Rejected Notes
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="#">
                                                <div class="edit-item"><i class="fas fa-eye"></i> See Profile</div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td class="text-center" colspan="8">Data not found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if ($interviewer_remarks->count() > 0)
        {{-- Selected Notes Modal --}}
        <div class="modal fade show" id="selectedNotes" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Default Modal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    {!! Form::model($interviewer_remark, [
                        'url' => route('selected.remarks', $interviewer_remark->id),
                        'method' => 'PUT',
                        'id' => 'selectedNotesHandler',
                    ]) !!}
                    <div class="modal-body">
                        <div class="card small-box card-primary p-5 position-relative">
                            <div id="loadingSpinner" style="display: none; text-align: center;">
                                <i class="fas fa-spinner fa-spin fa-3x"></i>
                            </div>
                            @method('PATCH')

                            {!! Form::textarea('selected_notes', null, [
                                'id' => 'selected_notes',
                                'cols' => 30,
                                'rows' => 10,
                                'class' => 'form-control',
                            ]) !!}

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        {!! Form::submit('Send', ['class' => 'btn btn-primary setDisabled']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        {{-- End Modal --}}

        {{-- Rejected Notes Modal --}}
        <div class="modal fade show" id="rejectedNotes" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    {!! Form::model($interviewer_remark, [
                        'url' => route('rejected.remarks', $interviewer_remark->id),
                        'method' => 'PATCH',
                        'id' => 'rejectedNotesHandler',
                    ]) !!}
                    <div class="modal-body">
                        <div class="card small-box card-primary p-5 position-relative">
                            <div id="loadingSpinner" style="display: none; text-align: center;">
                                <i class="fas fa-spinner fa-spin fa-3x"></i>
                            </div>

                            @method('PATCH')

                            <div class="mb-3 form-group">
                                {!! Form::textarea('rejected_notes', null, [
                                    'id' => 'rejected_notes',
                                    'cols' => 30,
                                    'rows' => 10,
                                    'class' => 'form-control',
                                ]) !!}
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        {!! Form::submit('Send', ['class' => 'btn btn-primary setDisabled']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>

        </div>
        {{-- End Modal --}}
    @endif
@endsection
@endsection

@push('css')
<style>
    .warning-badge {
        background: #ffc10761;
        color: #b7942d;
    }

    a.status-toggle.disabled {
        opacity: 0.40;
        pointer-events: none;
    }

    #selectedNotes .modal-content,
    #rejectedNotes .modal-content {
        padding: 0;
    }

    #selectedNotes .modal-content .modal-header,
    #rejectedNotes .modal-content .modal-header {
        padding-top: 0;
        padding-bottom: 0;
        padding: 0;
        border: none;
    }

    #selectedNotes .modal-content .modal-header .close,
    #rejectedNotes .modal-content .modal-header .close {
        opacity: 1;
        background: #007bff;
        padding: 0;
        margin-left: auto;
        margin-top: 5px;
        margin-right: 5px;
        width: 40px;
        height: 40px;
        border-radius: 100%;
        box-shadow: #0000002e 0px 0px 10px 0px;
    }

    #selectedNotes .modal-content .modal-header .close span,
    #rejectedNotes .modal-content .modal-header .close span {
        color: #fff !important;
        opacity: 1 !important;
    }

    #selectedNotes .modal-content .card.small-box,
    #rejectedNotes .modal-content .card.small-box {
        padding-top: 25px !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        padding-bottom: 0 !important;
    }

    #selectedNotes button.btn.btn-default,
    #rejectedNotes button.btn.btn-default {
        background: #ef4444;
        color: #fff;
        border: none;
    }

    button.btn.btn-default.badges.reject {
        background-color: #FFF1F1;
        outline: 1px solid #FFCFCF;
        color: #EF4444;
        font-size: 13px !important;
    }

    button.btn.btn-default.badges.pass {
        background-color: #D6FFE3;
        outline: 1px solid #00ff4f4f;
        color: #22C55E;
    }

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
        $('#selectedNotes').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const notes = button.data('notes');
            const modal = $(this);
            modal.find('#selected_notes').val(notes);
        });

        $('#rejectedNotes').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const notes = button.data('notes');
            const modal = $(this);
            modal.find('#rejected_notes').val(notes);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.status-toggle').click(function(e) {
            e.preventDefault();
            const button = $(this);
            const statusSpan = button.find('.badges');
            let currentStatus = button.attr('data-status');
            const url = button.attr('href');
            const token = $('meta[name="csrf-token"]').attr('content');

            let nextStatus;
            if (currentStatus === 'Pending') {
                nextStatus = 'Selected';
                statusSpan.removeClass('warning-badge').addClass('active-badge');
            } else if (currentStatus === 'Selected') {
                nextStatus = 'Rejected';
                statusSpan.removeClass('active-badge').addClass('deactive-badge');
            } else if (currentStatus === 'Rejected') {
                nextStatus = 'Pending';
                statusSpan.removeClass('deactive-badge').addClass('warning-badge');
            }

            const formData = new FormData();
            formData.append('status', nextStatus);

            button.addClass('disabled');
            $('#loadingSpinner').show();

            $.ajax({
                    method: "PATCH",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .then((response) => {
                    $('#loadingSpinner').hide();
                    button.removeClass('disabled');
                    toastr.success(`Status ${nextStatus} updated successfully.`);
                    button.attr('data-status', nextStatus);
                    statusSpan.text(nextStatus);
                })
                .catch((err) => {
                    $('#loadingSpinner').hide();
                    toastr.error(`Failed Status Changed.`);
                    button.removeClass('disabled');
                    console.error('Error:', err);
                });

        });

        $('#selectedNotesHandler, #rejectedNotesHandler').submit(function(e) {
            e.preventDefault();

            if ($(e.target).attr('id') === 'rejectedNotesHandler') {
                const rejected_notes = $('textarea[name="rejected_notes"]').val().trim();
            } else {
                const selected_notes = $('textarea[name="selected_notes"]').val().trim();
            }

            if ($(e.target).attr('id') === 'rejectedNotesHandler') {
                if (rejected_notes === '') {
                    toastr.error('Rejected Candidate Notes must be required.');
                    return;
                }
            } else {
                if (selected_notes === '') {
                    toastr.error('Selected Candidate Notes must be required.');
                    return;
                }
            }

            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            const formData = $(this).serialize();
            button.prop('disabled', true);
            $('#loadingSpinner').show();

            $.ajax({
                    method: "PATCH",
                    url: url,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .then((response) => {

                    if ($(e.target).attr('id') === 'rejectedNotesHandler') {
                        toastr.success('Rejected Notes updated successfully.');
                        $('#rejectedNotesHandler')[0].reset();
                        $('#rejectedNotes').modal('hide');
                        $('.pass').remove();

                    } else {
                        toastr.success('Selected Notes updated successfully.');
                        $('#selectedNotesHandler')[0].reset();
                        $('#selectedNotes').modal('hide');
                        $('.reject').remove();
                    }

                    button.prop('disabled', false);
                    $('#loadingSpinner').hide();

                    setTimeout(() => {
                        window.location.href = "{{ route('interviewer-remarks.index') }}";
                    }, 3000);

                })
                .catch((err) => {
                    if ($(e.target).attr('id') === 'rejectedNotesHandler') {
                        toastr.error('Failed to update Rejected Notes.');
                        $('#rejectedNotes').modal('hide');
                        console.log('Rejected Candidate data failed');
                    } else {
                        toastr.error('Failed to update Selected Notes.');
                        $('#selectedNotes').modal('hide');
                        console.log('Selected Candidate data failed');
                    }
                    button.prop('disabled', false);
                    $('#loadingSpinner').hide();
                });
        });
    });
</script>
@endpush
