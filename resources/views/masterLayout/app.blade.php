<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Home | American Books</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('Pixelz360.png') }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.8/sweetalert2.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('admin/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dist/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/main.css') }}">
</head>

<body class="hold-transition sidebar-mini skin-blue-light">
    <div class="wrapper">
        <header class="main-header">
            <a href="javascrip:;" class="logo">
                Pixel360
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
                                <img src="{{ asset('admin/images/face8.jpg') }}" class="user-image" alt="User Image">
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
                                        <a href="#" class="btn btn-default btn-flat">Sign out</a>
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
                        <img src="{{ asset('admin/images/face8.jpg') }}" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>Hello, {{ auth()->user()->name }}!</p>
                        <a href="javascript:;"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                    </li>

                    @if (Auth::user())
                        @if (Auth::user()->id == 1)
                            <li class=" treeview">
                                <a href="javascript:;">
                                    <i class="fa fa-users"></i> <span>User</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="active"><a href="{{ route('user_create') }}"><i
                                                class="fa fa-circle-o"></i>
                                            Add New
                                            User</a>
                                    </li>
                                    <li class=""><a href="{{ route('users') }}"><i class="fa fa-circle-o"></i>
                                            View
                                            User
                                        </a></li>
                                    <li class="active"><a href="{{ route('roles') }}"><i
                                                class="fa fa-circle-o"></i>Roles</a>
                                    </li>
                                </ul>
                            </li>
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
                                    <li class="active"><a href="{{ route('user_create') }}"><i
                                                class="fa fa-circle-o"></i>
                                            Add New
                                            Shift</a>
                                    </li>
                                    <li class=""><a href="{{ route('users') }}"><i class="fa fa-circle-o"></i>
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
                                    <li class="active"><a href="{{ route('user_create') }}"><i
                                                class="fa fa-circle-o"></i>
                                            Add New
                                            Complaint</a>
                                    </li>
                                    <li class=""><a href="{{ route('users') }}"><i class="fa fa-circle-o"></i>
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
                                    <li class="active"><a href="{{ route('user_create') }}"><i
                                                class="fa fa-circle-o"></i>
                                            Add New
                                            Award</a>
                                    </li>
                                    <li class=""><a href="{{ route('users') }}"><i class="fa fa-circle-o"></i>
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
                                    <li class="active"><a href="{{ route('user_create') }}"><i
                                                class="fa fa-circle-o"></i>
                                            Add New
                                            Notice</a>
                                    </li>
                                    <li class=""><a href="{{ route('users') }}"><i class="fa fa-circle-o"></i>
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
                                    <li class="active"><a href="{{ route('user_create') }}"><i
                                                class="fa fa-circle-o"></i>
                                            Create Payslips</a>
                                    </li>
                                    <li class=""><a href="{{ route('users') }}"><i class="fa fa-circle-o"></i>
                                            View
                                            Payslips
                                        </a></li>
                                </ul>
                            </li>
                        @endif
                        <li class="treeview">
                            <a href="javascript:;">
                                <i class="fa fa-users"></i> <span>Employees</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="active"><a href="{{ route('user_create') }}"><i
                                            class="fa fa-circle-o"></i>
                                        Add New
                                        Employee</a>
                                </li>
                                <li class=""><a href="{{ route('users') }}"><i class="fa fa-circle-o"></i>
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
                                    <a href="#"><i class="fa fa-circle-o"></i>Request A Leave</a>
                                </li>
                                <li class="">
                                    <a href="#"><i class="fa fa-circle-o"></i>All Leave</a>
                                </li>
                                <li class="">
                                    <a href="#"><i class="fa fa-circle-o"></i>Leave Types</a>
                                </li>
                                <li class="">
                                    <a href="#"><i class="fa fa-circle-o"></i>Holidays</a>
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
                                    <a href="{{ route('attendance') }}"><i class="fa fa-circle-o"></i> View
                                        Attendance
                                    </a>
                                </li>
                                <li class="">
                                    <a href="{{ route('attendance') }}"><i class="fa fa-circle-o"></i> View
                                        Report
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
                                    <a href="#"><i class="fa fa-circle-o"></i>Leave Policy</a>
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
                        

                        <li class="treeview">
                            <form action="{{ route('logoutUser') }}" method="POST" class="logout">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </form>
                        </li>
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
            <strong>Copyright &copy; 2024 <a href="#">Pixel 360</a>.</strong> All rights
            reserved.
        </footer>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="admin/js/chartJs.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.8/sweetalert2.min.js"></script>
        <script src="{{ asset('admin/js/customAjax.js') }}"></script>
        <script src="{{ asset('admin/plugins/chartjs/Chart.min.js') }}"></script>
        <script src="{{ asset('admin/dist/js/app.min.js') }}"></script>
        <script src="{{ asset('admin/dist/js/demo.js') }}"></script>
        <script src="{{ asset('admin/js/chartJs.js') }}"></script>
</body>

</html>
