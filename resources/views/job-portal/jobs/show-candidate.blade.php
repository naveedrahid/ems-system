<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ config('app.name', 'Laravel') }} | Apply Job</title>
    <link rel="icon" type="image/png" href="{{ asset('Pixelz360.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        crossorigin="anonymous">
        <style>
            .jobWrapper {
                padding: 30px;
                border-radius: 10px;
                box-shadow: #0000006e 0px 0px 20px 0px;
                background: #fff;
                margin-bottom: 30px;
            }
        
            .jobWrapper p {
                color: #000;
                font-size: 18px !important;
            }
        
            .jobWrapper h4 {
                text-decoration: underline;
                margin: 20px 0px;
                font-weight: 700;
            }
        
            .col-md-6 {}
        
            .jobWrapper~div {
                display: flex;
            }
        
            .jobWrapper~div form {
                margin-right: 20px;
            }
        </style>
</head>
<body>
    <div class="container">
        <div class="box">
            <div class="box-body">
                <div class="row">

                    @if ($jobs)
                        @foreach ($jobs as $job)
                            <div class="col-md-6">
                                <div class="jobWrapper">
                                    @if ($job->job_img)
                                        <img src="{{ asset($job->job_img) }}" alt="Award Image" class="img-fluid">
                                    @endif
                                    <h4>{{ $job->title }}</h4>
                                    <p> <strong>Department:</strong> {{ $departments[$job->department_id] ?? 'N/A' }}</p>
                                    <p> <strong>Designatio:</strong> {{ $designations[$job->designation_id] ?? 'N/A' }}</p>
                                    <p> <strong>Shift:</strong> {{ $shifts[$job->shift_id] ?? 'N/A' }}</p>
                                    @php
                                        $employmentTypes = explode(', ', $job->employment_type);
                                    @endphp
                                    <p>

                                        <strong>JobType:</strong>{{ ucwords(str_replace('_', ' ', implode(', ', $employmentTypes))) }}
                                    </p>
                                    <p>
                                        <strong>Location:</strong> {{ $job->location }}
                                    </p>
                                    <p>
                                        <strong>Salary:</strong>{{ $job->salary_range }}
                                    </p>
                                    <p>
                                        <strong>Job Last Date</strong>{{ $job->closing_date }}
                                    </p>
                                    {{-- @php
                                            $jobImg = str_replace('public/', 'storage/', $job->job_images) ?? '';
                                        @endphp --}}
                                    <p>{!! $job->description !!}</p>
                                    <a href="{{ route('candidates.create', ['job' => $encryptedJobIds[$job->id]]) }}">Apply</a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>

</body>

</html>

