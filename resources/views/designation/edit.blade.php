@extends('masterLayout.app')
@section('main')
@section('page-title')
    create Designation
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <form action="{{route('designation.update', $designation->id)}}" method="PUT" id="designationUpdate">
                        @csrf
                        @method('PUT')
                        <div class="mb-3 form-group">
                            <label class="form-label">Department Name</label>
                            <select name="department_id" id="department_id"
                                class="form-control form-select select2 @error('department_id') is-invalid @enderror"
                                style="width: 100%;">
                                <option value="">Select Department</option>
                                @foreach ($departments as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ $id == $designation->department_id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Designation Name</label>
                            <input type="text" name="designation_name" id="designation_name"
                                class="form-control @error('designation_name') is-invalid @enderror"
                                value="{{ $designation->designation_name }}">
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Department Status</label>
                            <select name="status"
                                class="form-control form-select select2 @error('status') is-invalid @enderror"
                                style="width: 100%;">
                                <option value="active" {{ $designation->status == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="deactive" {{ $designation->status == 'deactive' ? 'selected' : '' }}>DeActive
                                </option>
                            </select>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('designation.view') }}" class="btn btn-danger">Cancel</a>
                        </div>

                    </form>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>
@endsection
@endsection
