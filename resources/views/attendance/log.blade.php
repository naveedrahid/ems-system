@extends('masterLayout.app')
@section('main')
@section('page-title')
    Attendance Log
@endsection
@section('page-content')

    <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th width="15%">Employee Name</th>
                        <th width="10%">Date</th>
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
                    @if ($attendance)
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
                                <td>
                                    {{ \Carbon\Carbon::parse($result->check_in)->format('g:i A') }}
                                </td>
                                <td>
                                    @if ($result->check_in_status == 'Late In')
                                        <span class="btn btn-danger btn-xs">{{ $result->check_in_status }}</span>
                                    @elseif($result->check_in_status == 'Early In')
                                        <span class="btn btn-success btn-xs">{{ $result->check_in_status }}</span>
                                    @else
                                        <span class="btn btn-success btn-xs">{{ $result->check_in_status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($result->check_out)
                                        {{ \Carbon\Carbon::parse($result->check_out)->format('g:i A') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($result->check_out_status == 'Early Out')
                                        <span class="btn btn-danger btn-xs">{{ $result->check_out_status ?? '' }}</span>
                                    @elseif($result->check_out_status == 'Late Out')
                                        <span class="btn btn-success btn-xs">{{ $result->check_out_status ?? '' }}</span>
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
                                    @if ($result->status)
                                        <span class="btn btn-primary btn-xs">{{ $result->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $attendance->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
@endsection                         