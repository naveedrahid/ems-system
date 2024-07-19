<!DOCTYPE html>
<html>

<head>
    <title>Job Offer</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .header,
        .footer {
            width: 100%;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            height:100%;
        }

        .row {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .col-6 {
            width: 48%;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    @php
        $imagePath = public_path('admin/images/Header-logo-Pixelz.svg');
        $imageData = file_get_contents($imagePath);
        $base64Image = 'data:image/svg+xml;base64,' . base64_encode($imageData);

        $borderTop = public_path('admin/images/offerbordertop.PNG');
        $borderTopData = file_get_contents($borderTop);
        $borderTopimg = 'data:image/svg+xml;base64,' . base64_encode($borderTopData);

        $borderBottom = public_path('admin/images/offerborderbottom.PNG');
        $borderBottomData = file_get_contents($borderBottom);
        $borderBottomimg = 'data:image/svg+xml;base64,' . base64_encode($borderBottomData);
    @endphp

    {{-- Header start --}}
    <div class="header">
        <img src="{{ $borderTopimg }}" style="width:100%; margin-bottom: 50px;">
        <div class="row">
            <div class="col-6 text-left">
                <img src="{{ $base64Image }}" width="150">
            </div>
            <div class="col-6 text-right">
                Address: 6/43, Camden St, Albion, QLD, Australia.<br>
                Contact no: +61 403 580782<br>
                Email: contact@pixelz360.com.au<br>
                Date: {{ date('j - n - y') }}
            </div>
        </div>
    </div>
    {{-- Header end --}}

    {{-- Content start --}}
    <div class="content">
        <h1 style="font-size: 25px;"><strong>OFFER LETTER</strong></h1>
        <p>Dear {{ $candidate->name }},</p>
        <p style="margin-bottom: 40px;">{!! $jobOffer->candidate_offer !!}</p>
        <div class="row">
            <div class="col-6 text-left">
                <p><strong>Kind Regards,</strong></p>
                <p>Senior HR Executive</p>
            </div>
            <div class="col-6 text-right">
                <div style="margin-top: 40px;">
                    <span style="border-top: solid 2px #000;"><strong>Employee Signature</strong></span>
                </div>
            </div>
        </div>
    </div>
    {{-- Content end --}}

    {{-- Footer start --}}
    <div class="footer">
        <img src="{{ $borderBottomimg }}" style="width:100%; margin-top: 50px;">
    </div>
    {{-- Footer end --}}
</body>

</html>
