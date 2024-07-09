<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ config('app.name', 'Laravel') }} | Apply Job</title>
    <link rel="icon" type="image/png" href="{{ asset('Pixelz360.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        crossorigin="anonymous">
    <style>
        span.select2-selection.select2-selection--single {
            height: 40px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <h1>{{ $jobTitle }}</h1>
                    <div class="card small-box card-primary p-5">

                        @if (session('message'))
                            <div class="alert alert-success">{{ session('message') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {!! Form::model($candidate,[
                            'url' => route('candidates.store'),
                            'method' => 'POST',
                            'id' => 'candidateJobHandler',
                            'files' => true,
                        ]) !!}
                        
                        <input type="hidden" name="job_id" value="{{ encrypt($job->id) }}">
                        
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('first_name', 'First Name') !!}
                                    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('last_name', 'Last Name') !!}
                                    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('email', 'Email') !!}
                                    {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('phone', 'Phone') !!}
                                    {!! Form::tel('phone', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('age', 'Age') !!}
                                    {!! Form::text('age', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('city', 'City') !!}
                                    {!! Form::select('city', array_combine($cities, $cities), null, ['class' => 'form-control form-select select2']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('gender', 'Gender') !!}
                                    {!! Form::select('gender', ['' => 'Select Gender', 'male' => 'Male', 'female' => 'Female'], null, ['class' => 'form-control form-select select2']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('marital_status', 'Marital Status') !!}
                                    {!! Form::select('marital_status', ['' => 'Select Marital Status', 'single' => 'Single', 'married' => 'Married'], null, ['class' => 'form-control form-select select2']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('total_experience', 'Total Experience') !!}
                                    {!! Form::number('total_experience', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('current_salary', 'Current Salary') !!}
                                    {!! Form::text('current_salary', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('expected_salary', 'Expected Salary') !!}
                                    {!! Form::text('expected_salary', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('switching_reason', 'Switching Reason') !!}
                                    {!! Form::text('switching_reason', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('notice_period', 'Notice Period') !!}
                                    {!! Form::text('notice_period', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('datetime', 'Date Time For Schedule Interview') !!}
                                    {!! Form::input('datetime-local', 'datetime', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('linkdin', 'LinkedIn') !!}
                                    {!! Form::url('linkdin', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('github', 'Github') !!}
                                    {!! Form::url('github', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('behance', 'Behance') !!}
                                    {!! Form::url('behance', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('website', 'Website') !!}
                                    {!! Form::url('website', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('resume', 'Resume') !!}
                                    {!! Form::file('resume', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {!! Form::label('cover_letter', 'Cover Letter') !!}
                                    {!! Form::file('cover_letter', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            {!! Form::submit('Submit', ['class' => 'btn btn-primary setDisabled']) !!}
                        </div>
                        
                        {!! Form::close() !!}
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('select').select2();
        });
    </script>
</body>

</html>
