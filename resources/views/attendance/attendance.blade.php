@extends('masterLayout.app')
@section('main')
@section('page-title')
    Employee Attendance
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a class="btn btn-danger btn-xm"><i class="fa fa-eye"></i></a>
                <a class="btn btn-danger btn-xm"><i class="fa fa-trash"></i></a>
                <a class="btn btn-default btn-xm"><i class="fa fa-plus"></i></a>
            </h3>
            <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
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
                    @if (auth()->user()->id == 1 || auth()->user()->id == 2)
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
                                <td>{{ textFormating($result->status) }}</td>
                            </tr>
                        @endforeach
                    @else
                        @foreach ($attendance as $result)
                            @if ($result->user_id == auth()->user()->id)
                                <tr>
                                    <td><input type="checkbox" name="" id="" class="checkSingle"></td>
                                    <td>{{ $result->id }}</td>
                                    <td>
                                        @foreach ($users as $user)
                                            @if ($user->id == auth()->user()->id)
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
                                    <td>{{ textFormating($result->status) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <div class="row">
                {{-- <div class="col-sm-6">
                <span style="display:block;font-size:15px;line-height:34px;margin:20px 0;">
                    Showing 100 to 500 of 1000 entries
                </span>
            </div> --}}
                {{-- <div class="col-sm-6 text-right">
                <ul class="pagination">
                    <li class="paginate_button previous"><a href="#">Previous</a></li>
                    <li class="paginate_button active"><a href="#">1</a></li>
                    <li class="paginate_button "><a href="#">2</a></li>
                    <li class="paginate_button "><a href="#">3</a></li>
                    <li class="paginate_button "><a href="#">4</a></li>
                    <li class="paginate_button "><a href="#">5</a></li>
                    <li class="paginate_button "><a href="#">6</a></li>
                    <li class="paginate_button next"><a href="#">Next</a></li>
                </ul>
            </div> --}}
            </div>
        </div>
    </div>
@endsection
@endsection
