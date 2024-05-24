@extends('masterLayout.app')
@section('main')
@section('page-title')
    Add Attendance
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('attendance.store') }}" method="POST" id="">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Select User: <span class="text text-red">*</span></label>
                                    <select name="user_id" id="user_id"
                                        class="form-control form-select @error('user_id') is-invalid @enderror"
                                        style="width: 100%;">
                                        @foreach ($userNames as $userName)
                                            <option value="{{ $userName->id }}">{{ $userName->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Date: <span class="text text-red">*</span></label>
                                    <input type="date" name="attendance_date" id="attendance_date" class="form-control  @error('attendance_date') is-invalid @enderror">
                                    @error('attendance_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">check In Time: <span class="text text-red">*</span></label>
                                    <input type="time" name="check_in" id="check_in" class="form-control  @error('check_in') is-invalid @enderror">
                                    @error('check_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">Check Out Time: <span class="text text-red">*</span></label>
                                    <input type="time" name="check_out" id="check_out" class="form-control  @error('check_out') is-invalid @enderror">
                                    @error('check_out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('attendance') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
