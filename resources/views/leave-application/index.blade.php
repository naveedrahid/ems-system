@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Leave Applications
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('leave_application.create') }}" class="btn btn-block btn-primary">
                    Reques a leave
                </a>
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th width="4%"><input type="checkbox" name="" id="checkAll"></th>
                        <th width="16%">Employee Name</th>
                        <th width="10%">Leave Type</th>
                        <th width="10%">Start Date</th>
                        <th width="10%">End Date</th>
                        <th width="10%">Image</th>
                        <th width="10%">Reason</th>
                        <th width="10%">Total Days</th>
                        <th width="10%">Leave Status</th>
                        @if (Auth::user()->id < 3)
                            <th width="10%">Manage</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (count($leaveApplications) > 0)
                        @foreach ($leaveApplications as $leaveApplication)
                            @if ($roleId === 1 || $roleId === 2 || $leaveApplication->user->id === $userId)
                                <tr>
                                    <td><input type="checkbox" name="" id="" class="checkSingle"></td>
                                    <td>{{ $leaveApplication->user->name }}</td>
                                    <td>{{ $leaveApplication->leaveType->name }}</td>
                                    <td>{{ $leaveApplication->start_date }}</td>
                                    <td>{{ $leaveApplication->end_date }}</td>
                                    <td>{{ $leaveApplication->reason }}</td>
                                    <td>
                                        @if (!$leaveApplication->leave_image)
                                            <img src="{{ asset('admin/images/image-not-found.png') }}" width="70"
                                                height="70" alt="">
                                        @else
                                            <img src="{{ asset('upload/' . $leaveApplication->leave_image) }}"
                                                width="70" height="70" alt="">
                                        @endif
                                    </td>
                                    <td>{{ $leaveApplication->total_leave }}</td>
                                    <td>
                                        @switch($leaveApplication->status)
                                            @case('Rejected')
                                                <span class="label label-danger">{{ $leaveApplication->status }}</span>
                                            @break

                                            @case('Approved')
                                                <span class="label label-success">{{ $leaveApplication->status }}</span>
                                            @break

                                            @case('Pending')
                                                <span class="label label-warning">{{ $leaveApplication->status }}</span>
                                            @break
                                        @endswitch
                                    </td>
                                    @if (Auth::user()->id < 3)
                                        <td>
                                            <a href="{{ route('leave_application.edit', $leaveApplication) }}"
                                                class="btn btn-info btn-flat btn-sm"> <i class="fa fa-edit"></i></a>
                                            <button class="delete-leave-application btn btn-danger btn-flat btn-sm"
                                                data-leave-app-id="{{ $leaveApplication->id }}"
                                                data-delete-route="{{ route('leave_application.destroy', ':id') }}"><i
                                                    class="fa-regular fa-trash-can"></i></button>
                                        </td>
                                    @endif
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
