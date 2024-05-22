@extends('masterLayout.app')
@section('main')
@section('page-title')
    Edit Leave Types
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form action="{{ route('leave_types.update', $leaveType->id) }}" method="POST" id="updateLeave">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="redirect-url" value="{{ route('leave_types.index') }}">
                        <div class="mb-3 form-group">
                            <label class="form-label">Leave Types Name</label>
                            <input type="text" name="name" id="leave_name" class="form-control"
                                value="{{ $leaveType->name ?? '' }}">
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Total Leave</label>
                            <input type="text" name="default_balance" id="default_balance" class="form-control"
                                value="{{ $leaveType->default_balance ?? '' }}">
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-control form-select select2"
                                style="width: 100%;">
                                <option value="active" {{ $leaveType->status == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="deactive" {{ $leaveType->status == 'deactive' ? 'selected' : '' }}>Deactive
                                </option>
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control">{{ $leaveType->description ?? '' }}</textarea>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('leave_types.index') }}" class="btn btn-danger">Cancel</a>
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