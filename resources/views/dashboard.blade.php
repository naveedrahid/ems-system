@extends('masterLayout.app')
@section('main')
@section('page-title')
    Dashboard
@endsection
@section('page-content')
        <div class="col-lg-12 main-boxes">
            <div class="row top-boxes">
                <div class="col-lg-5 col-md-5  col-sm-12">
                    <!-- small box -->
                    <div class="small-box text-left boxes">
                        <div class="inner inner-box">
                            <h5 class="text-bold">Welcome back {{ auth()->user()->name }}</h5>
                            <small>
                                {{ $designation }} - {{ $departmentName }} Department
                            </small>
                            <p>Since your last login on the system, there were:</p>
                            <ul>
                                <a href="">
                                    <li>21 New Request</li>
                                </a>
                                <a href="">
                                    <li>15 New Report</li>
                                </a>
                                <a href="">
                                    <li>45 New Message</li>
                                </a>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <!-- small box -->
                    <div class="small-box text-left boxes work-status">
                        <div class="inner inner-box">
                            <h5 class="text-bold">Work Status</h5>
                            <div class="row progress-item">
                                <div class="col-lg-3 p-0">
                                    <strong>
                                        <p class="title">On Site</p>
                                    </strong>
                                </div>
                                <div class="col-lg-9 pl-1 remote">
                                    <div class="progress-group">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-primary" style="width: 80%"></div>
                                        </div>
                                    </div>
                                    <div class="total-employees">103 Employees</div>
                                </div>
                            </div>
                            <div class="row progress-item">
                                <div class="col-lg-3 p-0">
                                    <strong>
                                        <p class="title">Remote</p>
                                    </strong>
                                </div>
                                <div class="col-lg-9 pl-1 remote">
                                    <div class="progress-group">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-primary" style="width: 50%"></div>
                                        </div>
                                    </div>
                                    <div class="total-employees">50 Employees</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row deshboard-cards">
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
                            <h3 class="pt-2 mb-0">100</h3>
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
                                        <i class="fas fa-user-times"></i>
                                    </div>
                                </div>
                                <div class="col-lg-10 pl-3">
                                    <div class="detail">
                                        <p>Today's Absents</p>
                                    </div>
                                </div>
                            </div>
                            <h3 class="pt-2 mb-0">321</h3>
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
                            @if ($leaveQuery)
                                <h3 class="pt-2 mb-0">{{ $leaveQuery->count() }}</h3>
                            @else
                                <h3 class="pt-2 mb-0">0</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 pl-3">
                <div class="notice-board mb-3">
                    <div class="card-header">
                        <h4 class="card-title text-bold">Notice</h4>
                        <div class="card-tools">
                            <span class="badge badge-danger">2 Messages</span>
                        </div>
                    </div>
                    <div class="timeline mt-3">
                        @if ($notices->count() > 0)
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
                                                data-id="{{ $notice->id }}" data-toggle="modal"
                                                data-target="#modal-info">
                                                Read more
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>
                        @endif
                    </div>
                </div>

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
                                                        <tr>
                                                            <td>{{ $user->name }}</td>
                                                            <td>
                                                                <p class="badge badge-success text-white">
                                                                    {{ $user->employee->date_of_birth->format('d M') }}
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
                </div>
            </div>
            <div class="col-lg-4 p-0">
                <div class="col-lg-12">
                    <div class="small-box">
                        <div class="card-header">
                            <h4 class="card-title text-bold">Today's Birthday</h4>
                            <div class="card-tools">
                                <!-- <span class="badge badge-danger">8 New Members</span> -->
                            </div>
                        </div>
                        <div class="row align-items-center p-3">
                            <div class="col-lg-12 p-0">
                                <div class="user-panel mt-1">
                                    <div class="image">
                                        <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                    </div>
                                    <div class="info d-block">
                                        <h5 href="#" class="text-bold d-block m-0">Mubeen Dewani
                                        </h5>
                                        <p class="text-primary">Birthday Today</p>
                                    </div>
                                </div>
                                <div class="col-lg-12 pt-2 d-flex justify-content-end p-0">
                                    <button type="button" class="btn btn-primary"></i> Wish Him</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <div class="small-box Manage-boxes">
                                <div class="card-header">
                                    <h4 class="card-title text-bold">Leave</h4>
                                    <div class="card-tools">
                                        <span class="badge badge-danger">8 On leave</span>
                                    </div>
                                </div>
                                <div class="row leaves align-items-center p-3">
                                    <div class="col-lg-12 p-0">
                                        <div class="user-panel mt-1 mb-2">
                                            <div class="image">
                                                <img src="dist/img/user2-160x160.jpg" class="img-circle"
                                                    alt="User Image">
                                            </div>
                                            <div class="content">
                                                <div class="info d-block">
                                                    <h5 href="#" class=" d-block m-0">Mubeen Dewani
                                                    </h5>
                                                    <p>Annual Leave</p>
                                                    <p>1 May 2024 - 30 May 2024</p>
                                                </div>
                                                <div class="process-1 text-end">
                                                    <p></i> Padding</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="user-panel mt-1 mb-2">
                                            <div class="image">
                                                <img src="dist/img/user2-160x160.jpg" class="img-circle"
                                                    alt="User Image">
                                            </div>
                                            <div class="content">
                                                <div class="info d-block">
                                                    <h5 href="#" class=" d-block m-0">Benedict
                                                        Thomson</h5>
                                                    <p>Sick Leave</p>
                                                    <p>1 May 2024 - 30 May 2024</p>
                                                </div>
                                                <div class="process-2 text-end">
                                                    <p></i> Approved</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="user-panel mt-1 mb-2">
                                            <div class="image">
                                                <img src="dist/img/user2-160x160.jpg" class="img-circle"
                                                    alt="User Image">
                                            </div>
                                            <div class="content">
                                                <div class="info d-block">
                                                    <h5 href="#" class=" d-block m-0">Alice Nolan
                                                    </h5>
                                                    <p>Annual Leave</p>
                                                    <p>1 May 2024 - 30 May 2024</p>
                                                </div>
                                                <div class="process-2 text-end">
                                                    <p></i>Approved</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {

        let clockInterval;
        let isRunning = false;
        let startTime;

        function updateClock() {
            const clock = $('#clock');
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            clock.html(hours + ':' + minutes + ':' + seconds);
        }

        function startClock() {
            startTime = new Date().getTime();
            localStorage.setItem('startTime', startTime);
            clockInterval = setInterval(updateClock, 1000);
            // $('#start-time-btn').text('Pause Time');
            isRunning = true;
        }

        function stopClock() {
            clearInterval(clockInterval);
            // $('#start-time-btn').text('Resume Time');
            isRunning = false;
        }

        const storedStartTime = localStorage.getItem('startTime');
        if (storedStartTime) {
            startTime = parseInt(storedStartTime, 10);
            const elapsedTime = new Date().getTime() - startTime;
            const secondsElapsed = Math.floor(elapsedTime / 1000);
            const secondsLeft = 86400 - secondsElapsed;
            if (secondsLeft > 0) {
                startClock();
            } else {
                stopClock();
                localStorage.removeItem('startTime');
            }
        }

        $('#start-time-form').submit(function(e) {
            e.preventDefault();

            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                })
                .done(function(response) {
                    window.location.reload();
                    startClock();
                })
                .fail(function(err) {
                    console.error(err);
                    toastr.error('An error occurred while starting the time.');
                });
        });

        // Handle end time button click
        $('#end-time-form').submit(function(e) {
            e.preventDefault();

            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                    url: url,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                })
                .done(function(response) {
                    window.location.reload();
                    stopClock();
                    localStorage.removeItem('startTime');
                })
                .fail(function(err) {
                    console.error(err);
                    toastr.error('An error occurred while stopping the time.');
                });
        });
        updateClock();
    });
</script>
@endpush
