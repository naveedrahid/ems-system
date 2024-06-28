@extends('masterLayout.app')
@section('main')
@section('page-title')
    {{ $notice->exists ? 'Edit Notice' : 'Create Notice' }}
@endsection
@section('page-content')
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card small-box card-primary p-5">
                    {!! Form::model($notice, [
                        'url' => $notice->exists ? route('notices.update', $notice->id) : route('notices.store'),
                        'method' => $notice->exists ? 'PUT' : 'POST',
                        'id' => $notice->exists ? 'noticeUpdate' : 'noticeSoter',
                    ]) !!}
                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Title') !!}
                                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Notice Type') !!}
                                {!! Form::select(
                                    'notice_type',
                                    ['' => 'Select Notice Type', 'announcement' => 'Announcement', 'celebration' => 'Celebration'],
                                    null,
                                    ['class' => 'form-control form-select select2'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Department Name') !!}
                                {!! Form::select('department_id', ['' => 'Department Name', '0' => 'All'] + $department->toArray(), null, [
                                    'class' => 'form-control form-select select2',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Status') !!}
                                {!! Form::select(
                                    'status',
                                    ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'],
                                    $notice->status,
                                    ['class' => 'form-control form-select select2'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-group">
                                {!! Form::label('title', 'Description') !!}
                                {!! Form::textarea('description', old('description'), [
                                    'id' => 'myeditorinstance',
                                    'cols' => 30,
                                    'rows' => 10,
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($notice->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('notices.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
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

        $('#noticeSoter, #noticeUpdate').submit(function(e) {
            e.preventDefault();

            const name = $('input[name="name"]').val().trim();
            const noticeType = $('select[name="notice_type"]').val().trim();
            const departmentId = $('select[name="department_id"]').val().trim();
            const description = $('textarea[name="description"]').val().trim();
            
            if (name === '' || noticeType === '' || departmentId === '' || description === '') {
                if (name === '') {
                    toastr.error('Title is required.');
                }
                if (noticeType === '') {
                    toastr.error('Notice Type is required.');
                }
                if (departmentId === '') {
                    toastr.error('Department is required.');
                }
                if (description === '') {
                    toastr.error('Description is required.');
                }
                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);
            
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
                    toastr.success(response.message);
                    button.prop('disabled', false);
                    if ($(e.target).attr('id') === 'noticeSoter') {
                        $('#noticeSoter')[0].reset();
                    }
                })
                .catch(function(xhr) {
                    console.error(xhr);
                    toastr.error(response.message);
                    button.prop('disabled', false);
                });
        });

        // Update Designation

        // $('#noticeUpdate').submit(function(e) {
        //     e.preventDefault();

        //     const name = $('input[name="name"]').val().trim();
        //     const noticeType = $('select[name="notice_type"]').val().trim();
        //     const departmentId = $('select[name="department_id"]').val().trim();
        //     const description = $('textarea[name="description"]').val().trim();
        //     if (name === '' || noticeType === '' || departmentId === '' || description === '') {
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Error!',
        //             text: 'Notice Name, Notice Type, Department Name, or Description cannot be empty.',
        //         });
        //         return;
        //     }

        //     const formData = new FormData(this);
        //     formData.append('_method', 'PUT');
        //     const url = $(this).attr('action');
        //     const token = $('meta[name="csrf-token"]').attr('content');
        //     $.ajax({
        //             url: url,
        //             method: 'POST',
        //             data: formData,
        //             processData: false,
        //             contentType: false,
        //             headers: {
        //                 'X-CSRF-TOKEN': token
        //             }
        //         })
        //         .then(function(response) {
        //             console.log(response);
        //             Swal.fire({
        //                 icon: 'success',
        //                 title: 'Success!',
        //                 text: response.message,
        //             });
        //         })
        //         .catch(function(xhr) {
        //             console.error(xhr);
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Error!',
        //                 text: 'Failed to Update Notice.',
        //             });
        //         });
        // });
    });
</script>
@endpush
