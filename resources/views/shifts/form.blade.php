{!! Form::model($shift, [
    'url' => $route,
    'method' => $formMethod,
    'id' => $shift->exists ? 'updateShift' : 'addShift',
]) !!}

@if ($shift->exists === 'PUT')
    @method('PUT')
@endif
<div class="row">
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Shift Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
            <div id="nameError" class="text-danger"></div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Open Time') !!}
            {!! Form::time('opening', null, ['class' => 'form-control']) !!}
            <div id="openingError" class="text-danger"></div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Close Time') !!}
            {!! Form::time('closing', null, ['class' => 'form-control']) !!}
            <div id="closingError" class="text-danger"></div>
        </div>
    </div>
</div>
<div class="box-footer">
    {!! Form::submit($shift->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
