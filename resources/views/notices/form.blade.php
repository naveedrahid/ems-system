@extends('masterLayout.app')
@section('main')
@section('page-title')
    <h1>{{ $notice->exists ? 'Edit Notice' : 'Create Notice' }}</h1>
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    {!! Form::model($notice, [
                        'url' => $notice->exists ? route('notices.update', $notice->id) : route('notices.store'),
                        'method' => $notice->exists ? 'PUT' : 'POST',
                        'id' => $notice->exists ? 'noticeUpdate' : 'noticeSoter',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Name') !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Notice Type') !!}
                        {!! Form::select(
                            'notice_type',
                            ['' => 'Select Notice Type', 'announcement' => 'Announcement', 'celebration' => 'Celebration'],
                            null,
                            ['class' => 'form-control form-select select2']
                        ) !!}                        
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Department Name') !!}
                        {!! Form::select('department_id', ['' => 'Department Name', '0' => 'All'] + $department->toArray(), null, [
                            'class' => 'form-control form-select select2',
                        ]) !!}
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Status') !!}
                        {!! Form::select(
                            'status',
                            ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'],
                            $notice->status,
                            ['class' => 'form-control form-select select2'],
                        ) !!}
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Description') !!}
                        {!! Form::textarea('description', old('description'), [
                            'id' => 'myeditorinstance',
                            'cols' => 30,
                            'rows' => 10,
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($notice->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('notices.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-md-2"></div>
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
        selector: 'textarea#myeditorinstance',
        branding: false,
        plugins: 'code table lists',
        menubar: false,
        statusbar: false,
        toolbar: 'bold italic underline | fontsizeselect | forecolor | bullist numlist | alignleft aligncenter alignright | link | blocks',
    });
</script>
<script>
    $(document).ready(function() {
        // Create Designation

        $('#noticeSoter').submit(function(e) {
            e.preventDefault();

            const name = $('input[name="name"]').val().trim();
            const noticeType = $('select[name="notice_type"]').val().trim();
            const departmentId = $('select[name="department_id"]').val().trim();
            const description = $('textarea[name="description"]').val().trim();
            if (name === '' || noticeType === '' || departmentId === '' || description === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Notice Name, Notice Type, Department Name, or Description cannot be empty.',
                });
                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(function(response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    });
                    $('#noticeSoter')[0].reset();
                    // window.location.reload(); // or redirect to a different page
                })
                .catch(function(xhr) {
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to create Notice.',
                    });
                });
        });

        // Update Designation

        $('#noticeUpdate').submit(function(e) {
            e.preventDefault();

            const name = $('input[name="name"]').val().trim();
            const noticeType = $('select[name="notice_type"]').val().trim();
            const departmentId = $('select[name="department_id"]').val().trim();
            const description = $('textarea[name="description"]').val().trim();
            if (name === '' || noticeType === '' || departmentId === '' || description === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Notice Name, Notice Type, Department Name, or Description cannot be empty.',
                });
                return;
            }

            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(function(response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    });
                })
                .catch(function(xhr) {
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to Update Notice.',
                    });
                });
        });
    });
</script>
@endpush
