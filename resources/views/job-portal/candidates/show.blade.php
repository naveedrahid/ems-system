@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ 'Candidate' }}
@endsection
@section('page-content')
    <div class="card-body profile-box">
        <h3 class="text-bold ml-1 pt-2 pb-2">{{ $candidateWithJob->job->title ?? '' }}</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="card small-box profile-info">
                    <div class="card-header">
                        <h4 class="text-bold">Candidate Personal Info</h4>
                    </div>
                    <div class="card-body p-0 mb-3">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody class="menual-update-1">
                                    <tr>
                                        <td class="text-bold">First Name</td>
                                        <td>{{ $candidateWithJob->first_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Last Name</td>
                                        <td>{{ $candidateWithJob->last_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Age</td>
                                        <td>{{ $candidateWithJob->age ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Country</td>
                                        <td>{{ $countryName ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">City</td>
                                        <td>{{ $cityName ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Gender</td>
                                        <td>{{ $candidateWithJob->gender ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Address</td>
                                        <td>=</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Marital Status</td>
                                        <td>{{ $candidateWithJob->marital_status ?? '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card small-box profile-info">
                    <div class="card-header">
                        <h4 class="text-bold">Contact Info</h4>
                    </div>
                    <div class="card-body p-0 mb-3">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody class="menual-update-1">
                                    <tr>
                                        <td class="text-bold">Email</td>
                                        <td>{{ $candidateWithJob->email ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Phone</td>
                                        <td>{{ $candidateWithJob->phone ?? ''}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card small-box profile-info">
                    <div class="card-header">
                        <h4 class="text-bold">Attachments</h4>
                    </div>
                    <div class="card-body p-0 mb-3">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody class="menual-update-1">
                                    <tr>
                                        <td class="text-bold">Resume</td>
                                        <td><a href="{{ asset($candidateWithJob->resume) }}" target="_blank">View
                                                Resume</a></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Cover Letter</td>
                                        <td>
                                            <a href="{{ asset($candidateWithJob->cover_letter) }}" target="_blank">Download
                                                Cover Letter</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card small-box profile-info">
                    <div class="card-header">
                        <h4 class="text-bold">Job Description</h4>
                    </div>
                    <div class="card-body p-0 mb-3">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody class="menual-update-1">
                                    <tr>
                                        <td class="text-bold">Total Experience</td>
                                        <td>{{ $candidateWithJob->total_experience ?? '' }}year</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Current Salary</td>
                                        <td>${{ number_format($candidateWithJob->current_salary, 0, '.', ',') ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Expected Salary</td>
                                        <td>${{ number_format($candidateWithJob->expected_salary, 0, '.', ',') ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Notice Period</td>
                                        <td>{{ $candidateWithJob->notice_period ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Switching Reason</td>
                                        <td>{{ $candidateWithJob->switching_reason ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        @php
                                            $date = new DateTime($candidateWithJob->datetime);
                                            $formattedDate = $date->format('l d M g:iA');
                                        @endphp
                                        <td class="text-bold">Your Available Date and Time</td>
                                        <td>{{ $formattedDate ?? ''}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card small-box profile-info">
                    <div class="card-header">
                        <h4 class="text-bold">Social Info</h4>
                    </div>
                    <div class="card-body p-0 mb-3">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody class="menual-update-1">
                                    @if ($candidateWithJob->linkdin)
                                        <tr>
                                            <td class="text-bold">LinkedIn</td>
                                            <td><a href="{{ $candidateWithJob->linkdin ?? '' }}"
                                                    target="_blank">{{ $candidateWithJob->linkdin ?? '' }}</a></td>
                                        </tr>
                                    @endif
                                    @if ($candidateWithJob->github)
                                        <tr>
                                            <td class="text-bold">GitHub</td>
                                            <td><a href="{{ $candidateWithJob->github ?? '' }}"
                                                    target="_blank">{{ $candidateWithJob->github ?? '' }}</a></td>
                                        </tr>
                                    @endif
                                    @if ($candidateWithJob->behance)
                                        <tr>
                                            <td class="text-bold">Behance</td>
                                            <td><a href="{{ $candidateWithJob->behance ?? '' }}"
                                                    target="_blank">{{ $candidateWithJob->behance ?? '' }}</a></td>
                                        </tr>
                                    @endif
                                    @if ($candidateWithJob->website)
                                        <tr>
                                            <td class="text-bold">Website</td>
                                            <td><a href="{{ $candidateWithJob->website ?? '' }}"
                                                    target="_blank">{{ $candidateWithJob->website ?? '' }}</a>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
