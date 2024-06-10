@extends('masterLayout.app')
@section('main')
@section('page-title')
    <h1>{{ $bankDetail->exists ? 'Edit Bank Detail' : 'Create Bank Detail' }}</h1>
@endsection
@section('page-content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row justify-content-center">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    {!! Form::model($bankDetail, [
                        'url' => $route,
                        'method' => $formMethod,
                        'id' => $bankDetail->exists ? 'updateBankDetails' : 'addBankDetails',
                    ]) !!}

                    @if ($formMethod === 'PUT')
                        @method('PUT')
                    @endif

                    <div class="mb-3 form-group">
                        {!! Form::hidden('user_id', $userId ?? '', ['id' => 'user_id']) !!}
                    </div>
                    <div class="mb-3 form-group">

                        {!! Form::label('title', 'Select Employee') !!}

                        @if ($employeeId && $employeeName)

                            {!! Form::hidden('employee_id', $employeeId ?? '', ['id' => 'employee_id']) !!}
                            
                            {!! Form::text('employee_name', $employeeName, [
                                'class' => 'form-control',
                                'readonly' => 'readonly',
                                'placeholder' => $employeeName,
                                'id' => 'employee_name',
                            ]) !!}

                        @else

                            {!! Form::select(
                                'employee_id',
                                ['' => 'Select User Name'] + $employees->pluck('name', 'id')->toArray(),
                                $employeeId ?? null,
                                ['class' => 'form-control form-select select2', 'id' => 'employee_id'],
                            ) !!}
                            
                        @endif
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Bank Name') !!}
                        {!! Form::text('bank_name', null, ['class' => 'form-control']) !!}
                        <div id="bank_nameError" class="text-danger"></div>
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Account Title') !!}
                        {!! Form::text('account_title', null, ['class' => 'form-control']) !!}
                        <div id="account_titleError" class="text-danger"></div>
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Account Number') !!}
                        {!! Form::text('account_number', null, ['class' => 'form-control']) !!}
                        <div id="account_numberError" class="text-danger"></div>
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'IBN') !!}
                        {!! Form::text('ibn', null, ['class' => 'form-control']) !!}
                        <div id="ibnError" class="text-danger"></div>
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Branch Code') !!}
                        {!! Form::text('branch_code', null, ['class' => 'form-control']) !!}
                        <div id="branch_codeError" class="text-danger"></div>
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'branch Address') !!}
                        {!! Form::textarea('branch_address', old('branch_address'), [
                            'id' => 'branch_address',
                            'cols' => 30,
                            'rows' => 10,
                            'class' => 'form-control',
                        ]) !!}
                        <div id="branch_addressError" class="text-danger"></div>
                    </div>
                    <div class="mb-3 form-group">
                        {!! Form::label('title', 'Status') !!}
                        {!! Form::select(
                            'status',
                            ['' => 'Select Status', 'active' => 'Active', 'deactive' => 'Deactive'],
                            $bankDetail->status,
                            ['class' => 'form-control form-select select2'],
                        ) !!}
                        <div id="statusError" class="text-danger"></div>
                    </div>

                    <div class="box-footer">
                        {!! Form::submit($bankDetail->exists ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('bank-details.index') }}" class="btn btn-danger">Cancel</a>
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
        function setUserFromEmployeeId(selectedEmployeeId) {
            var selectedEmployee = {!! json_encode($employees->toArray()) !!}.find(function(employee) {
                return employee.id == selectedEmployeeId;
            });

            if (selectedEmployee) {
                $('#user_id').val(selectedEmployee.user_id);
            } else {
                $('#user_id').val('');
            }
        }
        $('select[name="employee_id"]').change(function() {
            setUserFromEmployeeId($(this).val());
        });

        setUserFromEmployeeId($('select[name="employee_id"]').val());

        $('#addBankDetails, #updateBankDetails').submit(function(e) {
            e.preventDefault();

            const employee_id = $('select[name="employee_id"]').length ?
                $('select[name="employee_id"]').val().trim() :
                $('input[name="employee_id"]').val().trim();

            const bank_name = $('input[name="bank_name"]').val().trim();
            const account_title = $('input[name="account_title"]').val().trim();
            const account_number = $('input[name="account_number"]').val().trim();
            const ibn = $('input[name="ibn"]').val().trim();
            const branch_code = $('input[name="branch_code"]').val().trim();
            const branch_address = $('textarea[name="branch_address"]').val().trim();
            const status = $('select[name="status"]').val().trim();

            $('.text-danger').text('');
            const submitButton = $(this).find('input[type="submit"]');
            submitButton.prop('disabled', true);
            let hasError = false;


            if (employee_id === '') {
                $('#employee_idError').text('Employee is required.');
                hasError = true;
            }
            if (bank_name === '') {
                $('#bank_nameError').text('Bank name is required.');
                hasError = true;
            }
            if (account_title === '') {
                $('#account_titleError').text('Account title is required.');
                hasError = true;
            }
            if (account_number === '') {
                $('#account_numberError').text('Account number is required.');
                hasError = true;
            }
            if (ibn === '') {
                $('#ibnError').text('IBN is required.');
                hasError = true;
            }
            if (branch_code === '') {
                $('#branch_codeError').text('Branch code is required.');
                hasError = true;
            }
            if (branch_address === '') {
                $('#branch_addressError').text('Branch address is required.');
                hasError = true;
            }
            if (status === '') {
                $('#statusError').text('Status is required.');
                hasError = true;
            }

            if (hasError) {
                submitButton.prop('disabled', false);
                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                    url: url,
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .done(function(response) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Your Bank Details has been saved",
                        showConfirmButton: false,
                        timer: 1500,
                        text: response.message,
                    });
                    $('#addBankDetails')[0].reset();
                })
                .fail(function(xhr) {
                    console.error(xhr);
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#' + key + 'Error').text(value[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to create Bank Detail.',
                        });
                    }
                })
                .always(function() {
                    submitButton.prop('disabled', false);
                });
        });
    });
</script>
@endpush
