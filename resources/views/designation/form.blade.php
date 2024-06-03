@extends('masterLayout.app')
@section('main')
@section('page-title')
    create Designation
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    {!! Form::model($designation, [
                        'url' => $designation->exists ? route('designation.update', $designation->id) : route('designation.store'),
                        'method' => $designation->exists ? 'PUT' : 'POST',
                        'id' => $designation->exists ? 'designationUpdate' : 'designationSoter',
                    ]) !!}

                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Department Name') !!}
                        {!! Form::select('department_id', ['' => 'Select Department'] + $departments->toArray(), null, [
                            'class' => 'form-control form-select select2',
                            'required',
                        ]) !!}
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Designation Name') !!}
                        {!! Form::text('designation_name', null, ['class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Status') !!}
                        {!! Form::select(
                            'status',
                            ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'],
                            $designation->status,
                            ['class' => 'form-control form-select select2', 'required'],
                        ) !!}
                    </div>
                    <div class="box-footer">
                        {!! Form::submit($designation->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('designation.index') }}" class="btn btn-danger">Cancel</a>
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
<script>
    $(document).ready(function() {
        // Create Designation

        $('#designationSoter').submit(function(e) {
            e.preventDefault();

            const departmentId = $('select[name="department_id"]').val().trim();
            const designationName = $('input[name="designation_name"]').val().trim();
            const status = $('select[name="status"]').val().trim();
            if (departmentId === '' || designationName === '' || status === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Department ID, Designation Name, or Status cannot be empty.',
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
                    $('#designationSoter')[0].reset();
                    $('#department_id').val('');
                    $('#designation_name').val('');
                    $('#status').val('');
                    // window.location.reload(); // or redirect to a different page
                })
                .catch(function(xhr) {
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to create Designation.',
                    });
                });
        });

        // Update Designation

        $('#designationUpdate').submit(function(e) {
            e.preventDefault();

            const departmentId = $('select[name="department_id"]').val().trim();
            const designationName = $('input[name="designation_name"]').val().trim();
            const status = $('select[name="status"]').val().trim();
            if (departmentId === '' || designationName === '' || status === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Department ID, Designation Name, or Status cannot be empty.',
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
                        text: 'Failed to create Designation.',
                    });
                });
        });
    });
</script>
@endpush
