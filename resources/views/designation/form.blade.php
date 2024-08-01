{!! Form::model($designation, [
    'url' => $designation->exists ? route('designation.update', $designation->id) : route('designation.store'),
    'method' => $designation->exists ? 'PUT' : 'POST',
    'id' => $designation->exists ? 'designationDataUpdate' : 'designationData',
]) !!}
<div class="row">
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Department Name') !!}
            {!! Form::select('department_id', ['' => 'Select Department'] + $departments->toArray(), null, [
                'class' => 'form-control form-select select2',
            ]) !!}
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Designation Name') !!}
            {!! Form::text('designation_name', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Status') !!}
            {!! Form::select(
                'status',
                ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'],
                $designation->status,
                ['class' => 'form-control form-select select2'],
            ) !!}
        </div>
    </div>
</div>
<div class="box-footer">
    {!! Form::submit($designation->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
