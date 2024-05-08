@extends('masterLayout.app')
@section('main')
@section('page-title')
    Edit Department
@endsection
@section('page-content')
    <div class="box box-primary">

        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <form action="{{ route('departmentUpdate', $department->id) }}" method="PUT" id="departmentDataUpdate">
                        @csrf
                        @method('PUT')
                        {{-- {{dd($department->id)}} --}}
                        <div class="mb-3 form-group">
                            <label class="form-label">Department Name</label>
                            <input type="text" name="department_name" class="form-control @error('department_name') is-invalid @enderror"
                                value="{{ $department->department_name }}">
                            @error('department_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Department Status</label>
                            <select name="status" class="form-control form-select select2 @error('status') is-invalid @enderror"
                                style="width: 100%;">
                                <option value="active" {{ $department->status == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="deactive" {{ $department->status == 'deactive' ? 'selected' : '' }}>DeActive
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('departmentView') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>
@endsection
@endsection
