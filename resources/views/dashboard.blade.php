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
                        in {{ $departmentName }} Department
                    </small>
                </h1>

            </div>
        </div>
        <div class="col-md-4">
            <div class="text-right">
                <div>
                    <h4><span>Today: {{ date('d M, Y') }}</span></h4>
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
    @if (Auth::user())
        @if (Auth::user()->id == 1)
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="ion ion-ios-book"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Books</span>
                            <span class="info-box-number">90</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Authors</span>
                            <span class="info-box-number">41,410</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Categories</span>
                            <span class="info-box-number">760</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Team Members</span>
                            <span class="info-box-number">2,000</span>
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
                                    <li>
                                        @if (!$employee->employee_img && $employee->gender === 'male')
                                            <img src="{{ asset('admin/images/male.jpg') }}" width="80" height="80"
                                                alt="User Image">
                                        @elseif(!$employee->employee_img && $employee->gender === 'female')
                                            <img src="{{ asset('admin/images/female.png') }}" width="80" height="80"
                                                alt="User Image">
                                        @else
                                            <img src="{{ asset('upload/' . $employee->employee_img) }}" width="80"
                                                height="80" alt="User Image">
                                        @endif
                                        <a class="users-list-name" href="javascript:;">{{ $employee->user->name }}</a>
                                        <span class="users-list-date">{{ $departmentName }}</span>
                                        <span
                                            class="users-list-date">{{ optional($employee->designation)->designation_name }}</span>
                                    </li>
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
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
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
                                            <th>Holidays</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
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
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Team {{ $departmentName }}</h3>
                        </div>
                        <div class="box-body no-padding">
                            <ul class="users-list clearfix">
                                @foreach ($employeesByDepartment as $employee)
                                    <li>
                                        @if (!$employee->employee_img && $employee->gender === 'male')
                                            <img src="{{ asset('admin/images/male.jpg') }}" width="80"
                                                height="80" alt="User Image">
                                        @elseif(!$employee->employee_img && $employee->gender === 'female')
                                            <img src="{{ asset('admin/images/female.png') }}" width="80"
                                                height="80" alt="User Image">
                                        @else
                                            <img src="{{ asset('upload/' . $employee->employee_img) }}" width="80"
                                                height="80" alt="User Image">
                                        @endif
                                        <a class="users-list-name" href="javascript:;">{{ $employee->user->name }}</a>
                                        <span class="users-list-date">{{ $departmentName }}</span>
                                        <span
                                            class="users-list-date">{{ optional($employee->designation)->designation_name }}</span>
                                    </li>
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
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
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
                                            <th>Holidays</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
                                        <tr>
                                            <td>Call of Duty IV</td>
                                            <td><span class="label label-success">Shipped</span></td>
                                        </tr>
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
