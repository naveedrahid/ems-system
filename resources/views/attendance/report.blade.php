@extends('masterLayout.app')
@section('main')
@section('page-title')
    Report
@endsection
@section('page-content')
    <div class="box">
        <div class="box-body">
            <form id="adminAttendanceFilterForm" action="{{ route('attendance.report') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select name="department_id" id="department" class="form-control">
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
                            <select name="user_id" id="user" class="form-control">
                                <option value="">Select User</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->user->id }}"
                                        data-department="{{ $employee->department_id }}">
                                        {{ $employee->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="monthYearPicker">Month and Year</label>
                            <input type="text" id="monthYear" class="form-control" autocomplete="off">
                            <input type="hidden" name="month" value="">
                            <input type="hidden" name="year" value="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary mt-4" id="filterButton">Filter</button>
                    </div>
                </div>
            </form>
            <div id="loading" style="display: none;">Loading...</div>
            <div id="attendanceTable"></div>

        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
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

            const currentDate = new Date();
            $('#monthYear').datepicker({
                format: "mm/yyyy",
                startView: "months",
                minViewMode: "months",
                autoclose: true,
                endDate: currentDate,
            }).on('changeDate', function(e) {
                const month = e.date.getMonth() + 1;
                const year = e.date.getFullYear();
                $('input[name="month"]').val(month);
                $('input[name="year"]').val(year);
            });

            $('#adminAttendanceFilterForm').on('submit', function(e) {
                e.preventDefault();

                const userId = $('#user').val();
                if (!userId) {
                    Swal.fire("Validation Error", "User name is required.", "warning");
                    return;
                }
                const monthYearVal = $('#monthYear').val();
                if (!monthYearVal) {
                    Swal.fire("Validation Error", "Month and Year are required.", "warning");
                    return;
                }

                const form = $(this);
                const url = form.attr('action');
                const data = form.serialize();

                $('#loading').show();
                $('#filterButton').prop('disabled', true);

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: data,
                    success: function(response) {
                        $('#loading').hide();
                        $('#filterButton').prop('disabled', false);
                        if (response.status === 'success') {
                            $('#attendanceTable').html(response.html);
                            Swal.fire("Success", "Attendance data fetched successfully.",
                                "success");
                        } else {
                            Swal.fire("Failed", "An error occurred while fetching the data.",
                                "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        $('#filterButton').prop('disabled', false);
                        console.error("AJAX Error: ", xhr.responseText);
                        Swal.fire("Failed", `An error occurred: ${xhr.status} ${error}`,
                            "error");
                    }
                });
            });

        });
    </script>
@endpush

@push('css')
    <style>
        #loading {
            position: fixed;
            inset: 0;
            background: #0009;
            display: grid;
            place-items: center;
            font-size: 4rem;
            color: #fff;
        }

        #attendanceFilterForm .row {
            display: flex;
            align-items: end;
        }

        .table-condensed span.month.focused.disabled {
            background: #f7f7f7;
        }
    </style>
@endpush
