@extends('masterLayout.app')
@section('main')
@section('page-title')
    View All Users
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a class="btn btn-danger btn-xm"><i class="fa fa-trash"></i></a>
                <a href="{{ route('user_create') }}" class="btn btn-default btn-xm"><i class="fa fa-plus"></i></a>
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
                        <th width="20%">Name</th>
                        <th width="20%">Email</th>
                        <th width="10%">Role</th>
                        <th width="10%">Status</th>
                        <th width="10%">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($employees->whereIn('id', [1, 2])->count() > 0)
                        @foreach ($employees->whereIn('id', [1, 2]) as $employee)
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
        <div class="box-footer clearfix">
            <div class="row">
                <div class="col-sm-6">
                    <span style="display:block;font-size:15px;line-height:34px;margin:20px 0;">
                        Showing 100 to 500 of 1000 entries
                    </span>
                </div>
                <div class="col-sm-6 text-right">
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
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
