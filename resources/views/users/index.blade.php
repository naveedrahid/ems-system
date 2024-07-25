@extends('masterLayout.app')
@section('main')
@section('page-title')
    View All Users
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    Add Users
                </a>
            </h3>
        </div>
        @php
            $roleId = auth()->user()->role_id;
        @endphp
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #fff;">
                    <tr>
                        <th width="20%">Name</th>
                        <th width="20%">Email</th>
                        <th width="10%">Role</th>
                        @if ($roleId == 0)
                            <th width="10%">Status</th>
                        @endif
                        <th width="10%">Manage</th>
                    </tr>
                </thead>
                <tbody style="background: #fff;">
                    @if ($employees->count() > 0)
                        @foreach ($employees as $employee)
                            {{-- @dd($employee) --}}
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>
                                    @if ($employee->role_id == 0)
                                        {{ 'Super Admin' }}
                                    @else
                                        {{ $employee->role->name }}
                                    @endif
                                </td>
                                @if ($roleId == 0)
                                    <td>
                                        <button
                                            class="user-toggle btn btn-{{ $employee->status === 'active' ? 'info' : 'danger' }} btn-sm"
                                            data-id="{{ $employee->id }}" data-status="{{ $employee->status }}">
                                            <i
                                                class="fa fa-thumbs-{{ $employee->status === 'active' ? 'up' : 'down' }}"></i>
                                        </button>
                                    </td>
                                @endif
                                <td>
                                    @if ($roleId == 0 || $employee->role_id == $roleId)
                                        <a href="{{ route('users.edit', $employee->id) }}"
                                            class="btn btn-info btn-flat btn-sm">
                                            <i class="fa fa-edit"></i></a>
                                    @endif
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
