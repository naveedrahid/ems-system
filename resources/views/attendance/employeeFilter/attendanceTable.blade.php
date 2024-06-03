<table class="table table-bordered">
    <thead>
        <tr>
            <th width="13%">Name</th>
            <th width="20%">Date</th>
            <th width="9%">Check In</th>
            <th width="8%">In Status</th>
            <th width="9%">Check Out</th>
            <th width="8%">Out Status</th>
            <th width="9%">Total Hours</th>
            <th width="10%">OT</th>
            <th width="14%">Status</th>
        </tr>
    </thead>
    <tbody>
        @if ($attendance->isEmpty())
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
                        @if (optional($attendanceData)->check_in_status == 'Late In')
                            <span
                                style="color: #fff;background-color: #d58512;border-color: #985f0d;border-radius: 3px;padding:3px 3px;font-size: 11px;line-height: 1.5;">{{ optional($attendanceData)->check_in_status }}</span>
                        @elseif(optional($attendanceData)->check_in_status == 'Early In')
                            <span
                                style="color: #fff;background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;padding:3px 3px;font-size: 11px;line-height: 1.5;">{{ optional($attendanceData)->check_in_status }}</span>
                        @elseif(optional($attendanceData)->check_in_status == 'In')
                            <span
                                style="color: #fff;background-color: #204d74;border-color: #122b40;border-radius: 3px;padding:3px 3px;font-size: 11px;line-height: 1.5;">{{ optional($attendanceData)->check_in_status }}</span>
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
                                style="color: #fff;background-color: #ff851b;border-color: #f08323;border-radius: 3px;padding:3px 3px;font-size: 11px;line-height: 1.5;">{{ optional($attendanceData)->check_out_status }}</span>
                        @elseif(optional($attendanceData)->check_out_status == 'Late Out')
                            <span
                                style="color: #fff;background-color: #001f3f;border-color: #001f3f;border-radius: 3px;padding:3px 3px;font-size: 11px;line-height: 1.5;">{{ optional($attendanceData)->check_out_status }}</span>
                        @elseif(optional($attendanceData)->check_out_status == 'Out')
                            <span
                                style="color: #fff;background-color: #337ab7;border-color: #275f90;border-radius: 3px;padding:3px 3px;font-size: 11px;line-height: 1.5;">{{ optional($attendanceData)->check_out_status }}</span>
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
                            <span
                                style="padding: 3px 5px;border-radius: 3px;background-color: #f39c12; color: white;">Holiday</span>
                        @elseif ($holidayName)
                            <span
                                style="padding: 3px 5px;border-radius: 3px;background-color: #f39c12; color: white;">{{ $holidayName }}</span>
                        @elseif (optional($attendanceData)->status)
                            <span style="padding: 3px 5px;border-radius: 3px;background-color:#367fa9; color: white;">
                                {{ optional($attendanceData)->status ? textFormating($attendanceData->status) : '' }}
                            </span>
                        @elseif (!$attendanceData && !$weekend)
                            <span
                                style="padding: 3px 5px;border-radius: 3px;background-color: #dd4b39;color:#fff;">Absent</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
@push('css')
    <style>
        a#downloadLink {
            margin: 20px 0px;
        }
    </style>
@endpush
