{!! Form::model($role, [
    'url' => $route,
    'method' => $formMethod,
    'id' => $role->exists ? 'editRoleForm' : 'addRoleForm',
]) !!}
@if ($formMethod === 'PUT')
    @method('PUT')
@endif
<div class="mb-3 form-group">
    {!! Form::label('name', 'Role Name') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
    <div id="nameError" class="text-danger"></div>
</div>

<div class="box-footer">
    {!! Form::submit($role->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
