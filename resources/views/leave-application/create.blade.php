@extends('masterLayout.app')
@section('main')
@section('page-title')
    create Leave Applicatation
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form action="{{ route('leave_application.store') }}" method="POST" enctype="multipart/form-data"   id="addLeaveApplication">
                        @csrf
                        <input type="hidden" id="redirect-url" value="{{ route('leave_application.index') }}">
                        <div class="mb-3 form-group">
                            <label class="form-label">Select Leave Type</label>
                            <select name="leave_type_id" id="leave_type_id" class="form-control form-select select2"
                                style="width: 100%;">
                                @foreach ($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->id }}">
                                        {{ $leaveType->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" id="reason" cols="30" rows="10" class="form-control">{{ old('reason') }}</textarea>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Upload</label>
                            <input type="file" name="leave_image" id="leave_image">
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('leave_application.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
    </div>
@endsection
@endsection
