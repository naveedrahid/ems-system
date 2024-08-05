{!! Form::model($notice, [
    'url' => $notice->exists ? route('notices.update', $notice->id) : route('notices.store'),
    'method' => $notice->exists ? 'PUT' : 'POST',
    'id' => $notice->exists ? 'noticeUpdate' : 'noticeSoter',
]) !!}
@if ($formMethod === 'PUT')
    @method('PUT')
@endif
<div class="row">
    <div class="col-md-6 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Title') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6 col-12">
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
    <div class="col-md-6 col-12">
        <div class="mb-3 form-group">
            {!! Form::label('title', 'Department Name') !!}
            {!! Form::select('department_id', ['' => 'Department Name', '0' => 'All'] + $department->toArray(), null, [
                'class' => 'form-control form-select select2',
            ]) !!}
        </div>
    </div>
    <div class="col-md-6 col-12">
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
                'cols' => 30,
                'rows' => 10,
                'class' => 'form-control txtArea',
            ]) !!}
        </div>
    </div>
</div>
<div class="box-footer">
    {!! Form::submit($notice->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
