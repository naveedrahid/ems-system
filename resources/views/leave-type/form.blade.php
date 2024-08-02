{!! Form::model($leave_type, [
    'url' => $route,
    'method' => $formMethod,
    'id' => $leave_type->exists ? 'updateLeave' : 'addLeave',
]) !!}

@if ($formMethod === 'PUT')
    @method('PUT')
@endif
<div class="row">
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Leave Types Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'leave_name']) !!}
            <div id="leave_nameError" class="text-danger"></div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Total Leave') !!}
            {!! Form::text('default_balance', null, ['class' => 'form-control', 'id' => 'default_balance']) !!}
            <div id="default_balanceError" class="text-danger"></div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('status', 'Status') !!}
            {!! Form::select(
                'status',
                ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'],
                $leave_type->status,
                ['class' => 'form-control form-select select2', 'id' => 'status'],
            ) !!}
            <div id="statusError" class="text-danger"></div>
        </div>
    </div>
    <div class="col-md-12 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Description') !!}
            {!! Form::textarea('description', old('description'), [
                'id' => 'description',
                'cols' => 30,
                'rows' => 10,
                'class' => 'form-control',
            ]) !!}
            <div id="branch_addressError" class="text-danger"></div>
        </div>
    </div>
</div>
<div class="box-footer">
    {!! Form::submit($leave_type->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
