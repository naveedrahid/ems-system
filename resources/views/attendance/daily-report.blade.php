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
                        <th width="4%"><input type="checkbox" name="" id="checkAll"></th>
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
                    @if ($users)
                        @foreach ($attendance as $result)
                            <tr>
                                <td><input type="checkbox" name="" id="" class="checkSingle"></td>
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
                                    @if ($result->check_out !== null)
                                        {{ \Carbon\Carbon::parse(optional($result)->check_out)->format('g:i A') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($result->check_out !== null)
                                        {{ showEmployeeTime($result->check_in, $result->check_out) }}
                                    @endif
                                </td>
                                <td>{{ $result->total_overtime }}</td>
                                <td><span class="btn btn-primary btn-xs">{{ textFormating($result->status) }}</span></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                
            </table>
        </div>
    </div>
@endsection
@endsection
