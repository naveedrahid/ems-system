@extends('masterLayout.app')
@section('main')
    <div class="content-wrapper" style="min-height: 960px;">
        <section class="content-header">
            <h1>
                Dashboard
            </h1>
            <div class="row">
                <div class="col-md-8">
                    <div>
                        <h1>{{ auth()->user()->name }} |
                            <small>Development - Manager</small>
                        </h1>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-right">
                        <div>
                            <h4><span>Today: {{ date('d M, Y') }}</span></h4>
                        </div>
                        <div>
                            <ul>
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
        </section>
        <section class="content">
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
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-book"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Leave</span>
                                    <span class="info-box-number">08</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Used Leave</span>
                                    <span class="info-box-number">02</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Remaining Leave</span>
                                    <span class="info-box-number">06</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Latest Employee</h3>
                                    <div class="box-tools pull-right">
                                        <span class="label label-danger">8 New Members</span>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body no-padding">
                                    <ul class="users-list clearfix">
                                        <li>
                                            <img src="{{ asset('/admin/images/face8.jpg') }}" alt="User Image">
                                            <a class="users-list-name" href="#">Alexander Pierce</a>
                                            <span class="users-list-date">Today</span>
                                        </li>
                                        <li>
                                            <img src="{{ asset('/admin/images/face8.jpg') }}" alt="User Image">
                                            <a class="users-list-name" href="#">Norman</a>
                                            <span class="users-list-date">Yesterday</span>
                                        </li>
                                        <li>
                                            <img src="{{ asset('/admin/images/face8.jpg') }}" alt="User Image">
                                            <a class="users-list-name" href="#">Jane</a>
                                            <span class="users-list-date">12 Jan</span>
                                        </li>
                                        <li>
                                            <img src="{{ asset('/admin/images/face8.jpg') }}" alt="User Image">
                                            <a class="users-list-name" href="#">John</a>
                                            <span class="users-list-date">12 Jan</span>
                                        </li>
                                        <li>
                                            <img src="{{ asset('/admin/images/face8.jpg') }}" alt="User Image">
                                            <a class="users-list-name" href="#">Alexander</a>
                                            <span class="users-list-date">13 Jan</span>
                                        </li>
                                        <li>
                                            <img src="{{ asset('/admin/images/face8.jpg') }}" alt="User Image">
                                            <a class="users-list-name" href="#">Sarah</a>
                                            <span class="users-list-date">14 Jan</span>
                                        </li>
                                        <li>
                                            <img src="{{ asset('/admin/images/face8.jpg') }}" alt="User Image">
                                            <a class="users-list-name" href="#">Nora</a>
                                            <span class="users-list-date">15 Jan</span>
                                        </li>
                                        <li>
                                            <img src="{{ asset('/admin/images/face8.jpg') }}" alt="User Image">
                                            <a class="users-list-name" href="#">Nadia</a>
                                            <span class="users-list-date">15 Jan</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="box-footer text-center">
                                    <a href="javascript:void(0)" class="uppercase">View All Users</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Up Coming Holidays</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                class="fa fa-times"></i></button>
                                    </div>
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
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Up Coming Birthday</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                class="fa fa-times"></i></button>
                                    </div>
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
        </section>
    </div>
@endsection
