<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('Pixelz360.png') }}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            background-repeat: no-repeat !important;
            background-size: cover;
            font-family: 'Roboto', sans-serif;
        }

        input {
            outline: none;
        }

        .login-main {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        .panel .fack{
            padding-bottom:30px;
        }

        .panel {
            width: 450px;
            border-radius: 20px;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
            background: #ffffff;
            text-align: center;
        }

        .panel .state {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #007bff;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            margin-bottom: 20px;
            height: 130px;
            color: white;
            font-size: 20px;
        }

        .panel .state h1 {
            font-size: 30px;
            font-weight: 700;
        }

        .panel .login:active {
            transform: translateY(2px);
        }

        .panel .state h2 {
            font-weight: 400;
        }

        .panel form {
            width: 340px;
            margin: 60px auto;
        }

        .login {
            height: 45px;
            width: 100%;
            background-color: #007BFF;
            border-radius: 45px;
            position: relative;
            line-height: 45px;
            text-align: center;
            font-weight: bold;
            color: white;
            margin-top: 10px;
            cursor: pointer;
            border: none;
            outline: none;
        }

        .panel .login:hover {
            background-color: #0026ff;
        }

        .login-main .panel .form-group input {
            width: 100% !important;
            background-color: #EBEBEB;
            border-radius: 45px;
            font-size: 13px;
            height: 45px;
            padding-left: 15px;
            width: calc(100% - 15px);
            margin-bottom: 10px;
            color: #000;
            font-size: 13px;
            font-weight: 500;
            border: none;
        }

        .panel .fack {
            margin-top: 30px;
            font-size: 14px;
        }

        .panel .fack i.fa {
            text-decoration: none;
            color: #000000;
            vertical-align: middle;
            font-size: 20px;
            margin-right: 5px;
        }

        .panel .fack a:link {
            color: #000;
        }

        .panel .fack a:visited {
            color: #000;
        }


        .form-check input#remember {
            height: 16px;
            float: unset;
        }

        .form-check label.form-check-label.ml-1 {
            width: unset !important;
            display: unset;
            margin-left: 10px;
        }

        .form-check {
            text-align: left;
        }
    </style>
</head>

<body>
    <div id="app">
        {{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('/admin/images/Pixelz360.svg') }}" class="img-fluid" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif --}}

        {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
        {{-- @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> --}}
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
