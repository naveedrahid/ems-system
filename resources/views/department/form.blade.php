@extends('masterLayout.app')
@section('main')
@section('page-title')
    <h1>{{ $department->exists ? 'Edit Department' : 'Create Department' }}</h1>
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    {!! Form::model($department, [
                        'url' => $department->exists ? route('department.update', $department->id) : route('department.store'),
                        'method' => $department->exists ? 'PUT' : 'POST',
                        'id' => $department->exists ? 'departmentDataUpdate' : 'departmentData',
                    ]) !!}
                    <div class="mb-3 form-group">
                        {!! Form::label('department_name', 'Department Name') !!}
                        {!! Form::text('department_name', null, ['class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('status', 'Department Status') !!}
                        {!! Form::select(
                            'status',
                            ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'],
                            $department->status,
                            ['class' => 'form-control form-select select2', 'required'],
                        ) !!}
                    </div>

                    <div class="box-footer">
                        {!! Form::submit($department->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('department.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    {!! Form::close() !!}
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

        // Create Department
        $('#departmentData, #departmentDataUpdate').submit(function(e) {
            e.preventDefault();

            const dp_name = $('input[name="department_name"]').val().trim();
            const dp_status = $('select[name="status"]').val().trim();
            if (dp_name === '' || dp_status === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Name or status cannot be empty.',
                });
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
                    console.log(response);
                    toastr.success(response.message);
                    button.prop('disabled', false);
                    if ($(e.target).attr('id') === 'departmentData') {
                        $('#departmentData')[0].reset();
                    }
                })
                .catch(function(err) {
                    console.error(err);
                    toastr.error('Failed to save Department.');
                    button.prop('disabled', false);
                });
        });
    });
</script>
@endpush
