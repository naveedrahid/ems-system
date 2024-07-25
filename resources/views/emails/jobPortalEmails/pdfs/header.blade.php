<!DOCTYPE html>
<html>
<head>
    <title>Job Offer</title>
    <style>
        @page {
            margin: 100px 50px;
        }
        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 100px;
            text-align: center;
        }
        footer {
            position: fixed;
            bottom: -80px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
        }
        .content {
            margin-top: 120px;
            margin-bottom: 60px;
        }
        .row {
           width: 100%;
        }
        .col-6 {
            width: 49%;
            float: left;
        }
    </style>
</head>
<body>
    <header>
        @php
            $imagePath = public_path('admin/images/Header-logo-Pixelz.svg');
            $imageData = file_get_contents($imagePath);
            $base64Image = 'data:image/svg+xml;base64,' . base64_encode($imageData);

            $borderTop = public_path('admin/images/offerbordertop.PNG');
            $borderTopData = file_get_contents($borderTop);
            $borderTopimg = 'data:image/png;base64,' . base64_encode($borderTopData);
        @endphp
        <img src="{{ $borderTopimg }}" class="img-fluid" style="margin-bottom:50px;width:100%;margin:auto;">
        <div class="row" style="margin-top:40px; margin-bottom:40px;">
            <div class="col-6" style="text-align: left;">
                <img src="{{ $base64Image }}" class="img-fluid" width="150">
            </div>
            <div class="col-6" style="text-align:right;">
                Address: 6/43, Camden St, Albion, QLD, Australia.<br>
                Contact no: +61 403 580782<br>
                Email: contact@pixelz360.com.au<br>
                Date: {{ date('j - n - y') }}
            </div>
        </div>
    </header>