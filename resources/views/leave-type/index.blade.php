@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Leave Types
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a class="btn btn-danger btn-xm"><i class="fa fa-trash"></i></a>
                <a href="{{ route('leave_types.create') }}" class="btn btn-default btn-xm"><i class="fa fa-plus"></i></a>
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
                        <th width="26%">Name</th>
                        <th width="30%">Description</th>
                        <th width="20%">Leave Balance</th>
                        <th width="10%">Status</th>
                        <th width="10%">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($leaveTypes) > 0)
                        @foreach ($leaveTypes as $leaveType)
                            <tr>
                                <td><input type="checkbox" name="" id="" class="checkSingle"></td>
                                <td>{{ $leaveType->name }}</td>
                                <td>{{ $leaveType->description }}</td>
                                <td>{{ $leaveType->default_balance }}</td>
                                <td>
                                    <button
                                        class="p-relative leave-toggle btn btn-{{ $leaveType->status === 'active' ? 'info' : 'danger' }} btn-sm"
                                        data-id="{{ $leaveType->id }}" data-status="{{ $leaveType->status }}">
                                        <i class="fa fa-thumbs-{{ $leaveType->status === 'active' ? 'up' : 'down' }}"></i>
                                        <img src="{{ asset('admin/images/loader.gif') }}" class="imgLoader" width="20" height="20"
                                            alt="Loading...">
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('leave_types.edit', ['leaveType' => $leaveType->id]) }}"
                                        class="btn btn-info btn-flat btn-sm"> <i class="fa fa-edit"></i></a>
                                    <button class="delete-leave-type btn btn-danger btn-flat btn-sm"
                                        data-leave-type-id="{{ $leaveType->id }}"
                                        data-delete-route="{{ route('leave_types.destroy', ':id') }}"><i
                                            class="fa-regular fa-trash-can"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@endsection
