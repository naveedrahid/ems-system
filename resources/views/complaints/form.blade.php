@extends('masterLayout.app')
@section('main')
@section('page-title')
    <h1>{{ $complaint->exists ? 'Edit Complaint' : 'Create Complaint' }}</h1>
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    {{-- {!! Form::model($complaint, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => $complaint->exists ? 'updateComplain' : 'addComplain',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('username', 'Employee Name') !!}
                                <div class="EmpInfo">
                                    {{ auth()->user()->name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('employeeTypeName', 'Employee Type') !!}
                                <div class="EmpInfo">
                                    {{ $employeeTypeName }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('department', 'Department Name') !!}
                                <div class="EmpInfo">
                                    {{ $departmentName }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('designationName', 'Designation Name') !!}
                                <div class="EmpInfo">
                                    {{ $designationName }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 form-group ticketNumber text-center btn btn-block btn-default btn-flat">
                        <strong>{{$ticketNumber}}</strong>
                    </div>

                    <div class="box-footer">
                        {!! Form::submit($complaint->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('complaints.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!} --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
