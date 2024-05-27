@extends('masterLayout.app')
@section('main')
@section('page-title')
    Report
@endsection
@section('page-content')
    <div class="box">
        <form id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" class="form-control">
                            <option value="">Select Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4" id="userContainer" style="display: none;">
                    <div class="form-group">
                        <label for="user">User</label>
                        <select id="user" class="form-control">
                            <option value="">Select User</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->user->id }}" data-department="{{ $employee->department_id }}">
                                    {{ $employee->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="monthYearPicker">Month and Year</label>
                        <input type="text" id="monthYearPicker" class="form-control" autocomplete="off">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="filterBtn">Filter</button>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead style="background-color: #F8F8F8;">
                <tr>
                    <th width="5%">ID</th>
                    <th width="10%">Name</th>
                    <th width="10%">Date</th>
                    <th width="10%">Check In</th>
                    <th width="10%">In Status</th>
                    <th width="10%">Check Out</th>
                    <th width="10%">Out Status</th>
                    <th width="10%">Hours</th>
                    <th width="5%">OT</th>
                    <th width="5%">Status</th>
                </tr>
            </thead>
            <tbody id="attendanceTable"></tbody>
        </table>
        
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('#monthYearPicker').datepicker({
            format: "mm/yyyy",
            startView: "months",
            minViewMode: "months",
            autoclose: true
        });

        $('#department').change(function(e) {
            e.preventDefault();
            const selectedDepartment = $(this).val();
            if (selectedDepartment) {
                $('#user option').each(function() {
                    const userDepartment = $(this).data('department');
                    if (userDepartment == selectedDepartment) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                $('#userContainer').show();
            } else {
                $('#userContainer').hide();
            }
        });

        $('#filterForm').submit(function(event) {
            event.preventDefault();
            const userId = $('#user').val();
            const monthYear = $('#monthYearPicker').val().split('/');
            const month = monthYear[0];
            const year = monthYear[1];

            $.ajax({
                url: '{{ route('filter.attendance.report') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: userId,
                    month: month,
                    year: year
                },
                success: function(data) {
                    const attendanceTable = $('#attendanceTable');
                    attendanceTable.empty();

                    // Loop through each day of the month
                    for (const day in data.days) {
                        const rowData = data.days[day];
                        let rowClass = '';
                        if (rowData.isHoliday) {
                            rowClass = 'bg-danger'; // Use your preferred styling for holidays
                        }

                        // Filter attendance records for the current day
                        const dayAttendance = data.attendance.filter(record => record.attendance_date === rowData.date);

                        let attendanceRow = `
                            <tr class="${rowClass}">
                                <td>${rowData.date}</td>
                                <td>${rowData.attendanceStatus}</td>
                            </tr>
                        `;

                        // If there are attendance records for the day, add them to the row
                        if (dayAttendance.length > 0) {
                            dayAttendance.forEach(attendance => {
                                const userName = attendance.user ? attendance.user.name : 'Unknown User';
                                const totalHours = attendance.total_hours !== null ? attendance.total_hours : '-';
                                attendanceRow += `
                                    <tr>
                                        <td></td> <!-- Empty cell for alignment -->
                                        <td>${userName}</td>
                                        <td>${attendance.attendance_date}</td>
                                        <td>${attendance.check_in ? attendance.check_in : '-'}</td>
                                        <td>${attendance.check_in_status ? attendance.check_in_status : '-'}</td>
                                        <td>${attendance.check_out ? attendance.check_out : '-'}</td>
                                        <td>${attendance.check_out_status ? attendance.check_out_status : '-'}</td>
                                        <td>${totalHours}</td>
                                        <td>${attendance.total_overtime}</td>
                                        <td><span class="btn btn-primary btn-xs">${attendance.status ? attendance.status : '-'}</span></td>
                                    </tr>
                                `;
                            });
                        } else {
                            attendanceRow += `
                                <tr>
                                    <td>No attendance records found for this date.</td>
                                </tr>
                            `;
                        }

                        attendanceTable.append(attendanceRow);
                    }
                }
            });
        });
    });
</script>
@endpush
