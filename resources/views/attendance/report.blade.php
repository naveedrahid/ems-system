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
                    <th width="15%">Name</th>
                    <th width="15%">Date</th>
                    <th width="10%">Check In</th>
                    <th width="10%">In Status</th>
                    <th width="10%">Check Out</th>
                    <th width="10%">Out Status</th>
                    <th width="5%">Hours</th>
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
            const monthYearVal = $('#monthYearPicker').val();
            const departmentId = $('#department').val();
            
            if (!departmentId) {
                alert('Please select a department.');
                return;
            }
            if (!userId) {
                alert('Please select a User Name.');
                return;
            }
            if (!monthYearVal) {
                alert('Please select a month and year.');
                return;
            }


            const monthYear = $('#monthYearPicker').val().split('/');
            const month = monthYear[0];
            const year = monthYear[1];

            $.ajax({
                url: '{{ route('filter.attendance.report') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    department_id: $('#department').val(),
                    user_id: userId,
                    month: month,
                    year: year
                },
                success: function(data) {
                    const attendanceTable = $('#attendanceTable');
                    attendanceTable.empty();

                    data.days.forEach(day => {
                        const attendanceData = day.attendanceData || {};
                        const checkInTime = attendanceData.check_in ? moment(
                            attendanceData.check_in, 'HH:mm:ss').format(
                            'h:mm A') : '-';
                        const checkOutTime = attendanceData.check_out ? moment(
                            attendanceData.check_out, 'HH:mm:ss').format(
                            'h:mm A') : '-';
                        const totalOvertime = attendanceData.total_overtime !==
                            null ? attendanceData.total_overtime : '-';
                        const isWeekend = moment(day.date).day() === 6 || moment(day
                            .date).day() === 0;
                        const userName = attendanceData.user ? attendanceData.user
                            .name : '';

                        const checkInStatusHtml = attendanceData.check_in_status ? (
                            attendanceData.check_in_status === 'Late In' ?
                            '<span class="btn btn-warning btn-xs">' +
                            attendanceData.check_in_status + '</span>' :
                            attendanceData.check_in_status === 'Early In' ?
                            '<span class="btn btn-info btn-xs">' +
                            attendanceData.check_in_status + '</span>' :
                            '<span class="btn btn-primary btn-xs">' +
                            attendanceData.check_in_status + '</span>'
                        ) : '-';

                        const checkOutStatusHtml = attendanceData.check_out_status ?
                            (
                                attendanceData.check_out_status === 'Early Out' ?
                                '<span class="btn bg-orange btn-xs">' +
                                attendanceData.check_out_status + '</span>' :
                                attendanceData.check_out_status === 'Late Out' ?
                                '<span class="btn bg-navy btn-xs">' +
                                attendanceData.check_out_status + '</span>' :
                                '<span class="btn btn-primary btn-xs">' +
                                attendanceData.check_out_status + '</span>'
                            ) : '-';

                        const dayRow = `
                        <tr>
                            <td>${ userName}</td>
                            <td><span class="currentDate">${day.displayDate}</span></td>
                            <td>${checkInTime}</td>
                            <td>${checkInStatusHtml}</td>
                            <td>${checkOutTime}</td>
                            <td>${checkOutStatusHtml}</td>
                            <td>${attendanceData.check_out ? showEmployeeTime(attendanceData.check_in, attendanceData.check_out) : '-'}</td>
                            <td>${totalOvertime ? totalOvertime : '-'}</td>
                            <td>${isWeekend ? '<span class="btn btn-warning btn-xs">Holiday</span>' : (day.date && moment(day.date).isAfter(moment()) ? '-' : attendanceData.status ? '<span class="btn btn-success btn-xs">' + textFormating(attendanceData.status) + '</span>' : '<span class="btn btn-danger btn-xs">Absent</span>')}</td>
                        </tr>
                    `;
                        attendanceTable.append(dayRow);
                    });
                }
            });
        });

        function showEmployeeTime(checkIn, checkOut) {
            const checkInTime = moment(checkIn, 'HH:mm:ss');
            const checkOutTime = moment(checkOut, 'HH:mm:ss');
            const duration = moment.duration(checkOutTime.diff(checkInTime));
            return `${duration.hours()}h ${duration.minutes()}m`;
        }

        function textFormating(text) {
            return text.charAt(0).toUpperCase() + text.slice(1);
        }

        function calculateOvertime(checkIn, checkOut) {
            const start = moment(checkIn);
            const end = moment(checkOut);
            const totalHours = moment.duration(end.diff(start)).asHours();
            return totalHours > 8 ? (totalHours - 8).toFixed(2) : '0.00';
        }
    });
</script>
@endpush
