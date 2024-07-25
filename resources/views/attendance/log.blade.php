@extends('masterLayout.app')
@section('main')
@section('page-title')
    Attendance Log
@endsection
@section('page-content')
    <div class="box">
        <div class="box-body">
            <div id="accordion">
                @foreach ($allDates as $date)
                    @php
                        $formattedDate = $date->format('Y-m-d');
                        $attendanceForDate = $attendanceByDate->get($formattedDate, collect());
                        $holidayName = checkHoliday($formattedDate, $holidays);
                        $leaveStatus = checkLaeve($formattedDate, $leaves);
                        $isWeekend = $date->isSaturday() || $date->isSunday();
                    @endphp
            
                    <div class="card">
                        <div class="card-header" id="heading-{{ $formattedDate }}">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse-{{ $formattedDate }}" aria-expanded="true" aria-controls="collapse-{{ $formattedDate }}">
                                    {{ $date->format('l - d M, Y') }}
                                </button>
                            </h5>
                        </div>
            
                        <div id="collapse-{{ $formattedDate }}" class="collapse" aria-labelledby="heading-{{ $formattedDate }}" data-parent="#accordion">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Check In</th>
                                            <th>Check In Status</th>
                                            <th>Check Out</th>
                                            <th>Check Out Status</th>
                                            <th>Total Hours</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            @php
                                                $attendanceRecord = $attendanceForDate->where('user_id', $user->id)->first();
                                            @endphp
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>
                                                    @if ($attendanceRecord && $attendanceRecord->check_in)
                                                        {{ \Carbon\Carbon::parse($attendanceRecord->check_in)->format('g:i A') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($attendanceRecord && $attendanceRecord->check_in_status)
                                                        <span class="badge bg-{{ $attendanceRecord->check_in_status == 'Late In' ? 'warning' : ($attendanceRecord->check_in_status == 'Early In' ? 'info' : 'primary') }}">
                                                            {{ $attendanceRecord->check_in_status }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($attendanceRecord && $attendanceRecord->check_out)
                                                        {{ \Carbon\Carbon::parse($attendanceRecord->check_out)->format('g:i A') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($attendanceRecord && $attendanceRecord->check_out_status)
                                                        <span class="badge bg-{{ $attendanceRecord->check_out_status == 'Early Out' ? 'orange' : ($attendanceRecord->check_out_status == 'Late Out' ? 'navy' : 'primary') }}">
                                                            {{ $attendanceRecord->check_out_status }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($attendanceRecord && $attendanceRecord->check_in && $attendanceRecord->check_out)
                                                        {{ showEmployeeTime($attendanceRecord->check_in, $attendanceRecord->check_out) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($attendanceRecord)
                                                        <span class="btn btn-primary btn-xs">Present</span>
                                                    @elseif ($holidayName)
                                                        <span class="btn btn-warning btn-xs">{{ $holidayName }}</span>
                                                    @elseif ($leaveStatus == 'Approved')
                                                        <span class="btn btn-success btn-xs">Leave</span>
                                                    @elseif ($isWeekend)
                                                        <span class="btn btn-info btn-xs">Weekend</span>
                                                    @else
                                                        <span class="btn btn-danger btn-xs">Absent</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- <div class="d-flex justify-content-center">
                {{ $attendance->links('pagination::bootstrap-4') }}
            </div> --}}
        </div>
    </div>
@endsection
@endsection

@push('css')
<style>
    .btn {
        padding: 3px 5px !important;
        border-radius: 3px;
        color: white;
        font-size: 11px !important;
        line-height: 1.5;
    }

    .btn-warning {
        background-color: #f39c12;
    }

    .btn-primary {
        background-color: #367fa9;
    }

    .btn-danger {
        background-color: #dd4b39;
    }

    .btn-success {
        background-color: #28a745;
    }

    .card-header h5 button {
        color: #000 !important;
        font-size: 25px !important;
        font-weight: 900;
        width: 100%;
    }

    .card-header h5 button.btn.btn-link:after {
        content: "\f068";
        position: absolute;
        right: 0;
        font-family: "Font Awesome 5 Free";
    }
    /* .card-header h5 button.btn.btn-link:after {
        content: "\f068" !important;
        position: absolute;
        right: 0;
        font-family: "Font Awesome 5 Free";
    } */

    .card-header h5 button.btn.btn-link.collapsed:after {
        content: "\f067" !important;
        position: absolute;
        right: 0;
        font-family: "Font Awesome 5 Free";
    }
</style>
@endpush
@push('js')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endpush
