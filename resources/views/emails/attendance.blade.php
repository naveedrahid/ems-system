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
        .content {
            background-color: #ffffff;
            border: 1px solid #e5e5e5;
            max-width: 65%;
            margin: 60px auto 30px;
            border-top: 3px solid;
            border-image: linear-gradient(to right, #8e2de2, #4a00e0) 1;
            text-align: center;
            padding: 40px 20px;
        }
        h1 {
            color: #000;
            font-size: 28px;
            font-weight: 400;
            margin-bottom: 5px;
        }
        h2 {
            color: #999;
            font-size: 16px;
            font-weight: 300;
            margin-bottom: 30px;
        }
        p {
            color: #666;
            font-size: 14px;
            font-weight: 300;
            margin: 0 0 40px;
            line-height: 22px;
        }
        .status {
            display: inline-block;
            color: #fff;
            border-radius: 3px;
            padding: 3px 5px;
            font-size: 12px;
            line-height: 1.5;
        }
        .btn-primary {
            background: linear-gradient(to right, #8e2de2, #4a00e0);
            border: none;
            color: #fff;
            font-weight: 200;
            padding: 10px 20px;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-block;
        }
        footer {
            text-align: center;
            margin-top: 20px;
        }
        small {
            color: #bbb;
            font-size: 12px;
        }
        a {
            color: #bbb;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Dear, {{ $employeeName }}!</h1>
        
        @if ($checkType == 'Check-In')
            <h2>Your Check In Time is: {{ $checkTime }}</h2>
            <p>Status: 
                @if ($checkStatus == 'Late In')
                    <span
                        style="color: #fff;background-color: #d58512;border-color: #985f0d;border-radius: 3px;padding:3px 5px;font-size: 12px;line-height: 1.5;">{{ $checkStatus }}</span>
                @elseif($checkStatus == 'Early In')
                    <span
                        style="color: #fff;background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;padding:3px 5px;font-size: 12px;line-height: 1.5;">{{ $checkStatus }}</span>
                @elseif($checkStatus == 'In')
                <span
                    style="color: #fff;background-color: #204d74;border-color: #122b40;border-radius: 3px;padding:3px 5px;font-size: 12px;line-height: 1.5;">{{ $checkStatus }}</span>
                @endif
            </p>
            <p>Thank you for checking in! Your attendance has been recorded successfully. Have a great day!</p>
        @elseif ($checkType == 'Check-Out')
            <h2>Your Check Out Time is: {{ $checkTime }}</h2>
            <p>Status: 
                @if ($checkStatus == 'Early Out')
                <span
                    style="color: #fff;background-color: #ff851b;border-color: #f08323;border-radius: 3px;padding:3px 5px;font-size: 12px;line-height: 1.5;">{{ $checkStatus }}</span>
                @elseif($checkStatus == 'Late Out')
                    <span
                        style="color: #fff;background-color: #001f3f;border-color: #001f3f;border-radius: 3px;padding:3px 5px;font-size: 12px;line-height: 1.5;">{{ $checkStatus }}</span>
                @elseif($checkStatus == 'Out')
                    <span
                        style="color: #fff;background-color: #337ab7;border-color: #275f90;border-radius: 3px;padding:3px 5px;font-size: 12px;line-height: 1.5;">{{ $checkStatus }}</span>
                @endif
            </p>
            <p>Thank you for checking out! Your attendance has been recorded successfully. Have a great day!</p>
        @endif
    </div>
    <footer>
        <small>Copyright 2022-2024 | All Rights Reserved. Powered by </small>
        <small><a href="https://pixelz360.com.au/" target="_blank">Pixelz360</a></small>
    </footer>
</body>

</html>
