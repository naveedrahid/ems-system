@extends('masterLayout.app')
@section('main')
@section('page-title')
    Daily Attendance Report
@endsection
@section('page-content')
    <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th width="4%"><input type="checkbox" name="" id="checkAll"></th>
                        <th width="6%">ID</th>
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
                    @if ($authUserId == 1 || $authUserId == 2)
                        @foreach ($attendance as $result)
                            <tr>
                                <td><input type="checkbox" name="" id="" class="checkSingle"></td>
                                <td>#{{ $result->id }}</td>
                                <td>
                                    @foreach ($users as $user)
                                        @if ($result->user_id == $user->id)
                                            {{ $user->name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $result->attendance_date }}</td>
                                <td>{{ $result->check_in }}</td>
                                <td>{{ $result->check_out }}</td>
                                <td>
                                    @if ($result->check_out !== null)
                                        {{ showEmployeeTime($result->check_in, $result->check_out) }}
                                    @endif
                                </td>
                                <td>{{ $result->total_overtime }}</td>
                                <td><span class="btn btn-primary btn-xs">{{ textFormating($result->status) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        @foreach ($attendance as $result)
                            @if ($result->user_id == $authUserId && $result->attendance_date == now()->format('Y-m-d'))
                                <tr>
                                    <td><input type="checkbox" name="" id="" class="checkSingle"></td>
                                    <td>#{{ $result->id }}</td>
                                    <td>
                                        @foreach ($users as $user)
                                            @if ($result->user_id == $user->id)
                                                {{ $user->name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $result->attendance_date }}</td>
                                    <td>{{ $result->check_in }}</td>
                                    <td>{{ $result->check_out }}</td>
                                    <td>
                                        @if ($result->check_out !== null)
                                            {{ showEmployeeTime($result->check_in, $result->check_out) }}
                                        @endif
                                    </td>
                                    <td>{{ $result->total_overtime }}</td>
                                    <td><span class="btn btn-primary btn-xs">{{ textFormating($result->status) }}</span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@endsection
