@extends('masterLayout.app')
@section('main')
@section('page-title')
    View All Users
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('user_create') }}" class="btn btn-block btn-primary">
                    Add Users
                </a>
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th width="4%"><input type="checkbox" name="" id="checkAll"></th>
                        <th width="20%">Name</th>
                        <th width="20%">Email</th>
                        <th width="10%">Role</th>
                        <th width="10%">Status</th>
                        <th width="10%">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($employees->count() > 0)
                        @foreach ($employees as $employee)
                            {{-- @if (count($employees) > 0)
                                @foreach ($employees as $employee) --}}
                            <tr>
                                <td><input type="checkbox" name="" id="" class="checkSingle"></td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>
                                    @if ($employee->role_id == 0)
                                        {{ 'Super Admin' }}
                                    @else
                                        {{ $employee->role->name }}
                                    @endif
                                </td>
                                <td>
                                    <button
                                        class="user-toggle btn btn-{{ $employee->status === 'active' ? 'info' : 'danger' }} btn-sm"
                                        data-id="{{ $employee->id }}" data-status="{{ $employee->status }}">
                                        <i class="fa fa-thumbs-{{ $employee->status === 'active' ? 'up' : 'down' }}"></i>
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('user_edit', $employee->id) }}" class="btn btn-info btn-flat btn-sm">
                                        <i class="fa fa-edit"></i></a>
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
