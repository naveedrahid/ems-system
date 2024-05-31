@extends('masterLayout.app')
@section('main')
@section('page-title')
    Daily Attendance
@endsection
@section('page-content')
    <div class="box">
        <div class="box-body">
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
                        <th width="6%">status</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($users)
                        @foreach ($attendance as $result)
                            <tr>
                                <td>
                                    @foreach ($users as $user)
                                        @if ($result->user_id == $user->id)
                                            {{ $user->name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $result->attendance_date }}</td>
                                <td>{{ \Carbon\Carbon::parse(optional($result)->check_in)->format('g:i A') }}</td>
                                <td>
                                    @if (optional($result)->check_in_status == 'Late In')
                                        <span class="btn btn-warning btn-xs">{{ optional($result)->check_in_status }}</span>
                                    @elseif(optional($result)->check_in_status == 'Early In' || optional($result)->check_in_status == 'In')
                                        <span class="btn btn-info btn-xs">{{ optional($result)->check_in_status }}</span>
                                    @elseif(optional($result)->check_in_status == 'Early In' || optional($result)->check_in_status == 'In')
                                        <span class="btn btn-primary btn-xs">{{ optional($result)->check_in_status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($result->check_out !== null)
                                        {{ \Carbon\Carbon::parse(optional($result)->check_out)->format('g:i A') }}
                                    @endif
                                </td>
                                <td>
                                    @if (optional($result)->check_out_status == 'Early Out')
                                        <span class="btn btn-orange btn-xs">{{ optional($result)->check_out_status }}</span>
                                    @elseif(optional($result)->check_out_status == 'Late Out')
                                        <span
                                            class="btn btn-navy btn-xs">{{ optional($result)->check_out_status }}</span>
                                    @elseif(optional($result)->check_out_status == 'Out')
                                        <span
                                            class="btn btn-primary btn-xs">{{ optional($result)->check_out_status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($result->check_out !== null)
                                        {{ showEmployeeTime($result->check_in, $result->check_out) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($result->check_out)
                                        {{ calculateOvertime($result->check_out) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="btn btn-primary btn-xs">
                                        {{ $result->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@endsection
