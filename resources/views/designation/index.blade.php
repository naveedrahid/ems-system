@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Designation
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a class="btn btn-danger btn-xm"><i class="fa fa-eye"></i></a>
                <a class="btn btn-danger btn-xm"><i class="fa fa-eye-slash"></i></a>
                <a class="btn btn-danger btn-xm"><i class="fa fa-trash"></i></a>
                <a href="{{ route('designation.create') }}" class="btn btn-default btn-xm"><i class="fa fa-plus"></i></a>
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
                        <th width="16%">Desingnation</th>
                        <th width="40%">Department Name</th>
                        <th width="20%">Status</th>
                        <th width="20%">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($designations) > 0)
                        @foreach ($designations as $designation)
                            <tr>
                                <td><input type="checkbox" name="" id="" class="checkSingle"></td>
                                <td>{{ $designation->designation_name }}</td>
                                <td>{{ $designation->department->department_name }}</td>
                                <td>
                                    <button
                                        class="status-toggle btn btn-{{ $designation->status === 'active' ? 'info' : 'danger' }} btn-sm"
                                        data-id="{{ $designation->id }}" data-status="{{ $designation->status }}">
                                        <i class="fa fa-thumbs-{{ $designation->status === 'active' ? 'up' : 'down' }}"></i>
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('designation.edit', ['id' => $designation->id]) }}"
                                        class="btn btn-info btn-flat btn-sm"> <i class="fa fa-edit"></i></a>
                                    <button class="delete-designation btn btn-danger btn-flat btn-sm"
                                        data-designation-id="{{ $designation->id }}"
                                        data-delete-route="{{ route('designation.destroy', ':id') }}"><i
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
