@extends('masterLayout.app')
@section('main')
@section('page-title')
    Current Month {{ date('M - Y') }}
@endsection
@section('page-content')
    <div class="box">
        <div class="box-body">
            <div class="btnGroup">
                <form action="{{ route('download-pdf') }}" method="GET" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-app btnPdf"><i class="fa-solid fa-file-pdf"></i></button>
                </form>
                <button id="printButton" class="btn btn-app btnPdf"><i class="fa-solid fa-print"></i></button>
            </div>
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th width="10%">Employee Name</th>
                        <th width="15%">Date</th>
                        <th width="10%">Check In</th>
                        <th width="10%">Check In Status</th>
                        <th width="10%">Check Out</th>
                        <th width="10%">Check Out Status</th>
                        <th width="10%">Total Hours</th>
                        <th width="5%">OT</th>
                        <th width="5%">status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentMonthDates = collect();
                        $startDate = Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1);
                        $endDate = $startDate->copy()->endOfMonth();
                        $currentMonthDates = $currentMonthDates->merge($startDate->toPeriod($endDate, '1 day'));
                        $atts = [];
                    @endphp
                    @foreach ($attendance as $att)
                        @php
                            $atts = $att;
                        @endphp
                    @endforeach
                    @foreach ($currentMonthDates as $date)
                        @php
                            $formattedDate = $date->format('Y-m-d');
                            $displayDate = $date->format('l - d M, Y');
                            $attendanceData = $attendance->where('attendance_date', $formattedDate)->first();
                            $weekend = \Carbon\Carbon::parse($date)->isWeekend();
                            $timeNow = \Carbon\Carbon::now();
                            $isFutureDate = \Carbon\Carbon::parse($date)->isFuture();
                        @endphp
                        <tr>
                            <td>{{ Auth::user()->name }}</td>
                            <td><span class="currentDate">{{ $displayDate }}</span></td>
                            <td>
                                @if (optional($attendanceData)->check_in)
                                    {{ \Carbon\Carbon::parse(optional($attendanceData)->check_in)->format('g:i A') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if (optional($attendanceData)->check_in_status == 'Late In')
                                    <span
                                        class="btn btn-warning btn-xs">{{ optional($attendanceData)->check_in_status }}</span>
                                @elseif(optional($attendanceData)->check_in_status == 'Early In')
                                    <span
                                        class="btn btn-info btn-xs">{{ optional($attendanceData)->check_in_status }}</span>
                                @elseif(optional($attendanceData)->check_in_status == 'In')
                                    <span
                                        class="btn btn-primary btn-xs">{{ optional($attendanceData)->check_in_status }}</span>
                                @endif
                            </td>
                            <td>
                                @if (optional($attendanceData)->check_out)
                                    {{ \Carbon\Carbon::parse(optional($attendanceData)->check_out)->format('g:i A') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if (optional($attendanceData)->check_out_status == 'Early Out')
                                    <span
                                        class="btn bg-orange btn-xs">{{ optional($attendanceData)->check_out_status }}</span>
                                @elseif(optional($attendanceData)->check_out_status == 'Late Out')
                                    <span
                                        class="btn bg-navy btn-xs">{{ optional($attendanceData)->check_out_status }}</span>
                                @elseif(optional($attendanceData)->check_out_status == 'Out')
                                    <span
                                        class="btn bg-primary btn-xs">{{ optional($attendanceData)->check_out_status }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($attendanceData && $attendanceData->check_out)
                                    {{ showEmployeeTime($attendanceData->check_in, $attendanceData->check_out) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($attendanceData && $attendanceData->check_out)
                                    {{ calculateOvertime($attendanceData->check_out) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($isFutureDate)
                                @elseif ($weekend)
                                    <span class="btn btn-warning btn-xs">Holiday</span>
                                @elseif (optional($attendanceData)->status)
                                    <span class="btn btn-success btn-xs">
                                        {{ optional($attendanceData)->status ? textFormating($attendanceData->status) : '' }}
                                    </span>
                                @elseif (!$attendanceData && !$weekend)
                                    <span class="btn btn-danger btn-xs">Absent</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    document.getElementById('printButton').addEventListener('click', function() {
        window.print();
    });
</script>
@endpush
