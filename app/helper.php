<?php

use App\Models\User;
use Carbon\Carbon;

if (!function_exists('isAdmin')) {
    function isAdmin($user)
    {
        return $user->role_id === 1 || $user->role_id === 2;
    }
}

if (!function_exists('getUpcomingBirthdays')) {
    function getUpcomingBirthdays()
    {
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $currentDay = $currentDate->day;

        $userBirthdays = User::orderBy('id', 'DESC')->whereHas('employee', function ($query) use ($currentMonth, $currentDay) {
            $query->whereMonth('date_of_birth', $currentMonth)
                ->whereDay('date_of_birth', '>=', $currentDay)
                ->orWhere(function ($query) use ($currentMonth) {
                    $query->whereMonth('date_of_birth', '>', $currentMonth);
                });
        })->with('employee')->get();

        $upcomingBirthdays = $userBirthdays->filter(function ($user) use ($currentMonth, $currentDay) {
            $dob = Carbon::parse($user->employee->date_of_birth);
            return $dob->month > $currentMonth || ($dob->month == $currentMonth && $dob->day >= $currentDay);
        });

        return $upcomingBirthdays;
    }
}

function calculateOvertime($check_out)
{
    $checkOutTime = Carbon::parse($check_out);
    $startTime = Carbon::createFromTime(17, 20, 0);

    if ($checkOutTime->greaterThan($startTime)) {
        $overtime = $checkOutTime->diff($startTime);
        return $overtime->h . "h : " . $overtime->i . "m";
    } else {
        return "-";
    }
}


// if (!function_exists('get_total_leave_balance')) {
//     function get_total_leave_balance($start_date, $end_date)
//     {
//         $start_date_parsed = Carbon::createFromFormat('Y-m-d', $start_date);
//         $end_date_parsed = Carbon::createFromFormat('Y-m-d', $end_date);
//         $total_days = $start_date_parsed->diffInDays($end_date_parsed) + 1;
//         return $total_days;
//     }
// }

if (!function_exists('showEmployeeTime')) {
    function showEmployeeTime($checkIn, $checkOut)
    {
        $check_in = strtotime($checkIn);
        $check_out = strtotime($checkOut);
        $totalSeconds = $check_out - $check_in;
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}


if (!function_exists('textFormating')) {
    function textFormating($text)
    {
        $status = str_replace('_', ' ', $text);
        $status = ucwords($status);
        $status = str_replace('', ' ', $status);
        return  $status;
    }
}

if (!function_exists('calculateAttendanceStatus')) {
    function calculateAttendanceStatus($checkInTime, $checkOutTime)
    {
        $lateCheckInTime = Carbon::createFromTime(8, 30, 0); // 8:30 AM
        $officeClosingTime = Carbon::createFromTime(17, 0, 0); // 5:00 PM

        $checkInTime = Carbon::createFromFormat('H:i:s', $checkInTime);
        $checkOutTime = Carbon::createFromFormat('H:i:s', $checkOutTime);

        $status = 'present';

        if ($checkInTime->greaterThan($lateCheckInTime)) {
            $status = 'late';
        }

        if ($checkOutTime->lessThan($officeClosingTime)) {
            $status = 'early_out';
        }

        if ($checkInTime->lessThan($lateCheckInTime) && $checkOutTime->greaterThan($officeClosingTime)) {
            $status = 'present';
        }

        if ($checkInTime->greaterThanOrEqualTo(Carbon::createFromTime(5, 30, 0)) && $checkOutTime->greaterThan($officeClosingTime)) {
            $status = 'ot';
        }

        $totalOvertime = 0;
        if ($checkOutTime->greaterThan($officeClosingTime)) {
            $totalOvertime = $checkOutTime->diffInMinutes($officeClosingTime);
            $status = 'ot';
        }

        return ['status' => $status, 'total_overtime' => $totalOvertime];
    }
}
