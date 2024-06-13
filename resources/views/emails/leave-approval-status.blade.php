<!DOCTYPE html>
<html>
<head>
    <title>Leave Approval</title>
</head>
<body>
    <p>Dear {{ $employeeName }},</p>
    <p>Your leave application for {{ $leaveType }} has been {{ $status }}.</p>
    <p>Total Days: {{ $totalDays }}</p>

    <footer>
        <small>Copyright 2022-2024 | All Rights Reserved. Powered by </small>
        <small><a href="https://pixelz360.com.au/" target="_blank">Pixelz360</a></small>
    </footer>
</body>
</html>
