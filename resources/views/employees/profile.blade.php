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

                    <form action="{{route('employees.changePassword', auth()->user()->id)}}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
