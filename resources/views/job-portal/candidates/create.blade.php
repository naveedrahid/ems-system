<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ config('app.name', 'Laravel') }} | Apply Job</title>
    <link rel="icon" type="image/png" href="{{ asset('Pixelz360.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <style>
        span.select2-selection.select2-selection--single {
            height: 40px;
        }

        div#loadingSpinner {
            position: fixed;
            left: 0;
            right: 0;
            margin: auto;
            top: 0;
            bottom: 0;
            z-index: 99;
            background: #00000036;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        div#loadingSpinner i {
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="">
        <section class="content job-application">
            <div class="container">
                <div class="card-body job-body position-relative">
                    <div id="loadingSpinner" style="display: none; text-align: center;">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                    </div>
                    <div class="row ">
                        <div class="col-lg-9 col-md-12">
                            <div class="form-logo mt-5">
                                <a href="#">
                                    <img src="{{ asset('admin/images/Header-logo-Pixelz.svg') }}"
                                        class="text-center img-fluid main-logo ml-2" height="63" width="150">
                                </a>
                            </div>
                            <div class="card p-4 mt-4">
                                <div class="card-header pl-0">
                                    <h3 class="text-bold mb-0">{{ $jobTitle }}</h3>
                                    <p class="mb-3">Marketing · Karachi, Sindh</p>
                                </div>
                                <h4 class="text-bold mt-4">Apply for this Position</h4>

                                {!! Form::model($candidate, [
                                    'url' => route('candidates.store'),
                                    'method' => 'POST',
                                    'id' => 'candidateJobHandler',
                                    'files' => true,
                                ]) !!}

                                <input type="hidden" name="job_id" value="{{ encrypt($job->id) }}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5 pl-0 col-12">
                                            <div class="form-group">
                                                {!! Form::label('first_name', 'First Name *') !!}
                                                {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-5 pl-0 col-12">
                                            <div class="form-group">
                                                {!! Form::label('last_name', 'Last Name *') !!}
                                                {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-7 pl-0 mb-3">
                                            {!! Form::label('email', 'Email *') !!}
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-envelope"></i></span>
                                                </div>
                                                {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-3 pl-0 mb-3">
                                            {!! Form::label('phone', 'Phone *') !!}
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-phone-alt"></i></span>
                                                </div>
                                                {!! Form::tel('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone', 'id' => 'phone']) !!}
                                            </div>
                                            <div>
                                                <small><strong>03*********</strong></small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 pl-0">
                                            <div class="form-group">
                                                {!! Form::label('age', 'Age *') !!}
                                                {!! Form::text('age', null, ['class' => 'form-control', 'placeholder' => 'Age']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4 pl-0">
                                            <div class="form-group">
                                                <label for="address">Address *</label>
                                                <input type="text" class="form-control" id="address"
                                                    placeholder="Address">
                                            </div>
                                        </div>
                                        <div class="col-md-3 pl-0">
                                            <div class="form-group">
                                                {!! Form::label('gender', 'Gender *') !!}
                                                {!! Form::select('gender', ['' => 'Select Gender', 'male' => 'Male', 'female' => 'Female'], null, [
                                                    'class' => 'form-control form-select select2',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4 pl-0">
                                            <div class="form-group">
                                                {!! Form::label('country', 'Country *') !!}
                                                {!! Form::select('country', ['' => 'Select Country'] + $countries, null, ['class' => 'form-control form-select select2', 'id' => 'country']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 pl-0">
                                            <div class="form-group">
                                                {!! Form::label('city', 'City *') !!}
                                                {!! Form::select('city', ['' => 'Select City'], null, ['class' => 'form-control form-select select2', 'id' => 'city']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-5 pl-0">
                                            <div class="form-group">
                                                {!! Form::label('marital_status', 'Marital Status *') !!}
                                                {!! Form::select(
                                                    'marital_status',
                                                    ['' => 'Select Marital Status', 'single' => 'Single', 'married' => 'Married'],
                                                    null,
                                                    ['class' => 'form-control form-select select2'],
                                                ) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-5 pl-0">
                                            <div class="form-group">
                                                {!! Form::label('total_experience', 'Total Experience *') !!}
                                                {!! Form::number('total_experience', null, ['class' => 'form-control', 'placeholder' => 'Total Experience']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3 pl-0">
                                            {!! Form::label('current_salary', 'Current Salary *') !!}
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-dollar-sign"></i></span>
                                                </div>
                                                {!! Form::text('current_salary', null, ['class' => 'form-control', 'placeholder' => 'Current Salary']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3 pl-0">
                                            {!! Form::label('expected_salary', 'Expected Salary *') !!}
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-dollar-sign"></i></span>
                                                </div>
                                                {!! Form::text('expected_salary', null, ['class' => 'form-control', 'placeholder' => 'Expected Salary']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3 pl-0">
                                            <div class="form-group">
                                                {!! Form::label('notice_period', 'Notice Period *') !!}
                                                {!! Form::text('notice_period', null, ['class' => 'form-control', 'placeholder' => 'Notice Period']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-8 pl-0">
                                            <div class="form-group">
                                                {!! Form::label('switching_reason', 'Switching Reason *') !!}
                                                {!! Form::text('switching_reason', null, ['class' => 'form-control', 'placeholder' => 'Switching Reason']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-5 mb-3 pl-0">
                                            {!! Form::label('linkdin', 'LinkedIn') !!}
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fab fa-linkedin-in"></i></span>
                                                </div>
                                                {!! Form::url('linkdin', null, ['class' => 'form-control', 'placeholder' => 'linkdin URL']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-5 mb-3 pl-0">
                                            {!! Form::label('github', 'Github') !!}
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fab fa-github"></i>
                                                    </span>
                                                </div>
                                                {!! Form::url('github', null, ['class' => 'form-control', 'placeholder' => 'github URL']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-5 mb-3 pl-0">
                                            {!! Form::label('behance', 'Behance') !!}
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fab fa-behance"></i>
                                                    </span>
                                                </div>
                                                {!! Form::url('behance', null, ['class' => 'form-control', 'placeholder' => 'behance URL']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-5 mb-3 pl-0">
                                            {!! Form::label('website', 'Website') !!}
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-globe"></i>
                                                    </span>
                                                </div>
                                                {!! Form::url('website', null, ['class' => 'form-control', 'placeholder' => 'website URL']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            {!! Form::label('datetime', 'Date Time For Schedule Interview *') !!}
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-calendar-alt"></i></span>
                                                </div>
                                                {!! Form::input('datetime-local', 'datetime', null, ['class' => 'form-control']) !!}
                                            </div>
                                            <div class="form-group">
                                            </div>
                                        </div>
                                        <div class="col-md-10 col-12">
                                            <div class="form-group">
                                                {!! Form::label('resume', 'Resume *') !!}
                                                <div class="custom-file">
                                                    {!! Form::file('resume', ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-10 col-12">
                                            <div class="form-group">
                                                {!! Form::label('cover_letter', 'Cover Letter *') !!}
                                                <div class="custom-file">
                                                    {!! Form::file('cover_letter', ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    {!! Form::submit('Submit Application', ['class' => 'btn btn-primary setDisabled']) !!}
                                    <a href="{{ route('jobs.data') }}" class="btn btn-danger">Cancel</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="{{ asset('admin/plugins/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/adminlte.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/demo.js') }}"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>

    <script>
        $(document).ready(function() {
            $('select').select2();
        });
    </script>

    <script>
        $('#phone').on('keypress', function(e) {
            if (!/^\d*$/.test(e.key) || $(this).val().length >= 11) {
                e.preventDefault();
            }
        });

        $('#phone').on('input', function() {
            if ($(this).val().length > 11) {
                $(this).val($(this).val().slice(0, 11));
            }
        });

        $(document).ready(function() {
            $('#candidateJobHandler').submit(function(e) {
                e.preventDefault();

                const first_name = $('input[name="first_name"]').val().trim();
                const last_name = $('input[name="last_name"]').val().trim();
                const email = $('input[name="email"]').val().trim();
                const phone = $('input[name="phone"]').val().trim();
                const age = $('input[name="age"]').val().trim();
                const country = $('select[name="country"]').val().trim();
                const city = $('select[name="city"]').val().trim();
                const gender = $('select[name="gender"]').val().trim();
                const marital_status = $('select[name="marital_status"]').val().trim();
                const total_experience = $('input[name="total_experience"]').val().trim();
                const current_salary = $('input[name="current_salary"]').val().trim();
                const expected_salary = $('input[name="expected_salary"]').val().trim();
                const switching_reason = $('input[name="switching_reason"]').val().trim();
                const notice_period = $('input[name="notice_period"]').val().trim();
                const datetime = $('input[name="datetime"]').val().trim();
                const resume = $('input[name="resume"]').val().trim();
                const cover_letter = $('input[name="cover_letter"]').val().trim();

                if (first_name === '' || last_name === '' || email === '' || phone === '' || age === '' ||
                    city === '' || gender === '' || marital_status === '' || total_experience === '' ||
                    current_salary === '' || expected_salary === '' || switching_reason === '' ||
                    notice_period === '' || datetime === '' || resume === '' || cover_letter === '') {

                    if (first_name === '') toastr.error('First Name is required.');
                    if (last_name === '') toastr.error('Last Name is required.');
                    if (email === '') toastr.error('Email is required.');
                    if (phone === '') {
                        toastr.error('Phone is required.');
                    } else if (phone.length !== 11 || !/^\d+$/.test(phone)) {
                        toastr.error('Phone number must be exactly 11 digits.');
                    } else if (!phone.startsWith('03')) {
                        toastr.error('Phone number must start with 03.');
                    }
                    if (age === '') toastr.error('Age is required.');
                    if (city === '') toastr.error('City is required.');
                    if (gender === '') toastr.error('Gender is required.');
                    if (marital_status === '') toastr.error('Marital status is required.');
                    if (total_experience === '') toastr.error('Total experience is required.');
                    if (current_salary === '') toastr.error('Current salary is required.');
                    if (expected_salary === '') toastr.error('Expected salary is required.');
                    if (switching_reason === '') toastr.error('Switching reason is required.');
                    if (notice_period === '') toastr.error('Notice period is required.');
                    if (datetime === '') toastr.error('Datetime is required.');
                    if (resume === '') toastr.error('Resume is required.');
                    if (cover_letter === '') toastr.error('Cover letter is required.');

                    return;
                }

                const button = $('input[type="submit"]');
                button.prop('disabled', true);

                $.ajax({
                    method: "POST",
                    url: "{{ route('check.email.phone') }}",
                    data: {
                        email: email,
                        phone: phone,
                        _token: '{{ csrf_token() }}'
                    }
                }).done(function(response) {
                    if (response.emailExists) {
                        toastr.error('Email already exists.');
                        button.prop('disabled', false);
                        return;
                    }
                    if (response.phoneExists) {
                        toastr.error('Phone number already exists.');
                        button.prop('disabled', false);
                        return;
                    }
                    if (!response.emailExists && !response.phoneExists) {
                        $('#loadingSpinner').show();
                        const formData = new FormData($('#candidateJobHandler')[0]);
                        const url = $('#candidateJobHandler').attr('action');
                        const token = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            method: "POST",
                            url: url,
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': token
                            }
                        }).done(function(response) {
                            toastr.success(response.message);
                            button.prop('disabled', false);
                            $('#candidateJobHandler')[0].reset();
                            $('#loadingSpinner').hide();
                            window.location.href = '/candidates/portal/apply/jobs';
                        }).fail(function() {
                            toastr.error('Application Submit Failed');
                            button.prop('disabled', false);
                            $('#loadingSpinner').hide();
                        });
                    }
                }).fail(function() {
                    toastr.error('Error checking email or phone.');
                    button.prop('disabled', false);
                });
            });

        });
    </script>
<script>
    $(document).ready(function() {
        const cities = @json($cities);
        // console.log(cities);
        $('#country').on('change', function() {
            const countryId = $(this).val();
            const countryCities = cities[countryId] || {};

            $('#city').empty();
            $.each(countryCities, function(cityId, cityName) {
                $('#city').append(new Option(cityName, cityId));
            });
        });
    });
</script>
</body>

</html>