{!! Form::model($holiday, [
    'url' => $route,
    'method' => $formMethod,
    'id' => $holiday->exists ? 'updateHolidays' : 'addHolidays',
]) !!}
@if ($formMethod === 'PUT')
    @method('PUT')
@endif
<div class="row">
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Holiday Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Select Holiday Type') !!}
            {!! Form::select(
                'holiday_type',
                ['' => 'Select Holiday Type'] + App\Models\Holiday::getStatusOptions(),
                old('holiday_type', $holiday->holiday_type ?? ''),
                [
                    'class' => 'form-control form-select select2',
                    'style' => 'width: 100%;',
                ],
            ) !!}
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Select Date Range:') !!}
            {!! Form::text('date', null, ['id' => 'daterange', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-12 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'description') !!}
            {!! Form::textarea('description', old('description'), [
                'id' => 'description',
                'cols' => 30,
                'rows' => 10,
                'class' => 'form-control',
            ]) !!}
        </div>
    </div>
</div>
<div class="box-footer">
    {!! Form::submit($holiday->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
