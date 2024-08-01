<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ config('app.name', 'Laravel') }} | @yield('page-title')</title>
    {{-- <link rel="icon" type="image/png" href="{{ asset('Pixelz360.png') }}"> --}}
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css"> -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.8/sweetalert2.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/2.0.8/dataTables.bootstrap5.min.css"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,500,600,700,800" rel="stylesheet">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('admin/css/main.css') }}">
    <style>
        div#timerd button {
            background: transparent;
            border: none;
            padding: 0;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="javascript:;" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->

            <ul class="navbar-nav d-flex align-items-center ml-auto">
                <li class="daily-timing d-flex align-items-center">
                    <div class="daily-time">
                        @php
                            $currentDate = now()->toDateString();
                            $userId = auth()->user()->id;

                            $attendance = DB::table('attendances')
                                ->whereDate('attendance_date', $currentDate)
                                ->where('user_id', $userId)
                                ->select('check_in', 'check_out')
                                ->first();

                            // $timeLog = DB::table('time_logs')
                            //     ->whereDate('created_at', $currentDate)
                            //     ->where('user_id', $userId)
                            //     ->orderBy('created_at', 'desc')
                            //     ->first();

                            // $timeLogDurations = DB::table('time_logs')
                            //     ->whereDate('created_at', $currentDate)
                            //     ->where('user_id', $userId)
                            //     ->pluck('duration');

                            // $timeLogId = DB::table('time_logs')
                            //     ->whereDate('created_at', now()->toDateString())
                            //     ->where('user_id', auth()->id())
                            //     ->pluck('id')
                            //     ->first();

                            // $totalDurationInSeconds = $timeLogDurations
                            //     ->map(function ($duration) {
                            //         $parts = explode(':', $duration);
                            //         $hours = isset($parts[0]) ? (int) $parts[0] : 0;
                            //         $minutes = isset($parts[1]) ? (int) $parts[1] : 0;
                            //         $seconds = isset($parts[2]) ? (int) $parts[2] : 0;

                            //         return $hours * 3600 + $minutes * 60 + $seconds;
                            //     })
                            //     ->sum();

                            // $hours = floor($totalDurationInSeconds / 3600);
                            // $minutes = floor(($totalDurationInSeconds % 3600) / 60);
                            // $seconds = $totalDurationInSeconds % 60;
                            // $totalDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                        @endphp

                        {{-- <div class="timer" id="timerd">
                            <i class="fas fa-stopwatch"></i>
                            @if ($attendance && $attendance->check_in)
                                @if ($attendance->check_out === null)
                                    <div id="timer">00:00:00</div>
                                @else
                                    <div id="total">{{ $totalDuration }}</div>
                                @endif
                            @endif

                            @if ($attendance && $attendance->check_in && $attendance->check_out === null)
                                <button
                                    id="{{ !$timeLog || ($timeLog && !$timeLog->start_time) ? 'start-time-btn' : 'pause-time-btn' }}"
                                    class="{{ !$timeLog || ($timeLog && $timeLog->end_time) ? 'play-time' : 'pause-time' }}">
                                    <i
                                        class="fas fa-{{ !$timeLog || ($timeLog && $timeLog->end_time) ? 'play' : 'pause' }}"></i>
                                </button>
                            @endif
                        </div> --}}
                    </div>
                    <div class="checkin">
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
                                <form action="{{ route('checkIn') }}" method="POST" id="checkin">
                                    @csrf
                                    <button type="submit" class="btn btn-primary checkinBtn">Check In</button>
                                </form>
                            @endif
                        @else
                            @php
                                $userCheckedOut = $attendance->check_out !== null;
                            @endphp

                            @if (!$userCheckedOut)
                                <script>
                                    const userId = {{ auth()->user()->id }};
                                </script>
                                <form action="{{ route('checkOut') }}" method="POST" id="checkOut"
                                    data-already-checked-out="{{ auth()->check() && auth()->user()->hasCheckedOut ? 'true' : 'false' }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger checkOutBtn">Check Out</button>
                                </form>
                            @endif
                        @endif
                        {{-- <button class="btn btn-primary">Check In</button>
                        <button onclick="checkOut()" class="btn btn-danger">Check Out</button> --}}
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="javascript:;">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="javascript:;" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user1-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user8-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i
                                                class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user3-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i
                                                class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="javascript:;">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary">
            <div class="logo"
                style="width: 100%; height: 57.10px; border-bottom: 1px solid #FFF; display: flex; justify-content: center; align-items: center;">
                <a href="{{ route('home') }}" style="color:#fff;">
                    training4employment
                </a>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
                    <div class="image">
                        @if (!optional(auth()->user()->employee)->employee_img && optional(auth()->user()->employee)->gender === 'male')
                            <img src="{{ asset('admin/images/male.jpg') }}" width="80" height="80"
                                alt="User Image">
                        @elseif(!optional(auth()->user()->employee)->employee_img && optional(auth()->user()->employee)->gender === 'female')
                            <img src="{{ asset('admin/images/female.png') }}" width="80" height="80"
                                alt="User Image">
                        @else
                            <img src="{{ asset('upload/' . optional(auth()->user()->employee)->employee_img) }}"
                                alt="Employee Image" class="profile-user-img img-responsive img-circle user-image">
                        @endif
                    </div>
                    <div class="info">
                        <a href="javascript:;" class="d-block"> Hello, {{ auth()->user()->name }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                @php
                    $user = auth()->user();
                @endphp
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                            with font-awesome or any other icon font library -->
                        <li class="nav-item has-treeview menu-open">
                            <a href="{{ route('home') }}" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Employees
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if (isAdmin($user))
                                    <li class="nav-item">
                                        <a href="{{ route('employees.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add Employee</p>
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="{{ route('employees.view') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Employee</p>
                                    </a>
                                </li>
                                @if (isAdmin($user))
                                <li class="nav-item">
                                    <a href="{{ route('documents.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Employee Documents</p>
                                    </a>
                                </li>
                                    <li class="nav-item">
                                        <a href="{{ route('bank-details.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Employee Bank Detail</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        @if (isAdmin($user))
                            <li class="nav-item has-treeview">
                                <a href="javascript:;" class="nav-link">
                                    <i class="nav-icon far fa-building"></i>
                                    <p>
                                        Departments
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    {{-- <li class="nav-item">
                                        <a href="{{ route('department.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add Department</p>
                                        </a>
                                    </li> --}}
                                    <li class="nav-item">
                                        <a href="{{ route('department.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>View Department</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="javascript:;" class="nav-link">
                                    <i class="nav-icon fas fa-level-up-alt"></i>
                                    <p>
                                        Desginations
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('designation.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add Desginations</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('designation.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>View Desginations</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="javascript:;" class="nav-link">
                                    <i class="nav-icon fas fa-exchange-alt"></i>
                                    <p>
                                        Shifts
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('shifts.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add New Shifts</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('shifts.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>View Shifts</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li class="nav-item has-treeview">
                            <a href="javascript:;" class="nav-link">
                                <i class="nav-icon far fa-newspaper"></i>
                                <p>
                                    Complaint Management
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('complaints.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add New Complaint</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('complaints.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Complaint</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @if (isAdmin($user))
                            <li class="nav-item has-treeview">
                                <a href="javascript:;" class="nav-link">
                                    <i class="nav-icon fas fa-medal"></i>
                                    <p>
                                        Awards Management
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('awards.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add New Awards</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('awards.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>View Awards</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="javascript:;" class="nav-link">
                                    <i class="nav-icon far fa-clipboard"></i>
                                    <p>
                                        Notice Board
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('notices.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add New Notice</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('notices.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>View Notice</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li class="nav-item has-treeview">
                            <a href="javascript:;" class="nav-link">
                                <i class="nav-icon fas fa-receipt"></i>
                                <p>
                                    Pay Slips
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="javascript:;" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create Payslips</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:;" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Payslips</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="javascript:;" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                <p>
                                    Attendance
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if (isAdmin($user))
                                    <li class="nav-item">
                                        <a href="{{ route('attendance.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add Attendance</p>
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="{{ route('daily.report') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Daily Attendance</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('attendance') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Month Wise Attedance</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('time-logs.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Times Log</p>
                                    </a>
                                </li>
                                @if (isAdmin($user))
                                    <li class="nav-item">
                                        <a href="{{ route('attendance.log') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Attedance log</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('attendance.report') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Attedance Report</p>
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="{{ route('attendance.filter') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Attedance Filter</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @if (isAdmin($user))
                            <li class="nav-item has-treeview">
                                <a href="javascript:;" class="nav-link">
                                    <i class="nav-icon fas fa-briefcase"></i>
                                    <p>
                                        Jobs Managment
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('jobs.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add new jobs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('jobs.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>View Jobs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('schedule-interviews.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Create Schedule Interviews</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('schedule-interviews.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Schedule Interviews</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('interviewer-remarks.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Interviewer Remarks
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('candidates.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>View candidates</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('job-offers.index')}}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Job Offer
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link">
                                    <i class="nav-icon far fa-credit-card"></i>
                                    <p>
                                        Payroll
                                        <span class="right badge badge-success">Coming Soon</span>
                                    </p>
                                </a>
                            </li>
                        @endif;
                        <li class="nav-item has-treeview">
                            <a href="javascript:;" class="nav-link">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>
                                    Leaves Managment
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('leave-applications.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Request A Leave</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('leave-applications.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>All Leave</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('holidays.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Holidays
                                        </p>
                                    </a>
                                </li>
                                @if (isAdmin($user))
                                    <li class="nav-item">
                                        <a href="{{ route('leave-types.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Leave Types</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="javascript:;" class="nav-link">
                                <i class="nav-icon fas fa-file-signature"></i>
                                <p>
                                    Policy Documents
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ asset('admin/document/Leave-Policy-Permanent-Employees-2022.pdf') }}"
                                        target="_blank" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Leave Policy</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:;" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Medical Policy</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:;" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Employee Policy</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="javascript:;" class="nav-link">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>
                                    Account Setting
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('employees.profile', auth()->user()->id) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Profile</p>
                                    </a>
                                </li>
                                @if (isAdmin($user))
                                    <li class="nav-item">
                                        <a href="{{ route('users.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('roles.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Roles</p>
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="javascript:;" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Change Password</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="javascript:;" class="nav-link">
                                <p>
                                    <form action="{{ route('logoutUser') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-default btn-flat">Sign Out</button>
                                    </form>
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        @yield('main')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-lg-6 pl-3">
                            <h1 class="m-0 text-dark" style="font-weight: 600;">@yield('page-title')</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
                @yield('page-content')
            </section>
        </div>
    </div>
    <!-- ./wrapper -->
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2014 <a href="https://pixelz360.com.au/">training4employment</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.0.4
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="{{ asset('admin/dist/js/app.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script> -->
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <!-- ChartJS -->
    <!-- <script src="plugins/chart.js/Chart.min.js"></script> -->
    <!-- Sparkline -->
    <!-- <script src="plugins/sparklines/sparkline.js"></script> -->
    <!-- JQVMap -->
    <!-- <script src="plugins/jqvmap/jquery.vmap.min.js"></script> -->
    <!-- <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script> -->
    <!-- jQuery Knob Chart -->
    <!-- <script src="plugins/jquery-knob/jquery.knob.min.js"></script> -->
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.0/moment.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <!-- <script src="plugins/summernote/summernote-bs4.min.js"></script> -->
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- <script src="dist/js/pages/dashboard.js"></script> -->
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.8/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="{{ asset('admin/js/customAjax.js') }}"></script>
    <script>
        toastr.options = {
            "closeButton": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        }
    </script>
    <script>
        // $(document).ready(function() {
        //     let timerInterval;
        //     let isRunning = false;
        //     let startTime;
        //     let elapsedTime = 0;
        //     const token = $('meta[name="csrf-token"]').attr('content');
        //     let timeLogId;

        //     function updateTimer() {
        //         const now = new Date().getTime();
        //         elapsedTime = now - startTime;
        //         const totalSeconds = Math.floor(elapsedTime / 1000);
        //         const hours = Math.floor(totalSeconds / 3600).toString().padStart(2, '0');
        //         const minutes = Math.floor((totalSeconds % 3600) / 60).toString().padStart(2, '0');
        //         const seconds = (totalSeconds % 60).toString().padStart(2, '0');
        //         $('#timer').html(hours + ':' + minutes + ':' + seconds);
        //     }

        //     function startTimer() {
        //         startTime = new Date().getTime() - elapsedTime;
        //         localStorage.setItem('startTime', startTime);
        //         timerInterval = setInterval(updateTimer, 1000);
        //         isRunning = true;
        //         updateButtonState('pause');
        //     }

        //     function stopTimer() {
        //         clearInterval(timerInterval);
        //         isRunning = false;
        //         updateButtonState('play');
        //     }

        //     function updateButtonState(state) {
        //         const button = $('#start-time-btn, #pause-time-btn');
        //         if (state === 'pause') {
        //             button.attr('id', 'pause-time-btn').removeClass('play-time').addClass('pause-time')
        //                 .html(
        //                     '<i class="fas fa-pause"></i>');
        //         } else {
        //             button.attr('id', 'start-time-btn').removeClass('pause-time').addClass('play-time')
        //                 .html(
        //                     '<i class="fas fa-play"></i>');
        //         }
        //     }

        //     function sendStartTimeRequest() {
        //         $.ajax({
        //                 url: '{{ route('start.time') }}',
        //                 method: 'POST',
        //                 headers: {
        //                     'X-CSRF-TOKEN': token
        //                 },
        //             })
        //             .done(function(response) {
        //                 startTimer();
        //                 timeLogId = response.time_log_id; // Update timeLogId with the response
        //                 localStorage.setItem('timeLogId', timeLogId);
        //             })
        //             .fail(function(err) {
        //                 console.error(err);
        //                 toastr.error('An error occurred while starting the time.');
        //             });
        //     }

        //     function sendEndTimeRequest() {
        //         let timeLogId = localStorage.getItem('timeLogId');
        //         $.ajax({
        //                 url: '{{ route('end.time', '') }}/' + timeLogId,
        //                 method: 'PUT',
        //                 headers: {
        //                     'X-CSRF-TOKEN': token
        //                 },
        //             })
        //             .done(function(response) {
        //                 stopTimer();
        //                 localStorage.removeItem('startTime');
        //                 localStorage.removeItem('timeLogId');
        //                 timeLogId = null; // Clear the timeLogId after stopping
        //             })
        //             .fail(function(err) {
        //                 console.error(err);
        //                 toastr.error('An error occurred while stopping the time.');
        //             });
        //     }

        //     function handleStartPause() {
        //         if (isRunning) {
        //             sendEndTimeRequest();
        //         } else {
        //             sendStartTimeRequest();
        //         }
        //     }
        //     const storedStartTime = localStorage.getItem('startTime');
        //     if (storedStartTime) {
        //         startTime = parseInt(storedStartTime, 10);
        //         elapsedTime = new Date().getTime() - startTime;
        //         startTimer();
        //         updateButtonState('pause');
        //     }
        //     $('#start-time-btn, #pause-time-btn').click(function() {
        //         handleStartPause();
        //     });
        // });

        $(document).ready(function() {




            $('#checkin').submit(function(e) {
                e.preventDefault();

                const url = $(this).attr('action');
                const token = $('meta[name="csrf-token"]').attr('content');
                const button = $('.checkinBtn');
                button.prop('disabled', true);

                $.ajax({
                    url: '/check-in-status',
                    method: 'GET',
                    data: {
                        _token: token,
                        user_id: '{{ Auth::id() }}',
                    },
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success: function(response) {
                        if (response.checkedIn) {
                            toastr.error('Already checked in for today');
                            button.prop('disabled', false);
                        } else {
                            $.ajax({
                                url: url,
                                method: 'POST',
                                data: {
                                    _token: token,
                                    user_id: '{{ Auth::id() }}',
                                },
                                headers: {
                                    'X-CSRF-TOKEN': token
                                },
                                success: function(response) {
                                    toastr.success('Check in successfully');
                                    button.prop('disabled', false);
                                    $('.checkinBtn').addClass('checkinActive');
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2500);
                                },
                                error: function(xhr) {
                                    console.error(xhr);
                                    toastr.success('Check in Failed');
                                    button.prop('disabled', false);
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        toastr.success('Check in Failed');
                        button.prop('disabled', false);
                    }
                });
            });

            $('#checkOut').submit(function(e) {
                e.preventDefault();

                const url = $(this).attr('action');
                const token = $('meta[name="csrf-token"]').attr('content');
                const alreadyCheckedOut = $(this).data('already-checked-out');
                const button = $('.checkOutBtn');
                button.prop('disabled', true);

                if (confirm("Are you sure you want to check out?")) {
                    if (alreadyCheckedOut === 'true') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Already Checked Out',
                            text: 'You have already checked out.',
                        });
                        return;
                    }

                    const now = new Date();
                    const hours = 17 - now.getHours();
                    const minutes = 60 - now.getMinutes();
                    const remainingTime = hours + " hours and " + minutes + " minutes";

                    if (hours <= 0 && minutes <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Already Past Office Closing Time',
                            text: 'It\'s already past office closing time. You cannot check out early.',
                        });
                        return;
                    }

                    $.ajax({
                        url: url,
                        method: 'PATCH',
                        data: {
                            _token: token,
                            user_id: userId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        success: function(response) {
                            button.prop('disabled', false);
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: "success",
                                title: "Check out successfully"
                            });
                            setTimeout(function() {
                                window.location.reload();
                            }, 2500);
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            button.prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
    @stack('js')
</body>

</html>
