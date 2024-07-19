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
                                {!! Form::label('job_id', 'Job Title') !!}
                                {!! Form::select('job_id', ['' => 'Select Job'] + $jobs->toArray(), $job_offer->job_id, [
                                    'class' => 'form-control form-select select2',
                                    'id' => 'job_id',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('candidate_id', 'Select Candidate') !!}
                                <select name="candidate_id" id="candidate_id" class="form-control form-select select2">
                                    <option value="">Select Candidate</option>
                                    @foreach ($candidates as $id => $candidate)
                                        <option value="{{ $id }}" data-job-id="{{ $candidate['job_id'] }}"
                                            style="{{ $selectedJobId != $candidate['job_id'] ? 'display:none;' : '' }}"
                                            {{ old('candidate_id', $job_offer->candidate_id) == $id ? 'selected' : '' }}>
                                            {{ $candidate['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                {!! Form::label('candidate_salary', 'Candidate Salary') !!}
                                {!! Form::text('candidate_salary', $job_offer->candidate_salary, [
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('candidate_offer', 'Candidate Offer') !!}
                                <p><small><b>Note:</b> This field is used for composing the email body that will be sent to
                                        the candidate. You can style it as you wish using HTML.</small></p>
                                {!! Form::textarea('candidate_offer', 
                                old('candidate_offer', $job_offer->candidate_offer), 
                                [
                                    'id' => 'candidateOfferEditor',
                                    'cols' => 30,
                                    'rows' => 10,
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    </div>


                    <div class="box-footer">
                        {!! Form::submit($job_offer->exists ? 'Update' : 'Send Offer', ['class' => 'btn btn-primary setDisabled']) !!}
                        <a href="{{ route('job-offers.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    a.tox-promotion-link,
    span.tox-statusbar__branding {
        display: none !important;
    }

    .small-box p>small {
        color: #000;
        margin-bottom: 15px;
    }

    span.select2-selection.select2-selection--single {
        height: 40px;
    }
</style>
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.1.1/tinymce.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    tinymce.init({
        selector: 'textarea#candidateOfferEditor',
        branding: true,
        plugins: 'code table lists',
        menubar: true,
        statusbar: true,
        toolbar: 'bold italic underline | fontsizeselect | forecolor | bullist numlist | alignleft aligncenter alignright | link | blocks',
    });
</script>


<script>
    $(document).ready(function() {
        $('#createJobOfferHandler, #updateJobOfferHandler').submit(function(e) {
            e.preventDefault();

            const job_id = $('select[name="job_id"]').val().trim();
            const candidate_id = $('select[name="candidate_id"]').val().trim();
            const candidate_salary = $('input[name="candidate_salary"]').val().trim();
            const candidate_offer = $('textarea[name="candidate_offer"]').val().trim();

            if (job_id === '' || candidate_id === '' || candidate_salary === '' || candidate_offer ===
                '') {

                if (job_id === '') toastr.error('Job is required.');
                if (candidate_id === '') toastr.error('Candidate is required.');
                if (candidate_salary === '') toastr.error('Candidate Salary is required.');
                if (candidate_offer === '') toastr.error('Candidate Offer is required.');

                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);

            $.ajax({
                    method: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .then((response) => {
                    toastr.success(response.message);
                    button.prop('disabled', false);
                    if ($(e.target).attr('id') === 'createJobOfferHandler') {
                        $('#createJobOfferHandler')[0].reset();
                    }
                }).catch((err) => {
                    console.error(err);
                    toastr.error('Failed to save JobOffer.');
                    button.prop('disabled', false);
                });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.select2').select2();

        function formatState(option) {
            if (!option.id) {
                return option.text;
            }
            if ($(option.element).data('select2-hidden')) {
                return null;
            }
            return option.text;
        }

        $('#candidate_id').select2({
            templateResult: formatState,
            templateSelection: formatState
        });

        function updateUsers(candidate_id) {
            $('#candidate_id option').not(':first').each(function() {
                const option = $(this);
                if (option.data('job-id') == candidate_id) {
                    option.data('select2-hidden', false);
                } else {
                    option.data('select2-hidden', true);
                }
            });

            $('#candidate_id').select2({
                templateResult: formatState,
                templateSelection: formatState
            });
        }

        $('#job_id').on('change', function() {
            const selectedCandidate_id = $(this).val();
            updateUsers(selectedCandidate_id);
        });

        const initialCandidate_id = $('#job_id').val();
        if (initialCandidate_id) {
            updateUsers(initialCandidate_id);
        }
    });
</script>
@endpush
