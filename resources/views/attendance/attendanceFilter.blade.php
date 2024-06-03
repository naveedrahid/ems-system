@extends('masterLayout.app')
@section('main')
@section('page-title')
    Current Month {{ date('M - Y') }}
@endsection
@section('page-content')
    <div class="box">
        <div class="box-body">
            <form id="attendanceFilterForm" action="{{route('attendance.filter')}}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" placeholder="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="monthYear">Month and Year</label>
                        <input type="text" name="monthYear" id="monthYear" class="form-control" autocomplete="off">
                        <input type="hidden" name="month" value="{{ $month }}">
                        <input type="hidden" name="year" value="{{ $year }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary mt-4">Filter</button>
                    </div>
                </div>
            </form>
            <a id="downloadLink" href="#" class="btn btn-primary btn btn-info btnPdf" style="display:none;">
                <i class="fa-solid fa-file-pdf"></i>
            </a>
            <div id="loading" style="display: none;">Loading...</div>
            <div id="attendanceTable">
                @include('attendance.employeeFilter.attendanceTable', [
                    'attendance' => $attendance,
                    'month' => $month,
                    'year' => $year,
                ])
            </div>
        </div>
    </div>
@endsection
@push('js')
<script>
    $(document).ready(function() {
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth();
        const currentYear = currentDate.getFullYear();

        $('#monthYear').datepicker({
            format: "mm/yyyy",
            startView: "months",
            minViewMode: "months",
            useCurrent: false,
            autoclose: true,
            endDate: currentDate,
            beforeShowMonth: function(date) {
                if (date.getFullYear() === currentYear && date.getMonth() === currentMonth) {
                    return false;
                }
            },
        }).on('changeDate', function(e) {
            const month = e.date.getMonth() + 1;
            const year = e.date.getFullYear();
            $('input[name="month"]').val(month);
            $('input[name="year"]').val(year);
        });

        $('#attendanceFilterForm').on('submit', function(e) {
            e.preventDefault();

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
                        $('#downloadLink').attr('href', response.download_url);
                        $('#downloadLink').show();
                        Swal.fire("Success", "Attendance data fetched successfully.", "success");
                    } else {
                        Swal.fire("Failed", "An error occurred while fetching the data.", "error");
                    }
                },
                error: function(error) {
                    $('#loading').hide();
                    $('#filterButton').prop('disabled', false);
                    Swal.fire("Failed", "An error occurred while fetching the data.", "error");
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
