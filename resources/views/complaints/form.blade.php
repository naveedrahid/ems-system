@extends('masterLayout.app')
@section('main')
@section('page-title')
    <h1>{{ $complaint->exists ? 'Edit Complaint' : 'Create Complaint' }}</h1>
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    {!! Form::model($complaint, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => $complaint->exists ? 'updateComplain' : 'addComplain',
                    ]) !!}

                    <div class="box-footer">
                        {!! Form::submit($complaint->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('complaints.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>
@endsection
@endsection
