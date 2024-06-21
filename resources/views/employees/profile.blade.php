@extends('masterLayout.app')
@section('main')
@section('page-title')
    Profile Detail
@endsection
@section('page-content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="personalInfo">
                    <h3>Employee Personal Info</h3>
                    <p><strong>Name:</strong> {{ $employee->name ?? '' }}</p>
                    <p><strong>Father Name:</strong> {{ $employee->employee->fater_name ?? '' }}</p>
                    <p><strong>Address:</strong> {{ $employee->employee->address ?? '' }}</p>
                    <p><strong>Phone Number:</strong> {{ $employee->employee->phone_number ?? '' }}</p>
                    <p><strong>Emergency Number:</strong> {{ $employee->employee->emergency_phone_number ?? '' }}</p>
                    <p><strong>Emergency Contact Name:</strong> {{ $employee->employee->emergency_person_name ?? '' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="employeeDetail">
                    <h3>Employee Details</h3>
                    <p><strong>Joining Date:</strong> {{ $employee->employee->joining_date ?? '' }}</p>
                    <p><strong>Date of birth:</strong> {{ date('Y-m-d', strtotime($employee->employee->date_of_birth)) }}
                    </p>
                    <p><strong>City:</strong> {{ $employee->employee->city ?? '' }}</p>
                    <p><strong>Gender:</strong> {{ $employee->employee->gender ?? '' }}</p>
                    <p><strong>Department:</strong> {{ $employee->employee->department->department_name ?? '' }}</p>
                    <p><strong>Designation:</strong> {{ $employee->employee->designation->designation_name ?? '' }}</p>
                    <p><strong>Employee Type:</strong> {{ $employee->employee->employeeType->type ?? '' }}</p>
                    <p><strong>Shift:</strong> {{ $employee->employee->shift->name ?? '' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bankDetails">
                    <h3>Employee Bank Details</h3>
                    @if ($employee->employee && $employee->employee->bank->isNotEmpty())
                        @foreach ($employee->employee->bank as $bankDetail)
                            <p><strong>Bank Name:</strong> {{ $bankDetail->bank_name }}</p>
                            <p><strong>Account Title:</strong> {{ $bankDetail->account_title }}</p>
                            <p><strong>Account Number:</strong> {{ $bankDetail->account_number }}</p>
                            <p><strong>IBN:</strong> {{ $bankDetail->ibn }}</p>
                            <p><strong>Branch Code:</strong> {{ $bankDetail->branch_code }}</p>
                            <p><strong>Branch Address:</strong> {{ $bankDetail->branch_address }}</p>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="changePassword">
                    <h3>Change Password</h3>
                    <form action="{{ route('employees.changePassword', auth()->user()->id) }}" method="POST"
                        id="changePassword">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control">
                            <small>
                                Password must contain at least one special character, Uppercase & two numbers (@, #, !).<br>
                                <strong>Example:</strong> Password99@
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="form-control">
                            <span id="password-match-message" class="text-danger"></span>
                        </div>

                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        const $newPasswordInput = $('#new_password');
        const $confirmPasswordInput = $('#new_password_confirmation');
        const $messageElement = $('#password-match-message');

        function checkPasswordsMatch() {
            const newPassword = $newPasswordInput.val().trim();
            const confirmPassword = $confirmPasswordInput.val().trim();

            if (newPassword === '' || confirmPassword === '') {
                $messageElement.text('');
                $messageElement.removeClass('text-success text-danger');
            } else if (newPassword !== confirmPassword) {
                $messageElement.text('Passwords do not match');
                $messageElement.addClass('text-danger');
                $messageElement.removeClass('text-success');
            } else {
                $messageElement.text('Passwords match');
                $messageElement.addClass('text-success');
                $messageElement.removeClass('text-danger');
            }
        }

        function validatePassword(password) {
            const minLength = password.length >= 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasSpecialChar = /[@#!]/.test(password);
            const hasTwoNumbers = /(?=(.*[0-9]){2})/.test(password);

            if (!minLength) {
                toastr.error('Password must be at least 8 characters long.');
                return false;
            }
            if (!hasUpperCase) {
                toastr.error('Password must contain at least one uppercase letter.');
                return false;
            }
            if (!hasSpecialChar) {
                toastr.error('Password must contain at least one special character (@, #, !).');
                return false;
            }
            if (!hasTwoNumbers) {
                toastr.error('Password must contain at least two numbers.');
                return false;
            }

            return true;
        }

        $newPasswordInput.on('input', checkPasswordsMatch);
        $confirmPasswordInput.on('input', checkPasswordsMatch);

        $('#changePassword').submit(function(e) {
            e.preventDefault();

            const newPassword = $newPasswordInput.val().trim();
            const newPasswordConfirmation = $confirmPasswordInput.val().trim();

            if (newPassword === '' || newPasswordConfirmation === '') {
                if (newPassword === '') {
                    toastr.error('Password is required.');
                }

                if (newPasswordConfirmation === '') {
                    toastr.error('Confirm Password is required.');
                }
                return;
            }

            if (!validatePassword(newPassword)) {
                return;
            }

            if (newPassword !== newPasswordConfirmation) {
                toastr.error('Passwords do not match.');
                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const button = $('button[type="submit"]');
            button.prop('disabled', true);

            $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                })
                .done(function(response) {
                    toastr.success(response.message);
                    $('#changePassword')[0].reset();
                    button.prop('disabled', false);
                })
                .fail(function(err) {
                    console.error(err);
                    toastr.error('Failed to change password.');
                    button.prop('disabled', false);
                });
        });
    });
</script>
@endpush
