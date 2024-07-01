@extends('layouts.app')

@section('content')

    <div class="col-lg-12 ">
        <div class="row justify-content-center">
            {{-- <div class="col-lg-4 mx-auto">
                <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div> --}}
            <section class="login-main">
                <div class="panel">
                    <div class="state">
                        <div class="logo" style=" height: 57.10px;">
                            <a href="javascript:;">
                                <img src="{{asset('admin/images/pixelz-logo-white.svg')}}" class="text-center" height="43" width="150">
                            </a>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('login') }}" class="pt-3">
                        @csrf
                        <div class="form-group">
                            {{-- <label for="email" class="form-check-label">{{ __('Email Address') }}</label> --}}
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{-- <label for="password" class="form-check-label">{{ __('Password') }}</label> --}}
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password" placeholder="Password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-check">
                            <input class="form-check-input ms-0" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label ml-1" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <button type="submit" class="login">
                                {{ __('Login') }}
                            </button>

                            {{-- @if (Route::has('password.request'))
                                    <a class="btn btn-link p-0 mt-3" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif --}}
                        </div>
                    </form>
                    <div class="fack">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                <i class="fa fa-question-circle"></i>
                               {{ __('Forgot password?')}}
                            </a>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
    </div>
    {{-- <div class="brand-logo">
    <img src="../../assets/images/logo.svg" alt="logo">
</div> --}}
    {{-- <div class="card-header">{{ __('Login') }}</div> --}}
@endsection
