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
                        <h4 class="text-bold">Candidate Personal Info</h4>
                    </div>
                    <div class="card-body p-0 mb-3">
                        <div class="table-responsive">
                            @if ($schedule_interview->interviewerRemarks->isEmpty())
                            <p>No remarks found.</p>
                        @else
                            @foreach ($schedule_interview->interviewerRemarks as $remark)
                                <div>
                                    <p>Status: {{ $remark->status }}</p>
                                    <p>Selected Notes: {{ $remark->selected_notes }}</p>
                                    <p>Rejected Notes: {{ $remark->rejected_notes }}</p>
                                </div>
                                <hr>
                            @endforeach
                        @endif
                        </div>
                    </div>
                </div>
                <a href="{{route('schedule-interviews.index')}}" class="btn btn-danger">Back</a>
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
