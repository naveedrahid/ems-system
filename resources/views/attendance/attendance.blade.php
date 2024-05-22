@extends('masterLayout.app')
@section('main')
@section('page-title')
    Current Month Attendance
@endsection
@section('page-content')
    <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th width="20%">Name</th>
                        <th width="20%">Date</th>
                        <th width="10%">Check In</th>
                        <th width="10%">Check Out</th>
                        <th width="10%">Total Hours</th>
                        <th width="10%">Over Time</th>
                        <th width="10%">status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentMonthDates = collect();
                        $startDate = Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1);
                        $endDate = $startDate->copy()->endOfMonth();

                        $currentMonthDates = $currentMonthDates->merge($startDate->toPeriod($endDate, '1 day'));
                    @endphp

                    @foreach ($currentMonthDates as $date)
                        @php
                            $formattedDate = $date->format('Y-m-d');
                            $displayDate = $date->format('l - d M, Y');
                            $attendanceData = $attendance->where('attendance_date', $formattedDate)->first();
                            $weekend = \Carbon\Carbon::parse($date)->isWeekend();
                            $timeNow = \Carbon\Carbon::now();

                        @endphp
                        <tr>
                            <td>
                                <!-- @foreach ($users as $user)
                                    @if (optional($attendanceData)->user_id == $user->id)
                                        {{ $user->name }}
                                    @endif
                                @endforeach -->
                                {{Auth::user()->name}}
                            </td>
                            <td>
                                <span class="currentDate">{{ $displayDate }}</span>
                            </td>
                            <td>
                                @if (optional($attendanceData)->check_in)
                                    {{ \Carbon\Carbon::parse(optional($attendanceData)->check_in)->format('g:i A') }}
                                @else
                                    -
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
                                @if ($attendanceData && $attendanceData->check_out)
                                    {{ showEmployeeTime($attendanceData->check_in, $attendanceData->check_out) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                {{ optional($attendanceData)->total_overtime }}
                            </td>
                            <td>
                                @if ($weekend)
                                    <span class="btn btn-danger btn-xs">Holiday</span>
                                @elseif(optional($attendanceData)->status)
                                    <span class="btn btn-primary btn-xs">
                                        {{ optional($attendanceData)->status ? textFormating($attendanceData->status) : '' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="box-footer clearfix">
            <div class="row">
                {{-- <div class="col-sm-6">
                <span style="display:block;font-size:15px;line-height:34px;margin:20px 0;">
                    Showing 100 to 500 of 1000 entries
                </span>
            </div> --}}
                {{-- <div class="col-sm-6 text-right">
                <ul class="pagination">
                    <li class="paginate_button previous"><a href="#">Previous</a></li>
                    <li class="paginate_button active"><a href="#">1</a></li>
                    <li class="paginate_button "><a href="#">2</a></li>
                    <li class="paginate_button "><a href="#">3</a></li>
                    <li class="paginate_button "><a href="#">4</a></li>
                    <li class="paginate_button "><a href="#">5</a></li>
                    <li class="paginate_button "><a href="#">6</a></li>
                    <li class="paginate_button next"><a href="#">Next</a></li>
                </ul>
            </div> --}}
            </div>
        </div>
    </div>
@endsection
@endsection
