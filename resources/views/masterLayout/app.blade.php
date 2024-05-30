<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Laravel') }} | American Books</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('Pixelz360.png') }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.8/sweetalert2.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('admin/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dist/css/AdminLTE.min.css') }}">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('admin/dist/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/main.css') }}">
</head>

<body class="hold-transition sidebar-mini skin-blue-light">
    <div class="wrapper">
        <header class="main-header">
            <a href="{{ url('/') }}" class="logo">
                <img src="{{ asset('/admin/images/Pixelz360.svg') }}" class="img-fluid" alt=""
                    style="height:40px;">
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav flexBtnHeader">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                @if (optional(auth()->user()->employee)->employee_img)
                                    <img src="{{ asset('upload/' . optional(auth()->user()->employee)->employee_img) }}"
                                        alt="Employee Image"
                                        class="profile-user-img img-responsive img-circle user-image">
                                @endif
                                <span class="hidden-xs"> Hello, {{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="{{ asset('admin/images/face8.jpg') }}" class="img-circle"
                                        alt="User Image">
                                    <p>
                                        Hello, {{ auth()->user()->name }}
                                    </p>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <form action="{{ route('logoutUser') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-default btn-flat">Sign Out</button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">

                    <div class="pull-left image">
                        @if (optional(auth()->user()->employee)->employee_img)
                            <img src="{{ asset('upload/' . optional(auth()->user()->employee)->employee_img) }}"
                                alt="Employee Image" class="profile-user-img img-responsive img-circle"
                                style="width:40px;height:40px;margin:unset;">
                        @endif
                    </div>
                    <div class="pull-left info">
                        <p>Hello, {{ auth()->user()->name }}!</p>
                        <a href="javascript:;"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="treeview">
                        <a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                    </li>

                    @if (Auth::user())
                        @php
                            $user = auth()->user();
                        @endphp
                        @if (isAdmin($user))
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Department</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="">
                                        <a href="{{ route('departmentView') }}"><i class="fa fa-circle-o"></i> View
                                            Department
                                        </a>
                                    </li>
                                    <li class="active">
                                        <a href="{{ route('departmentCreate') }}"><i class="fa fa-circle-o"></i>
                                            Add Department
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Desgination</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="active">
                                        <a href="{{ route('designation.view') }}"><i class="fa fa-circle-o"></i> View
                                            Desgination
                                        </a>
                                    </li>
                                    <li class="active">
                                        <a href="{{ route('designation.create') }}"><i class="fa fa-circle-o"></i>
                                            Add Desgination
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Shifts</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="active"><a href="javascript:;"><i class="fa fa-circle-o"></i>
                                            Add New
                                            Shift</a>
                                    </li>
                                    <li class=""><a href="javascript:;"><i class="fa fa-circle-o"></i>
                                            View
                                            Shift
                                        </a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Complaint Management
                                    </span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="active"><a href="javascript:;"><i class="fa fa-circle-o"></i>
                                            Add New
                                            Complaint</a>
                                    </li>
                                    <li class=""><a href="javascript:;"><i class="fa fa-circle-o"></i>
                                            View
                                            Complaints
                                        </a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Awards Management
                                    </span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="active"><a href="javascript:;"><i class="fa fa-circle-o"></i>
                                            Add New
                                            Award</a>
                                    </li>
                                    <li class=""><a href="javascript:;"><i class="fa fa-circle-o"></i>
                                            View
                                            Awards
                                        </a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Notice Board
                                    </span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="active"><a href="javascript:;"><i class="fa fa-circle-o"></i>
                                            Add New
                                            Notice</a>
                                    </li>
                                    <li class=""><a href="javascript:;"><i class="fa fa-circle-o"></i>
                                            View
                                            Notices
                                        </a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Pay Slips
                                    </span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="active"><a href="javascript:;"><i class="fa fa-circle-o"></i>
                                            Create Payslips</a>
                                    </li>
                                    <li class=""><a href="javascript:;"><i class="fa fa-circle-o"></i>
                                            View
                                            Payslips
                                        </a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Attendance</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="">
                                        <a href="{{ route('daily.report') }}"><i class="fa fa-circle-o"></i>
                                            Daily Attendance
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('attendance') }}"><i class="fa fa-circle-o"></i> Month Wise
                                            Attendance
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('attendance.log') }}"><i
                                                class="fa fa-circle-o"></i>Attendance Log</a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('attendance.report') }}"><i
                                                class="fa fa-circle-o"></i>Attendance Report</a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('attendance.create') }}"><i
                                                class="fa fa-circle-o"></i>Add Attendance</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Employees</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="active"><a href="{{ route('employees.create') }}"><i
                                                class="fa fa-circle-o"></i>
                                            Add New
                                            Employee</a>
                                    </li>
                                    <li class=""><a href="{{ route('employees.view') }}"><i
                                                class="fa fa-circle-o"></i>
                                            View
                                            Employees
                                        </a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Leave Managment</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="">
                                        <a href="{{ route('leave_application.create') }}"><i
                                                class="fa fa-circle-o"></i>Request A Leave</a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('leave_application.index') }}"><i
                                                class="fa fa-circle-o"></i>All Leave</a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('leave_types.index') }}"><i
                                                class="fa fa-circle-o"></i>Leave
                                            Types</a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('holidays.index') }}"><i
                                                class="fa fa-circle-o"></i>Holidays</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Policy Documents</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="">
                                        <a href="{{ asset('admin/document/Leave-Policy-Permanent-Employees-2022.pdf') }}"
                                            target="_blank"><i class="fa fa-circle-o"></i>Leave Policy</a>
                                    </li>
                                    <li class="">
                                        <a href="#"><i class="fa fa-circle-o"></i>Medical Policy</a>
                                    </li>
                                    <li class="">
                                        <a href="#"><i class="fa fa-circle-o"></i>Employee Policy</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Account Setting</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="">
                                        <a href="#"><i class="fa fa-circle-o"></i>Profile</a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('users') }}"><i class="fa fa-circle-o"></i>Users</a>
                                    </li>
                                    <li class="active">
                                        <a href="{{ route('roles') }}"><i class="fa fa-circle-o"></i>Roles</a>
                                    </li>
                                    <li class="">
                                        <a href="#"><i class="fa fa-circle-o"></i>Change Password</a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Employees</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    @if (Auth::user()->id < 3)
                                        <li class="active"><a href="{{ route('employees.create') }}"><i
                                                    class="fa fa-circle-o"></i>
                                                Add New
                                                Employee</a>
                                        </li>
                                    @endif
                                    <li class=""><a href="{{ route('employees.view') }}"><i
                                                class="fa fa-circle-o"></i>
                                            View
                                            Employees
                                        </a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Leave Managment</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="">
                                        <a href="{{ route('leave_application.create') }}"><i
                                                class="fa fa-circle-o"></i>Request A Leave</a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('leave_application.index') }}"><i
                                                class="fa fa-circle-o"></i>All Leave</a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('holidays.index') }}"><i
                                                class="fa fa-circle-o"></i>Holidays</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Attendance</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="">
                                        <a href="{{ route('daily.report') }}"><i class="fa fa-circle-o"></i>
                                            Daily Attendance
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('attendance') }}"><i class="fa fa-circle-o"></i> Month Wise
                                            Attendance
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Policy Documents</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="">
                                        <a href="{{ asset('admin/document/Leave-Policy-Permanent-Employees-2022.pdf') }}"
                                            target="_blank"><i class="fa fa-circle-o"></i>Leave Policy</a>
                                    </li>
                                    <li class="">
                                        <a href="#"><i class="fa fa-circle-o"></i>Medical Policy</a>
                                    </li>
                                    <li class="">
                                        <a href="#"><i class="fa fa-circle-o"></i>Employee Policy</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>Account Setting</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="">
                                        <a href="#"><i class="fa fa-circle-o"></i>Profile</a>
                                    </li>
                                    <li class="">
                                        <a href="#"><i class="fa fa-circle-o"></i>Change Password</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview btnLogoutWrap">
                                <form action="{{ route('logoutUser') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btnLogout"><i
                                            class="fa-solid fa-arrow-right-from-bracket"></i></button>
                                </form>
                            </li>
                        @endif
                    @endif
                </ul>
            </section>
        </aside>
        @yield('main')
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    @yield('page-title')
                </h1>
            </section>
            <section class="content">
                @yield('page-content')
            </section>
        </div>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.3.6
            </div>
            <strong>Copyright &copy; 2024 <a href="#"></a>.</strong> All rights
        </footer>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="admin/js/chartJs.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.8/sweetalert2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script src="{{ asset('admin/js/customAjax.js') }}"></script>
        {{-- <script src="{{ asset('admin/plugins/chartjs/Chart.min.js') }}"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('admin/dist/js/app.min.js') }}"></script>
        <script src="{{ asset('admin/dist/js/demo.js') }}"></script>
        <script src="{{ asset('admin/js/chartJs.js') }}"></script>
        @stack('js')
</body>

</html>
