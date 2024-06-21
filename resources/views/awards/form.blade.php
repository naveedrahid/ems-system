@extends('masterLayout.app')
@section('main')
@section('page-title')
    <h1>{{ $award->exists ? 'Edit award' : 'Create award' }}</h1>
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-2"></div>
                <div class="col-md-8    ">
                    {!! Form::model($award, [
                        'url' => $route,
                        'method' => $formMethod,
                        'files' => true,
                        'id' => $award->exists ? 'updateAwardHandler' : 'createAwardHandler',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('department', 'Department') !!}
                                {!! Form::select(
                                    'department_id',
                                    $departments->pluck('department_name', 'id')->prepend('Select Department', ''),
                                    $award->user->employee->department_id ?? null,
                                    ['class' => 'form-control', 'id' => 'department'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('user', 'User') !!}
                                <select name="user_id" id="user" class="form-control">
                                    <option value="">Select User</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->user->id }}"
                                            data-department="{{ $employee->department_id }}"
                                            {{ $award->user_id == $employee->user->id ? 'selected' : '' }}>
                                            {{ $employee->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Award Name') !!}
                                {!! Form::text('award_name', $award->award_name, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Award Name') !!}
                                {!! Form::file('award_file', ['class' => 'form-control']) !!}
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
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($award->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary setDisabled']) !!}
                        <a href="{{ route('awards.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>
    </div>
@endsection
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.1.1/tinymce.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
<script>
    tinymce.init({
        selector: 'textarea#awardEditor',
        branding: false,
        plugins: 'code table lists',
        menubar: false,
        statusbar: false,
        toolbar: 'bold italic underline | fontsizeselect | forecolor | bullist numlist | alignleft aligncenter alignright | link | blocks',
    });
</script>
<script>
    document.getElementById('department').addEventListener('change', function() {
        var departmentId = this.value;
        var employeeSelect = document.getElementById('user');
        var options = employeeSelect.querySelectorAll('option[data-department]');

        // Reset user dropdown to default state
        employeeSelect.value = '';

        // Hide all user options initially
        options.forEach(function(option) {
            option.style.display = 'none';
        });

        // Show only the options that match the selected department
        options.forEach(function(option) {
            if (option.getAttribute('data-department') == departmentId) {
                option.style.display = 'block';
            }
        });
    });

    //record inser & update by AJAX
    $(document).ready(function() {
        $('#createAwardHandler, #updateAwardHandler').submit(function(e) {
            e.preventDefault();
            const userId = $('select[name="user_id"]').val().trim();
            const award_name = $('input[name="award_name"]').val().trim();
            const description = $('textarea[name="description"]').val().trim();

            let hasError = false;

            if (userId == '') {
                toastr.error('User name is required.');
                hasError = true;
            }

            if (award_name == '') {
                toastr.error('Award name is required.');
                hasError = true;
            }

            if (description == '') {
                toastr.error('Description is required.');
                hasError = true;
            }

            if (hasError) return;

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);

            $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                })
                .then((response) => {
                    toastr.success(response.message);
                    $(this)[0].reset();
                    button.prop('disabled', false);
                }).catch((err) => {
                    console.error(err);
                    toastr.error('Failed to save complaint.');
                    button.prop('disabled', true);
                });
        });
    });
</script>
@endpush
