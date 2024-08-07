@extends('masterLayout.app')
@section('main')
@section('page-title')
    Profile Detail
@endsection
@section('page-content')
    <div class="card-body profile-box">
        <div class="btnGroup mb-4">
            <a href="{{ route('bank-details.create', ['employee_id' => $employee->id]) }}" class="btn btn-primary mr-5">Add
                Bank Detail</a>
            <a href="{{ route('documents.create', ['document_user' => $employee->id]) }}" class="btn btn-primary">Documnet
                user</a>
        </div>
        <div class="row ">
            <div class="col-md-5">
                <div class="card small-box">
                    <div class="row align-items-center ">
                        <div class="col-md-5 profile-img bg-primary p-5">
                            <div id="loadingSpinner" style="display: none; text-align: center;">
                                <i class="fas fa-spinner fa-spin fa-3x"></i>
                            </div>
                            <div class="text-center bg-primary">
                                @auth
                                    <i class="far fa-edit" id="edit-icon">
                                        <input type="file" name="employee_img" id="employee_img">
                                    </i>
                                @endauth

                                <div class="image d-block">
                                    @php
                                        $user = auth()->user();
                                    @endphp
                                    @if (!$employee->employee->employee_img && $employee->employee->gender === 'male')
                                        <img src="{{ asset('admin/images/male.jpg') }}" style="width: 100%;"
                                            class="img-circle" alt="User Image">
                                    @elseif (!$employee->employee->employee_img && $employee->employee->gender === 'female')
                                        <img src="{{ asset('admin/images/female.png') }}" style="width: 100%;"
                                            class="img-circle" alt="User Image">
                                    @elseif($employee->employee->employee_img)
                                        <img src="{{ asset('upload/' . $employee->employee->employee_img) }}"
                                            style="width: 100%;" class="img-circle" id="profileImage" alt="User Image">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 p-0">
                            <div class="info text-center">
                                <h4 class="text-bold">{{ $employee->name }}</h4>
                                <span>{{ $employee->employee->department->department_name ?? '-' }}</span><br>
                                <span>{{ $employee->employee->designation->designation_name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card small-box profile-info">
                    <div class="card-header">
                        <h4 class="text-bold">Personal Information</h4>
                    </div>
                    <div class="card-body p-0 mb-4">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody class="menual-update-1">
                                    <tr>
                                        <td class="text-bold">Name</td>
                                        <td>{{ $employee->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Phone</td>
                                        <td>{{ $employee->employee->phone_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Email Address</td>
                                        <td>{{ $employee->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Country</td>
                                        <td>{{ $employee->employee->country ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">City</td>
                                        <td>{{ $employee->employee->city ?? '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="border-bottom"></div>
                    <div class="card-header">
                        <h4 class="text-bold">Bio-Graphical Informantion</h4>
                    </div>
                    <div class="card-body p-0 mb-4">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody class="menual-update-1">
                                    <tr>
                                        <td class="text-bold">Date of birth</td>
                                        <td>{{ $employee->employee->date_of_birth ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Gender</td>
                                        <td>{{ $employee->employee->gender ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Martial Status</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">SOS</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Work in City</td>
                                        <td>{{ $employee->employee->city ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">City of Residence</td>
                                        <td>{{ $employee->employee->address ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card small-box profile-info">
                    <div class="card-header">
                        <h4 class="text-bold">Personal Information</h4>
                    </div>
                    <div class="card-body p-0 mb-4">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody class="menual-update-1">
                                    <tr>
                                        <td class="text-bold">Sub Department</td>
                                        <td>{{ $employee->employee->department->department_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Position</td>
                                        <td>{{ $employee->employee->designation->designation_name ?? '' }} </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Duty Type</td>
                                        <td>{{ $employee->job_type ?? '' }} </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Hire Date</td>
                                        <td>{{ $employee->employee->joining_date ?? '' }} </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Work Type</td>
                                        <td>{{ $employee->work_type ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Employee Type</td>
                                        <td>{{ $employee->employee->employeeType->type ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Shift Time</td>
                                        <td>{{ $employee->employee->shift->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Pay Frequency</td>
                                        <td>??</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Supervisor Name</td>
                                        <td>??</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Ist Supervisor</td>
                                        <td>??</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card small-box profile-info">
                    <div class="card-header">
                        <h4 class="text-bold">Emergency Contant</h4>
                    </div>
                    <div class="card-body p-0 mb-4">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody class="menual-update-1">
                                    <tr>
                                        <td class="text-bold">Emergency Contact</td>
                                        <td>{{ $employee->employee->emergency_phone_number ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Emergency Contact Person Name</td>
                                        <td>{{ $employee->employee->emergency_person_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Emergency Home Phone</td>
                                        <td>??</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Emergency Work Phone</td>
                                        <td>??</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="changePassword card small-box profile-info p-3">
                    <h4 class="text-bold">Change Password</h4>
                    <form action="{{ route('employees.changePassword', auth()->user()->id) }}" method="POST"
                        id="changePassword">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="logout" value="{{ route('logoutUser') }}">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-12">
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control">
                                    <small>
                                        Password must contain at least one special character, Uppercase & two numbers (@, #,
                                        !).<br>
                                        <strong>Example:</strong> Password99@
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-12">
                                <div class="form-group">
                                    <label for="new_password_confirmation">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation"
                                        id="new_password_confirmation" class="form-control">
                                    <span id="password-match-message" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>








    {{-- <div class="container">
        <div class="row"> --}}
    {{-- <div class="col-md-6">
                <div class="personalInfo">
                    <h3>Employee Personal Info</h3>
                    <p><strong>Name:</strong> {{ $employee->name ?? '' }}</p>
                    <p><strong>Father Name:</strong> {{ $employee->employee->fater_name ?? '' }}</p>
                    <p><strong>Address:</strong> {{ $employee->employee->address ?? '' }}</p>
                    <p><strong>Phone Number:</strong> {{ $employee->employee->phone_number ?? '' }}</p>
                    <p><strong>Emergency Number:</strong> {{ $employee->employee->emergency_phone_number ?? '' }}</p>
                    <p><strong>Emergency Contact Name:</strong> {{ $employee->employee->emergency_person_name ?? '' }}</p>
                </div>
            </div> --}}
    {{-- <div class="col-md-6">
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
            </div> --}}
    {{-- <div class="col-md-6">
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
            </div> --}}
    {{-- <div class="col-md-6">
                
            </div>
        </div>
    </div> --}}
@endsection
@endsection

@push('css')
<style>
    div#loadingSpinner {
        position: absolute;
        left: 0;
        right: 0;
        margin: auto;
        top: 0;
        bottom: 0;
        z-index: 99;
        background: #00000036;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    div#loadingSpinner i {
        color: #fff !important;
    }

    .profile-img i.fa-edit {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 22px;
        cursor: pointer;
    }

    .profile-img i img {
        display: inline;
        font-size: 0;
    }

    .profile-img i input[type="file"] {
        display: inline;
        font-size: 0;
        position: absolute;
        top: 0;
        right: 0;
        color: transparent;
        background: transparent;
        outline: none;
        border: none;
        width: 30px;
        height: 30px;
        opacity: 0;
        overflow: hidden;
        cursor: pointer;
    }

    .profile-img {
        position: relative;
    }

    .profile-img img#profileImage {
        width: 100px;
        height: 100px;
        object-fit: cover;
        background-position: center center;
    }
</style>
@endpush
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
            const logout = $('input[name="logout"]').val().trim();
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
                    if (response.logout) {
                        window.location.href = '/login';
                    }
                })
                .fail(function(err) {
                    console.error(err);
                    toastr.error('Failed to change password.');
                    button.prop('disabled', false);
                });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#employee_img').on('change', function(e) {
            const file = e.target.files[0];
            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
            const maxSize = 1 * 1024 * 1024; // 1 MB

            if (!allowedTypes.includes(file.type)) {
                toastr.error('Only PNG, JPEG, JPG, and WEBP formats are allowed.');
                return;
            }

            if (file.size > maxSize) {
                toastr.error('File size must be less than 1 MB.');
                return;
            }

            const formData = new FormData();
            formData.append('employee_img', file);
            formData.append('_method', 'PUT');
            formData.append('_token', '{{ csrf_token() }}');

            $('#loadingSpinner').show();

            $.ajax({
                method: 'POST',
                url: "{{ route('employees.image') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    toastr.success(response.message);
                    $('#profileImage').attr('src', '/upload/' + response.image);
                    $('#loadingSpinner').hide();
                },
                error: function(response) {
                    toastr.error('An error occurred while updating the profile image.');
                    $('#loadingSpinner').hide();
                }
            });
        });

    });
</script>
@endpush
