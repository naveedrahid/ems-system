<!DOCTYPE html>

<head>
    <title>Pixelz360</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
            background-color: #f9f9f9;
            padding-right: 10px;
            padding-left: 10px;
        }

        .content {
            background-color: #ffffff;
            border-color: #e5e5e5;
            border-style: solid;
            border-width: 0 1px 1px 1px;
            max-width:65%;
            width: 100%;
            height: 420px;
            margin: auto;
            margin-top: 60.5px;
            margin-bottom: 31px;
            border-top: solid 3px #8e2de2;
            border-top: solid 3px -webkit-linear-gradient(to right, #8e2de2, #4a00e0);
            border-top: solid 3px -webkit-linear-gradient(to right, #8e2de2, #4a00e0);
            text-align: center;
            padding: 100px 0px 0px;
        }

        h1 {
            padding-bottom: 5px;
            color: #000;
            font-family: Poppins, Helvetica, Arial, sans-serif;
            font-size: 28px;
            font-weight: 400;
            font-style: normal;
            letter-spacing: normal;
            line-height: 36px;
            text-transform: none;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            color: #999;
            font-family: Poppins, Helvetica, Arial, sans-serif;
            font-size: 16px;
            font-weight: 300;
            font-style: normal;
            letter-spacing: normal;
            line-height: 24px;
            text-transform: none;
            text-align: center;
        }

        p {
            font-size: 14px;
            margin: 0px 21px;
            color: #666;
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-style: normal;
            letter-spacing: normal;
            line-height: 22px;
            margin-bottom: 40px;
        }

        .btn-primary {
            background: #8e2de2;
            background: -webkit-linear-gradient(to right, #8e2de2, #4a00e0);
            background: linear-gradient(to right, #8e2de2, #4a00e0);
            border: none;
            font-family: Poppins, Helvetica, Arial, sans-serif;
            font-weight: 200;
            font-style: normal;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
        }

        footer {
            width: 100%;
            text-align: center;
        }

        small {
            color: #bbb;
            font-family: "Poppins", sans-serif;
            font-size: 12px;
            font-weight: 400;
            font-style: normal;
            letter-spacing: normal;
            line-height: 20px;
            text-transform: none;
            margin-bottom: 5px;
            display: block;
        }

        small:last-child {
            margin-top: 20px;
        }

        a {
            color: #bbb;
            text-decoration: underline;
        }
    </style>
</head>


<h1>Employee Check-In Notification</h1>

<div class="d-flex align-items-center justify-content-center">
    <div class="content">
        <h1>Dear, {{ $employeeName }}!</h1>
        <h2>Your Check In Time is: {{ $checkInTime }}</h2>
        <p>Status:
            @if ($checkInStatus == 'Late In')
                <span
                    style="color: #fff;background-color: #d58512;border-color: #985f0d;border-radius: 3px;padding:3px 5px;font-size: 12px;line-height: 1.5;">{{ $checkInStatus }}</span>
            @elseif($checkInStatus == 'Early In')
                <span
                    style="color: #fff;background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;padding:3px 5px;font-size: 12px;line-height: 1.5;">{{ $checkInStatus }}</span>
            @elseif($checkInStatus == 'In')
                <span
                    style="color: #fff;background-color: #204d74;border-color: #122b40;border-radius: 3px;padding:3px 5px;font-size: 12px;line-height: 1.5;">{{ $checkInStatus }}</span>
            @endif
        </p>
        <p>Thank you for checking in! Your attendance has been recorded successfully. Have a great day!</p>
    </div>
</div>
<div class="d-flex align-items-center justify-content-center">
    <footer>
        <small>Copyright 2022-2024 | All Rights Reserved . Powered by </small>
        <small><a href="https://pixelz360.com.au/" target="_blank">Pixelz360</a></small>
    </footer>
</div>
</body>
</html>
