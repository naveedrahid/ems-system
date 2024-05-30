<!DOCTYPE html>
<html>
<head>
    <title>PDF Document</title>
    <style>
        body {
            font-family: 'Arial, sans-serif';
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
    <style>
        * {
            margin:0;
            padding:0;
            font-family: 'Muli' !important;
        }
    
        html{
            font-family: 'Muli' !important;
            padding: 0;
        }
        body{
            padding: 20px;
            background-color: #fff;
        }
    
    
        table{
            width: 100%;
        }
        table thead tr th{
            padding: 15px;
            font-size: 14px;
            font-weight: lighter;
        }
        table thead tr th:last-child{
            text-align: right;
        }
        table tr td{
            padding: 15px;
            vertical-align: top;
            font-size: 14px;
            border-top: 1px solid #b9b9b9;
        }
        table tr td:last-child{
            text-align: right;
        }
        table tr:last-child td{
            border-bottom: 1px solid #b9b9b9;
        }
        table thead tr th{
            padding:10px 10px;
        }
    </style>
</head>
<body>
    <div class="logo">
        <h1 style="text-align: center;">{{ auth()->user()->name }}</h1>
        o<h4 style="text-align: center;">Attendance Report Month of {{date('M Y')}}</h4>
    </div>
    <table class="table table-bordered">
        <thead style="background-color: #F8F8F8;">
            <tr>
                <th width="10%"><strong>Name</strong></th>
                <th width="15%"><strong>Date</strong> </th>
                <th width="10%"><strong>Check In</strong></th>
                <th width="10%"><strong>In Status</strong></th>
                <th width="10%"><strong>Check Out</strong></th>
                <th width="10%"><strong>Out Status</strong></th>
                <th width="10%"><strong>Hours</strong></th>
                <th width="5%"><strong>OT</strong></th>
                <th width="5%"><strong>status</strong></th>
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
                            <span class="btn btn-danger btn-xs">{{ optional($attendanceData)->check_in_status }}</span>
                        @elseif(optional($attendanceData)->check_in_status == 'Early In')
                            <span class="btn btn-success btn-xs">{{ optional($attendanceData)->check_in_status }}</span>
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
                            <span class="btn btn-danger btn-xs">{{ optional($attendanceData)->check_out_status }}</span>
                        @elseif(optional($attendanceData)->check_out_status == 'Late Out')
                            <span class="btn btn-success btn-xs">{{ optional($attendanceData)->check_out_status }}</span>
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
                            {{ calculateOvertime($attendanceData->check_in, $attendanceData->check_out) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($isFutureDate)
                                        
                        @elseif ($weekend)
                            <span style="padding: 3px 5px;border-radius: 3px;background-color: #f39c12; color: white;">Holiday</span>
                        @elseif (optional($attendanceData)->status)
                            <span style="padding: 3px 5px;border-radius: 3px;background-color:#367fa9; color: white;">
                                {{ optional($attendanceData)->status ? textFormating($attendanceData->status) : '' }}
                            </span>
                        @elseif (!$attendanceData && !$weekend)
                            <span style="padding: 3px 5px;border-radius: 3px;background-color: #dd4b39;color:#fff;">Absent</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

