@extends('layouts.app')

@section('content')
    <div class="col-lg-4 mx-auto">
        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
            {{-- <div class="brand-logo">
                <img src="../../assets/images/logo.svg" alt="logo">
            </div> --}}
            {{-- <div class="card-header">{{ __('Login') }}</div> --}}
                <form method="POST" action="{{ route('login') }}" class="pt-3">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-check-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-check-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
    
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-check">
                        <input class="form-check-input ms-0" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label ml-1" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                    <div class="form-check">
                        <button type="submit" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn w-100">
                            {{ __('Login') }}
                        </button>
    
                        {{-- @if (Route::has('password.request'))
                            <a class="btn btn-link p-0 mt-3" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
