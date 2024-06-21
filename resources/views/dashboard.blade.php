@extends('masterLayout.app')
@section('main')
@section('page-title')
    Dashboard
@endsection
@section('page-content')
    <div class="row" style="margin-bottom:30px;">
        <div class="col-md-8">
            <div>
                <h1>{{ auth()->user()->name }} |
                    <small>
                        {{ $designation }} - {{ $departmentName }} Department
                    </small>
                </h1>

            </div>
        </div>
        <div class="col-md-4">
            <div class="text-right">
                <div>
                    @php
                        $currentDayName = Carbon\Carbon::now()->format('l');
                    @endphp
                    <h4><span>Today: {{ $currentDayName }} , {{ date('d M, Y') }}</span></h4>
                </div>
                <div>
                    <ul class="p-0 list-unstyled mb-3">
                        @php
                            $userId = Auth::id();
                            $attendance = DB::table('attendances')
                                ->where('user_id', $userId)
                                ->whereDate('attendance_date', now()->toDateString())
                                ->first();
                            $userCheckedInToday = $attendance !== null;
                        @endphp
                        @if (!$userCheckedInToday)
                            @php
                                $checkInTime = \Carbon\Carbon::createFromTimeString('07:45:00');
                                $currentTime = \Carbon\Carbon::now();
                            @endphp

                            @if ($currentTime->greaterThanOrEqualTo($checkInTime))
                                <li class="nav-item checkinMain">
                                    <form action="{{ route('checkIn') }}" method="POST" id="checkin">
                                        @csrf
                                        <button type="submit" class="btn btn-success checkinBtn">Check In</button>
                                    </form>
                                </li>
                            @endif
                        @else
                            @php
                                $userCheckedOut = $attendance->check_out !== null;
                            @endphp

                            @if (!$userCheckedOut)
                                <li class="nav-item checkoutMain">
                                    <script>
                                        const userId = {{ auth()->user()->id }};
                                    </script>
                                    <form action="{{ route('checkOut') }}" method="POST" id="checkOut"
                                        data-already-checked-out="{{ auth()->check() && auth()->user()->hasCheckedOut ? 'true' : 'false' }}">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">Check Out</button>
                                    </form>
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @php
        $user = auth()->user();
    @endphp
    @if (Auth::user())
        @if (isAdmin($user))
            <div class="row">
                <div class="col-md-8  col-sm-12 col-xs-12">
                    <div class="cstScroll small-box bg-green py-4 px-3">
                        @if ($notices)
                            @foreach ($notices as $notice)
                                @php
                                    $NoticeDate = \Carbon\Carbon::parse($notice->created_at)->format('d M Y');
                                    $NoticeTime = \Carbon\Carbon::parse($notice->created_at)->format('g:i a');
                                    $limitedDescription = Str::words($notice->description, 20, '...');
                                @endphp
                                <ul class="timeline">
                                    <li class="time-label">
                                        <span class="bg-red">{{ $NoticeDate }}</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-envelope bg-blue"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fa fa-clock"></i> {{ $NoticeTime }}</span>

                                            <h3 class="timeline-header">
                                                <a
                                                    href="#"><strong>{{ strtoupper($notice->notice_type) }}</strong></a>
                                                {{ $notice->name }}
                                            </h3>

                                            <div class="timeline-body">
                                                {!! $limitedDescription !!}
                                            </div>
                                            <div class="timeline-footer">
                                                <button type="button" class="btn btn-info read-more-btn"
                                                    data-id="{{ $notice->id }}" data-toggle="modal"
                                                    data-target="#modal-info">
                                                    Read more
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            @endforeach
                        @endif
                        <div class="modal modal-info fade in" id="modal-info" tabindex="-1" role="dialog"
                            aria-labelledby="modal-infoLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <div class="noticeHeader">
                                            <div class="noticeName">
                                                <h4 class="modal-title" id="modal-infoLabel"></h4>
                                                <small class="noticeType"></small>
                                            </div>
                                            <div id="modal-date-time"></div>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="modal-description"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="ion ion-ios-book"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Employees</span>
                            <span class="info-box-number">{{ $activeEmployeeCount }}</span>
                        </div>
                    </div>
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Leave Requests</span>
                            @if ($leaveQuery)
                                <span class="info-box-number">{{ $leaveQuery->count() }}</span>
                            @else
                                <span class="info-box-number">0</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Approved Request</span>
                            @if ($leaveQuery)
                                <span
                                    class="info-box-number">{{ $leaveQuery->where('status', 'Approved')->count() }}</span>
                            @else
                                <span class="info-box-number">0</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pending Request</span>
                            @if ($leaveQuery)
                                <span class="info-box-number">{{ $leaveQuery->where('status', 'Pending')->count() }}</span>
                            @else
                                <span class="info-box-number">0</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="small-box bg-aqua py-4 px-3">
                        <div class="inner">
                            @php
                                $totalLeaves = 0;
                                foreach ($leaveTypes as $leaveType) {
                                    $totalLeaves += $leaveType->default_balance;
                                }
                            @endphp
                            <h4><strong>Total Leaves - {{ $totalLeaves }} </strong></h4>
                            @foreach ($leaveTypes as $leaveType)
                                <p class="m-0">
                                    {{ $leaveType->name }} - {{ $leaveType->default_balance }}
                                </p>
                            @endforeach
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="small-box bg-red py-4 px-3">
                        <div class="inner">
                            @php
                                $totalAvaile = 0;
                                foreach ($leaveTypes as $leaveType) {
                                    $totalAvaile += $availedLeaves->get($leaveType->id, 0);
                                }
                            @endphp
                            <h4><strong>Used Leave: {{ $totalAvaile }}</strong></h4>
                            @foreach ($leaveTypes as $leaveType)
                                <p class="m-0">{{ strtoupper($leaveType->name) }} -
                                    {{ $availedLeaves->get($leaveType->id, 0) }}</p>
                            @endforeach
                        </div>
                        <div class="icon">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="small-box bg-green py-4 px-3">
                        <div class="inner">
                            @php
                                $totalRemaining = 0;
                                foreach ($leaveTypes as $leaveType) {
                                    $remainingLeaveCount = $remainingLeaves->get($leaveType->id, 0);
                                    $totalRemaining += $remainingLeaveCount;
                                }
                            @endphp

                            <h4><strong>Remaining Leave: {{ $totalRemaining }}</strong></h4>

                            @foreach ($leaveTypes as $leaveType)
                                @php
                                    $remainingLeaveCount = $remainingLeaves->get($leaveType->id, 0);
                                @endphp
                                <p class="m-0">{{ strtoupper($leaveType->name) }} -
                                    {{ $remainingLeaveCount }}</p>
                            @endforeach
                        </div>

                        <div class="icon">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Team {{ $departmentName }}</h3>
                        </div>
                        <div class="box-body no-padding">
                            <ul class="users-list clearfix">
                                @foreach ($employeesByDepartment as $employee)
                                    @if ($employee->user_id !== auth()->id())
                                        <li>
                                            @if (!$employee->employee_img && $employee->gender === 'male')
                                                <img src="{{ asset('admin/images/male.jpg') }}" width="80"
                                                    height="80" alt="User Image">
                                            @elseif(!$employee->employee_img && $employee->gender === 'female')
                                                <img src="{{ asset('admin/images/female.png') }}" width="80"
                                                    height="80" alt="User Image">
                                            @else
                                                <img src="{{ asset('upload/' . $employee->employee_img) }}"
                                                    width="80" height="80" alt="User Image">
                                            @endif
                                            <a class="users-list-name"
                                                href="javascript:;">{{ $employee->user->name }}</a>
                                            <span class="users-list-date">{{ $departmentName }}</span>
                                            <span
                                                class="users-list-date">{{ optional($employee->designation)->designation_name }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="box-footer text-center">
                            <a href="javascript:void(0)" class="uppercase">View All Users</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Up Coming Holidays</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Holidays</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($holidays->isNotEmpty())
                                            @foreach ($holidays as $holiday)
                                                @php
                                                    $dateRange = $holiday->date;
                                                    [$startDate, $endDate] = explode(' - ', $dateRange);
                                                @endphp

                                                @if ($startDate == $endDate)
                                                    <tr>
                                                        <td>{{ $holiday->name }}</td>
                                                        <td><span
                                                                class="label label-success">{{ \Carbon\Carbon::parse($startDate)->format('d  M') }}</span>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>{{ $holiday->name }}</td>
                                                        <td>
                                                            <span class="label label-success">
                                                                {{ \Carbon\Carbon::parse($startDate)->format('d M') }}
                                                                -
                                                                {{ \Carbon\Carbon::parse($endDate)->format('d M') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Up Coming Birthday</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Birthday Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($userBirthdays->isNotEmpty())
                                            @foreach ($userBirthdays as $user)
                                                @if ($user->employee && $user->employee->date_of_birth)
                                                    <tr>
                                                        <td>{{ $user->name }}</td>
                                                        <td><span
                                                                class="label label-success">{{ $user->employee->date_of_birth->format('d M') }}</span>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-8  col-sm-12 col-xs-12">
                    <div class="cstScroll small-box bg-green py-4 px-3">
                        @if ($notices)
                            @foreach ($notices as $notice)
                                @php
                                    $NoticeDate = \Carbon\Carbon::parse($notice->created_at)->format('d M Y');
                                    $NoticeTime = \Carbon\Carbon::parse($notice->created_at)->format('g:i a');
                                    $limitedDescription = Str::words($notice->description, 20, '...');
                                @endphp
                                <ul class="timeline">
                                    <li class="time-label">
                                        <span class="bg-red">{{ $NoticeDate }}</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-envelope bg-blue"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fa fa-clock"></i> {{ $NoticeTime }}</span>

                                            <h3 class="timeline-header">
                                                <a
                                                    href="#"><strong>{{ strtoupper($notice->notice_type) }}</strong></a>
                                                {{ $notice->name }}
                                            </h3>

                                            <div class="timeline-body">
                                                {!! $limitedDescription !!}
                                            </div>
                                            <div class="timeline-footer">
                                                <button type="button" class="btn btn-info read-more-btn"
                                                    data-id="{{ $notice->id }}" data-toggle="modal"
                                                    data-target="#modal-info">
                                                    Read more
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            @endforeach
                        @endif
                        <div class="modal modal-info fade in" id="modal-info" tabindex="-1" role="dialog"
                            aria-labelledby="modal-infoLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <div class="noticeHeader">
                                            <div class="noticeName">
                                                <h4 class="modal-title" id="modal-infoLabel"></h4>
                                                <small class="noticeType"></small>
                                            </div>
                                            <div id="modal-date-time"></div>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="modal-description"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="small-box bg-aqua py-4 px-3">
                        <div class="inner">
                            @php
                                $totalLeaves = 0;
                                foreach ($leaveTypes as $leaveType) {
                                    $totalLeaves += $leaveType->default_balance;
                                }
                            @endphp
                            <h4><strong>Total Leaves - {{ $totalLeaves }} </strong></h4>
                            @foreach ($leaveTypes as $leaveType)
                                <span class="info-box-text">
                                    {{ $leaveType->name }} - {{ $leaveType->default_balance }}
                                </span>
                            @endforeach
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="small-box bg-red py-4 px-3">
                        <div class="inner">
                            @php
                                $totalAvaile = 0;
                                foreach ($leaveTypes as $leaveType) {
                                    // dd($leaveType);
                                    $totalAvaile += $availedLeaves->get($leaveType->id, 0);
                                }
                            @endphp
                            <h4><strong>Used Leave: {{ $totalAvaile }}</strong></h4>
                            @foreach ($leaveTypes as $leaveType)
                            
                                <span class="info-box-text">{{ strtoupper($leaveType->name) }} -
                                    {{ $availedLeaves->get($leaveType->id, 0) }}</span>
                            @endforeach
                        </div>
                        <div class="icon">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="small-box bg-green py-4 px-3">
                        <div class="inner">
                            @php
                                $totalRemaining = 0;
                                foreach ($leaveTypes as $leaveType) {
                                    $remainingLeaveCount = $remainingLeaves->get($leaveType->id, 0);
                                    $totalRemaining += $remainingLeaveCount;
                                }
                            @endphp

                            <h4><strong>Remaining Leave: {{ $totalRemaining }}</strong></h4>

                            @foreach ($leaveTypes as $leaveType)
                                @php
                                    $remainingLeaveCount = $remainingLeaves->get($leaveType->id, 0);
                                @endphp
                                <span class="info-box-text">{{ strtoupper($leaveType->name) }} -
                                    {{ $remainingLeaveCount }}</span>
                            @endforeach
                        </div>

                        <div class="icon">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Team {{ $departmentName }}</h3>
                        </div>
                        <div class="box-body no-padding">
                            <ul class="users-list clearfix">
                                <ul class="users-list clearfix">
                                    @foreach ($employeesByDepartment as $employee)
                                        @if ($employee->user_id !== auth()->id())
                                            <li>
                                                @if (!$employee->employee_img && $employee->gender === 'male')
                                                    <img src="{{ asset('admin/images/male.jpg') }}" width="80"
                                                        height="80" alt="User Image">
                                                @elseif(!$employee->employee_img && $employee->gender === 'female')
                                                    <img src="{{ asset('admin/images/female.png') }}" width="80"
                                                        height="80" alt="User Image">
                                                @else
                                                    <img src="{{ asset('upload/' . $employee->employee_img) }}"
                                                        width="80" height="80" alt="User Image">
                                                @endif
                                                <a class="users-list-name"
                                                    href="javascript:;">{{ $employee->user->name }}</a>
                                                <span class="users-list-date">{{ $departmentName }}</span>
                                                <span
                                                    class="users-list-date">{{ optional($employee->designation)->designation_name }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </ul>
                        </div>
                        <div class="box-footer text-center">
                            <a href="javascript:void(0)" class="uppercase">View All Users</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Up Coming Holidays</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Holidays</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($holidays->isNotEmpty())
                                            @foreach ($holidays as $holiday)
                                                @php
                                                    $dateRange = $holiday->date;
                                                    [$startDate, $endDate] = explode(' - ', $dateRange);
                                                @endphp

                                                @if ($startDate == $endDate)
                                                    <tr>
                                                        <td>{{ $holiday->name }}</td>
                                                        <td><span
                                                                class="label label-success">{{ \Carbon\Carbon::parse($startDate)->format('d  M') }}</span>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>{{ $holiday->name }}</td>
                                                        <td>
                                                            <span class="label label-success">
                                                                {{ \Carbon\Carbon::parse($startDate)->format('d M') }}
                                                                -
                                                                {{ \Carbon\Carbon::parse($endDate)->format('d M') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Up Coming Birthday</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Birthday Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($userBirthdays->isNotEmpty())
                                            @foreach ($userBirthdays as $user)
                                                @if ($user->employee && $user->employee->date_of_birth)
                                                    <tr>
                                                        <td>{{ $user->name }}</td>
                                                        <td><span
                                                                class="label label-success">{{ $user->employee->date_of_birth->format('d M') }}</span>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endsection
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.read-more-btn').on('click', function() {
            const noticeId = $(this).data('id');
            $.ajax({
                type: "GET",
                url: `/notices/${noticeId}`,
                success: function(data) {
                    // console.log(data.notice_type);
                    $('#modal-infoLabel').text(data.title);
                    $('.noticeType').text(data.notice_type);
                    $('#modal-date-time').text(data.notice_date + ' - ' + data.notice_time);
                    $('#modal-description').html(data.description);
                },
                error: function() {
                    $('#modal-infoLabel').text('Error');
                    $('#modal-description').html(
                        '<p>An error occurred while fetching the notice content.</p>');
                }
            });
        });
    });
</script>
@endpush
