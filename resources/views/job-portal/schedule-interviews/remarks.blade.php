@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ 'Candidate' }}
@endsection
@section('page-content')
    <div class="card-body profile-box">
        <div class="row">
            <div class="col-md-12">
                <div class="card small-box profile-info">
                    <div class="card-header">
                        <h4 class="text-bold">Interviewer Remarks</h4>
                    </div>
                    <div class="card-body p-0 mb-3">
                        <table class="table m-0">
                            <tbody class="menual-update-1">
                                @forelse ($interviewer_remarks as $interviewer_remark)
                                    <tr>
                                        <td width="30%">Interview Type</td>
                                        <td width="70%">{{ $schedule_interview->interview_type }}</td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Interviewer Remarks</td>
                                        <td width="70%">
                                            @if ($interviewer_remark->rejected_notes)
                                                {{$interviewer_remark->rejected_notes}}
                                            @elseif ($interviewer_remark->selected_notes)
                                                {{$interviewer_remark->selected_notes}}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td>No Remarks found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="{{ route('schedule-interviews.index') }}" class="btn btn-danger">Back</a>
            </div>

        </div>
    </div>
@endsection
@endsection

@push('css')
<style>
    .profile-box table tr {
        font-size: 16px !important;
    }
</style>
@endpush
