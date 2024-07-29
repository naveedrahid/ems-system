{!! Form::model($department, [
    'url' => $department->exists ? route('department.update', $department->id) : route('department.store'),
    'method' => $formMethod,
    'id' => $department->exists ? 'departmentDataUpdate' : 'departmentData',
]) !!}
@if ($formMethod === 'PUT')
    @method('PUT')
@endif
<div class="row">
    <div class="col-md-6">
        <div class="mb-3 form-group">
            {!! Form::label('department_name', 'Department Name') !!}
            {!! Form::text('department_name', null, ['class' => 'form-control', 'required']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3 form-group">
            {!! Form::label('status', 'Department Status') !!}
            {!! Form::select(
                'status',
                ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'],
                $department->status,
                ['class' => 'form-control form-select select2', 'required'],
            ) !!}
        </div>
    </div>
    <div class="box-footer">
        {!! Form::submit($department->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
{!! Form::close() !!}