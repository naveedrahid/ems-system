@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $job_offer->exists ? 'Edit job offer' : 'Create job offer' }}
@endsection
@section('page-content')
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card small-box card-primary p-5">
                    {!! Form::model($job_offer, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => $job_offer->exists ? 'updateJobOfferHandler' : 'createJobOfferHandler',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('job_id', 'Job Title ') !!}
                                {!! Form::select('job_id', ['' => 'Select Job'] + $job->toArray(), null, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'job_id',
                                ]) !!}
                            </div>
                        </div>
                        {{-- <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Job Created By') !!}
                                {!! Form::text('created_by', $createrName, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Job Title') !!}
                                {!! Form::text('title', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::textarea('description', old('description'), [
                                    'id' => 'awardEditor',
                                    'cols' => 30,
                                    'rows' => 10,
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div> --}}
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($job_offer->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary setDisabled']) !!}
                        <a href="{{ route('jobs.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
