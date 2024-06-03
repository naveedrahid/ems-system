<table class="table table-bordered">
    <thead>
        <tr>
            <th width="10%">Employee Name</th>
            <th width="15%">Date</th>
            <th width="10%">Check In</th>
            <th width="10%">Check In Status</th>
            <th width="10%">Check Out</th>
            <th width="10%">Check Out Status</th>
            <th width="10%">Total Hours</th>
            <th width="5%">OT</th>
            <th width="5%">Status</th>
        </tr>
    </thead>
    <tbody>
        @if($attendance->isEmpty())
            <tr>
                <td colspan="9" class="text-center">No record found</td>
            </tr>
        @else
            @php
                $currentMonthDates = collect();
                $startDate = Carbon\Carbon::createFromDate($year, $month, 1);
                $endDate = $startDate->copy()->endOfMonth();
                $currentMonthDates = $currentMonthDates->merge($startDate->toPeriod($endDate, '1 day'));
            @endphp
            @foreach ($currentMonthDates as $date)
                @php
                    $formattedDate = $date->format('Y-m-d');
                    $displayDate = $date->format('l - d M, Y');
                    $attendanceData = $attendance->where('attendance_date', $formattedDate)->first();
                    $weekend = \Carbon\Carbon::parse($date)->isWeekend();
                    $isFutureDate = \Carbon\Carbon::parse($date)->isFuture();
                    $holidayName = checkHoliday($formattedDate, $holidays);
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
                        @if (optional($attendanceData)->check_in_status)
                            <span class="btn btn-{{ optional($attendanceData)->check_in_status == 'Late In' ? 'warning' : (optional($attendanceData)->check_in_status == 'Early In' ? 'info' : 'primary') }} btn-xs">{{ optional($attendanceData)->check_in_status }}</span>
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
                        @if (optional($attendanceData)->check_out_status)
                            <span class="btn btn-{{ optional($attendanceData)->check_out_status == 'Early Out' ? 'bg-orange' : (optional($attendanceData)->check_out_status == 'Late Out' ? 'bg-navy' : 'bg-primary') }} btn-xs">{{ optional($attendanceData)->check_out_status }}</span>
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
                        @elseif ($holidayName)
                            <span class="btn btn-warning btn-xs">{{ $holidayName }}</span>
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
        @endif
    </tbody>
</table>
