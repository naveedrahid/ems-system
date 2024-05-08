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
                    <form action="{{ route('designation.store') }}" method="POST" id="designationSoter">
                        @csrf
                        <div class="mb-3 form-group">
                            <label class="form-label">Department Name</label>
                            <select name="department_id" id="department_id"
                                class="form-control form-select select2 @error('department_id') is-invalid @enderror"
                                style="width: 100%;">
                                <option value="">Select Department</option>
                                @foreach ($departments as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Designation Name</label>
                            <input type="text" name="designation_name" id="designation_name"
                                class="form-control @error('designation_name') is-invalid @enderror"
                                value="{{ old('designation_name') }}">
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">status </label>
                            <select name="status" id="status"
                                class="form-control form-select select2 @error('status') is-invalid @enderror"
                                style="width: 100%;">
                                @foreach (['active', 'deactive'] as $status)
                                    <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('designation.view') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
    </div>
@endsection
@endsection
