<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employee Check-In Notification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap');

        body {
            background-color: #f9f9f9;
            font-family: 'Poppins', sans-serif;
            padding: 10px;
        }

        .container {
            background-color: #f7f7f7;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 20px;
        }

        .grid-item {
            padding: 15px;
            border: 2px solid #cccc;
            border-radius: 4px;
            background-color: #FFF;
        }

        .label {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Dear, {{ $employeeName }}!</h1>
        <div class="grid-container">
            <div class="grid-item label">Leave Type:</div>
            <div class="grid-item">{{ $leaveType }}</div>
            <div class="grid-item label">Start Date:</div>
            <div class="grid-item">{{ $startDate }}</div>
            <div class="grid-item label">End Date:</div>
            <div class="grid-item">{{ $endDate }}</div>
            <div class="grid-item label">Reason:</div>
            <div class="grid-item">{{ $reason }}</div>
            <div class="grid-item label">Total Days:</div>
            <div class="grid-item">{{ $totalDays }}</div>
        </div>
    </div>
    <footer>
        <small>Copyright 2022-2024 | All Rights Reserved. Powered by </small>
        <small><a href="https://pixelz360.com.au/" target="_blank">Pixelz360</a></small>
    </footer>
</body>

</html>
