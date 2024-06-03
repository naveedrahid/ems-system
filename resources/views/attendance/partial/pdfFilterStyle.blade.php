<style>
    body {
        font-family:font-family: "Poppins", sans-serif;
    }

    .logo {
        text-align: center;
        margin-bottom: 20px;
    }
</style>
<style>
    * {
        margin: 0;
        padding: 0;
        font-family:font-family: "Poppins", sans-serif;
    }

    html {
        font-family:font-family: "Poppins", sans-serif;
        padding: 0;
    }

    body {
        padding: 20px;
        background-color: #fff;
    }


    table {
        width: 100%;
    }

    table thead tr th {
        padding: 15px;
        font-size: 14px;
        font-weight: lighter;
    }

    table thead tr {
        background: #f7f7f7;
    }

    table.table.table-bordered thead tr th {
        font-size: 13px !important;
        font-weight: 700;
    }

    table.table.table-bordered tbody tr td {
        font-size: 13px;
    }

    table thead tr th:last-child {
        text-align: right;
    }

    table tr td {
        padding: 15px;
        vertical-align: top;
        font-size: 14px;
        border-top: 1px solid #b9b9b9;
    }

    table tr td:last-child {
        text-align: right;
    }

    table tr:last-child td {
        border-bottom: 1px solid #b9b9b9;
    }

    table thead tr th {
        padding: 10px 10px;
    }
</style>
<div class="logo">
    @if (!empty($imageHtml))
        {!! $imageHtml !!}
    @endif

    <h1 style="text-align: center;">{{ auth()->user()->name }}</h1>
    <h4 style="text-align: center;">Attendance Report Month of
        {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</h4>
</div>
