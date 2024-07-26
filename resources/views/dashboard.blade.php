@extends('masterLayout.app')
@section('main')
@section('page-title')
    @php
        $user = auth()->user();
    @endphp
    @if (isAdmin($user))
        {{ 'Admin Dashboard' }}
    @else
        {{ 'Employee Dashboard' }}
    @endif
@endsection
@section('page-content')
    @php
        $user = auth()->user();
    @endphp
    <div class="col-lg-12 main-boxes">
        <div class="row top-boxes">
            <div class="{{ isAdmin($user) ? 'col-lg-5 col-md-5  col-sm-12' : 'col-lg-6 col-md-6  col-sm-12' }} ">
                <!-- small box -->
                <div class="small-box text-left boxes">
                    <div class="inner inner-box">
                        <h5 class="text-bold">Welcome back {{ auth()->user()->name }}</h5>
                        <small>
                            {{ $designation }} - {{ $departmentName }} Department
                        </small>
                    </div>
                </div>
            </div>
            <div class="{{ isAdmin($user) ? 'col-lg-3 col-md-3 col-sm-12' : 'col-lg-6 col-md-6  col-sm-12' }} ">
                <!-- small box -->
                <div class="small-box text-left boxes work-status">
                    <div class="inner inner-box">
                        <h5 class="text-bold">{{ isAdmin($user) ? 'Work Status' : "Today's Attandance" }}</h5>
                        <div class="row progress-item">
                            <div class="col-lg-3 p-0">
                                <strong>
                                    <p class="title">{{ isAdmin($user) ? 'On Site' : 'Total Present' }}</p>
                                </strong>
                            </div>
                            @php
                                $onsiteJobTypeCount = $activeEmployees
                                    ->filter(function ($employee) {
                                        return $employee->job_type === 'onsite';
                                    })
                                    ->count();

                                $remoteJobTypeCount = $activeEmployees
                                    ->filter(function ($employee) {
                                        return $employee->job_type === 'remote';
                                    })
                                    ->count();
                            @endphp
                            {{-- @dd($onsiteJobType) --}}
                            <div class="col-lg-9 pl-1 remote">
                                <div class="progress-group">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary"
                                            style="width: {{ isAdmin($user) ? $onsiteJobTypeCount : $attendanceCount->count() }}%">
                                        </div>
                                    </div>
                                </div>
                                <div class="total-employees">
                                    {{ isAdmin($user) ? $onsiteJobTypeCount : $attendanceCount->count() }} Employees</div>
                            </div>
                        </div>
                        <div class="row progress-item">
                            <div class="col-lg-3 p-0">
                                <strong>
                                    <p class="title">{{ isAdmin($user) ? 'Remote' : 'Total Absent' }}</p>
                                </strong>
                            </div>
                            <div class="col-lg-9 pl-1 remote">
                                @php
                                    $totalAbsent = $activeEmployeeCount - $attendanceCount->count();
                                @endphp
                                <div class="progress-group">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary"
                                            style="width: {{ isAdmin($user) ? $remoteJobTypeCount : $totalAbsent }}%">
                                        </div>
                                    </div>
                                </div>
                                <div class="total-employees">{{ isAdmin($user) ? $remoteJobTypeCount : $totalAbsent }}
                                    Employees</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (isAdmin($user))
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="small-box text-left boxes announcement">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="inner">
                                    <h5 class="text-bold">Announcement</h5>
                                    <p>Since your last login on the login on </p>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="icon d-flex justify-content-center">
                                    <img src="dist/img/announcement.png" width="110" height="100">
                                </div>
                            </div>
                            <div class="col-lg-5 mt-2">
                                <a href="{{ route('notices.index') }}" class="btn btn-block btn-primary">Create
                                    New</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row deshboard-cards">
            @if (isAdmin($user))
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <!-- small box -->
                    <div class="small-box">
                        <div class="inner">
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-2">
                                    <div class="deshboard-cards-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col-lg-10 pl-3">
                                    <div class="detail">
                                        <p>Total Employee</p>
                                    </div>
                                </div>
                            </div>
                            <h3 class="pt-2 mb-0">{{ $activeEmployeeCount }} </h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <!-- small box -->
                    <div class="small-box">
                        <div class="inner">
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-2">
                                    <div class="deshboard-cards-icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                </div>
                                <div class="col-lg-10 pl-3">
                                    <div class="detail">
                                        <p>Today's Presents</p>
                                    </div>
                                </div>
                            </div>
                            <h3 class="pt-2 mb-0">{{ $attendanceCount->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <!-- small box -->
                    @php
                        $pendingReq = $leaveQuery->where('status', 'Pending')->count();
                    @endphp
                    <div class="small-box">
                        <div class="inner">
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-2">
                                    <div class="deshboard-cards-icon">
                                        <i class="fas fa-user-times"></i>
                                    </div>
                                </div>
                                <div class="col-lg-10 pl-3">
                                    <div class="detail">
                                        <p>Today's Absents</p>
                                    </div>
                                </div>
                            </div>
                            <h3 class="pt-2 mb-0">{{ $activeEmployeeCount - $attendanceCount->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <!-- small box -->
                    <div class="small-box">
                        <div class="inner">
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-2">
                                    <div class="deshboard-cards-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div class="col-lg-10 pl-3">
                                    <div class="detail">
                                        <p>Today's Leave</p>
                                    </div>
                                </div>
                            </div>
                            @if ($pendingReq)
                                <h3 class="pt-2 mb-0">{{ $pendingReq }}</h3>
                            @else
                                <h3 class="pt-2 mb-0">0</h3>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-12">
                    <div class="row deshboard-cards">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <!-- small box -->
                            <div class="small-box">
                                <div class="inner leave-managment">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-lg-1">
                                            <div class="deshboard-cards-icon">
                                                <i class="fas fa-calendar-check" style="font-size: 25px;"></i>
                                            </div>
                                        </div>
                                        @php
                                            $totalLeaves = 0;
                                            foreach ($leaveTypes as $leaveType) {
                                                $totalLeaves += $leaveType->default_balance;
                                            }
                                        @endphp
                                        <h4 class="card-title text-bold leave-managment-title">Total Leaves -
                                            {{ $totalLeaves }}</h4><br>
                                        <div class="col-lg-10">
                                            <div class="detail mt-2">
                                                @foreach ($leaveTypes as $leaveType)
                                                    <p class=" text-bold">
                                                        {{ $leaveType->name }} - {{ $leaveType->default_balance }}
                                                    </p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <!-- small box -->
                            <div class="small-box">
                                <div class="inner leave-managment">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-lg-1">
                                            <div class="deshboard-cards-icon">
                                                <i class="fas fa-history" style="font-size: 25px;"></i>
                                            </div>
                                        </div>
                                        @php
                                            $totalAvaile = 0;
                                            foreach ($leaveTypes as $leaveType) {
                                                // dd($leaveType);
                                                $totalAvaile += $availedLeaves->get($leaveType->id, 0);
                                            }
                                        @endphp
                                        <h4 class="card-title text-bold leave-managment-title">Used Leave -
                                            {{ $totalAvaile }}</h4><br>
                                        <div class="col-lg-10">
                                            <div class="detail mt-2">
                                                @foreach ($leaveTypes as $leaveType)
                                                    <p class=" text-bold">{{ strtoupper($leaveType->name) }} -
                                                        {{ $availedLeaves->get($leaveType->id, 0) }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <!-- small box -->
                            <div class="small-box">
                                <div class="inner leave-managment">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-lg-1">
                                            <div class="deshboard-cards-icon">
                                                <i class="fas fa-balance-scale" style="font-size: 25px;"></i>
                                            </div>
                                        </div>
                                        @php
                                            $totalRemaining = 0;
                                            foreach ($leaveTypes as $leaveType) {
                                                $remainingLeaveCount = $remainingLeaves->get($leaveType->id, 0);
                                                $totalRemaining += $remainingLeaveCount;
                                            }
                                        @endphp
                                        <h4 class="card-title text-bold leave-managment-title">Remaining Leave -
                                            {{ $totalRemaining }}</h4>
                                        <br>
                                        <div class="col-lg-10">
                                            <div class="detail mt-2">
                                                @foreach ($leaveTypes as $leaveType)
                                                    @php
                                                        $remainingLeaveCount = $remainingLeaves->get($leaveType->id, 0);
                                                    @endphp
                                                    <p class=" text-bold">{{ strtoupper($leaveType->name) }} -
                                                        {{ $remainingLeaveCount }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 pl-3">
            @if ($notices->count() > 0)
                <div class="notice-board mb-3">
                    <div class="card-header">
                        <h4 class="card-title text-bold">Notice</h4>
                        <div class="card-tools">
                            <span class="badge badge-danger">2 Messages</span>
                        </div>
                    </div>
                    <div class="timeline mt-3">
                        @foreach ($notices as $notice)
                            @php
                                $NoticeDate = \Carbon\Carbon::parse($notice->created_at)->format('d M Y');
                                $NoticeTime = \Carbon\Carbon::parse($notice->created_at)->format('g:i a');
                            @endphp
                            <div class="time-label">
                                <span class="bg-red">{{ $NoticeDate }}</span>
                            </div>
                            <div>
                                <i class="fas fa-envelope bg-blue"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{ $NoticeTime }}</span>
                                    <h3 class="timeline-header"><a href="#">{{ $notice->name }}</a>
                                        {{ strtoupper($notice->notice_type) }}
                                    </h3>

                                    <div class="timeline-body">
                                        {!! $notice->description !!}
                                    </div>
                                    <div class="timeline-footer">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            data-id="{{ $notice->id }}" data-toggle="modal" data-target="#modal-info">
                                            Read more
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card small-box menual-update">
                        <div class="card-header">
                            <h4 class="text-bold card-title">Up Coming Holidays</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Holidays</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="menual-update-1">
                                        @if ($holidays->isNotEmpty())
                                            @foreach ($holidays as $holiday)
                                                @php
                                                    $dateRange = $holiday->date;
                                                    [$startDate, $endDate] = explode(' - ', $dateRange);
                                                @endphp

                                                @if ($startDate == $endDate)
                                                    <tr>
                                                        <td>{{ $holiday->name }}</td>
                                                        <td>
                                                            <p class="badge badge-success text-white">
                                                                {{ \Carbon\Carbon::parse($startDate)->format('d  M') }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>{{ $holiday->name }}</td>
                                                        <td>
                                                            <p class="badge badge-success text-white">
                                                                {{ \Carbon\Carbon::parse($startDate)->format('d M') }}
                                                                -
                                                                {{ \Carbon\Carbon::parse($endDate)->format('d M') }}
                                                            </p>
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
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card small-box menual-update">
                        <div class="card-header">
                            <h4 class="text-bold card-title">Up Coming Birthday</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Birthday Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="menual-update-1">
                                        @if ($userBirthdays->isNotEmpty())
                                            @foreach ($userBirthdays as $user)
                                                @if ($user->employee && $user->employee->date_of_birth)
                                                    @php
                                                        $currentDate = date('m-d');
                                                        $userBirthday = date(
                                                            'm-d',
                                                            strtotime($user->employee->date_of_birth),
                                                        );
                                                    @endphp
                                                    @if ($currentDate !== $userBirthday)
                                                        <tr>
                                                            <td>{{ $user->name }}</td>
                                                            <td>
                                                                <p class="badge badge-success text-white">
                                                                    {{ date('d M', strtotime($user->employee->date_of_birth)) }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    @endif
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
        </div>
        <div class="col-lg-4 p-0">
            <div class="col-lg-12">
                @php
                    $today = now()->format('m-d');

                    // Query to get employees and their associated users
                    $todayBirthdays = DB::table('employees')
                        ->join('users', 'employees.user_id', '=', 'users.id')
                        ->select(
                            'employees.date_of_birth',
                            'employees.employee_img',
                            'employees.gender',
                            'employees.user_id',
                            'users.name',
                        )
                        ->whereRaw("DATE_FORMAT(employees.date_of_birth, '%m-%d') = ?", [$today])
                        ->get();
                @endphp

                @if ($todayBirthdays)
                    @foreach ($todayBirthdays as $todayBirthday)
                        <div class="small-box">
                            <div class="card-header">
                                <h4 class="card-title text-bold">Today's Birthday</h4>
                            </div>
                            <div class="row align-items-center p-3">
                                <div class="col-lg-12 p-0">
                                    <div class="user-panel mt-1">
                                        <div class="image">
                                            @if (!$todayBirthday->employee_img && $todayBirthday->gender === 'male')
                                                <img src="{{ asset('admin/images/male.jpg') }}" width="80"
                                                    height="80" class="img-circle" alt="User Image">
                                            @elseif(!$todayBirthday->employee_img && $todayBirthday->gender === 'female')
                                                <img src="{{ asset('admin/images/female.png') }}" width="80"
                                                    height="80" class="img-circle" alt="User Image">
                                            @else
                                                <img src="{{ asset('upload/' . $todayBirthday->employee_img) }}"
                                                    alt="Employee Image" width="80" height="80"
                                                    class="profile-user-img img-responsive img-circle user-image">
                                            @endif
                                        </div>
                                        <div class="info d-block">
                                            <h5 href="#" class="text-bold d-block m-0 d-flex align-items-center">
                                                {{ $todayBirthday->name }} <img
                                                    src="{{ asset('admin/images/cake.png') }}" class="img-fluid ml-4"
                                                    alt="" style="width: 24px !important;">
                                            </h5>
                                            <p class="text-primary">Birthday Today </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pt-2 d-flex justify-content-end p-0">
                                        <button type="button" class="btn btn-primary"></i> Wish Him</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card small-box">
                            <div class="card-header">
                                <h4 class="card-title text-bold">{{ $departmentName }}</h4>
                                <div class="card-tools">
                                    <a href="{{ route('employees.view') }}">
                                        <span class="badge badge-danger">Team</span>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row web-development-team">
                                    @foreach ($employeesByDepartment as $employee)
                                        @if ($employee->user_id !== auth()->id())
                                            <div
                                                class="col-lg-4 col-md-4  col-sm-6 web-development-member pt-2 text-center">
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
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if (isAdmin($user))
                            <div class="small-box Manage-boxes">
                                <div class="card-header">
                                    <h4 class="card-title text-bold">Leave</h4>
                                    <div class="card-tools">
                                        @if ($pendingReq)
                                            <span class="badge badge-danger">{{ $pendingReq }} On leave</span>
                                        @else
                                            <span class="badge badge-danger">no leave</span>
                                        @endif
                                    </div>
                                </div>
                                {{-- @php
                                    $approvedLeaveToday = $leaveQuery->where('status', 'Approved')->first();
                                @endphp --}}
                                {{-- @dd($leaveQuery) --}}
                                @if ($leaveQuery)
                                    <div class="row leaves align-items-center p-3">
                                        <div class="col-lg-12 p-0">
                                            @foreach ($leaveQuery as $leave)
                                                {{-- @dd($leave) --}}
                                                <div class="user-panel mt-1 mb-2">
                                                    <div class="image">
                                                        @if (!optional($leave->employee)->employee_img && optional($leave->employee)->gender === 'male')
                                                            <img src="{{ asset('admin/images/male.jpg') }}"
                                                                width="80" height="80" class="img-circle"
                                                                alt="User Image">
                                                        @elseif(!optional($leave->employee)->employee_img && optional($leave->employee)->gender === 'female')
                                                            <img src="{{ asset('admin/images/female.png') }}"
                                                                width="80" height="80" class="img-circle"
                                                                alt="User Image">
                                                        @else
                                                            <img src="{{ asset('upload/' . optional($leave->employee)->employee_img) }}"
                                                                alt="Employee Image" width="80" height="80"
                                                                class="profile-user-img img-responsive img-circle user-image">
                                                        @endif

                                                        {{-- <img src="dist/img/user2-160x160.jpg" class="img-circle"
                                                            alt="User Image"> --}}
                                                    </div>
                                                    <div class="content">
                                                        <div class="info d-block">
                                                            <h5 class="d-block m-0">{{ $leave->user->name }}</h5>

                                                            <p>{{ $leave->leaveType->name }}</p>
                                                            <p>
                                                                {{ date('d M Y', strtotime($leave->start_date)) }} -
                                                                {{ date('d M Y', strtotime($leave->end_date)) }}
                                                            </p>
                                                        </div>
                                                        <a href="{{ route('leave-applications.index') }}">
                                                            <div
                                                                class="process-{{ $leave->status != 'Approved' ? '1' : '2' }} text-end">
                                                                <p> {{ $leave->status }}</p>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
