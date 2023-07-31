@extends('layouts.auth')

@section('content')
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="min-vh-100 row justify-content-center align-items-center">
                    <div class="card">
                        <!-- <div class="card-header">{{ __('Login') }}</div> -->

                        <div class="card-body text-center">
                            <span class="logo">OnField</span>
                            <h1>Welcome back !</h1>
                            <h2>Sign In</h2>
                            <p>Welcome Back, Please sign in to continue</p>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <img src="{{ asset('dist/img/smartphone.svg') }}" alt="mobile">
                                        </span>
                                    </div>
                                    <input id="email" type="text" class="form-control" name="mobile" placeholder="Enter Your Mobile Number" value="{{ old('mobile') }}" autofocus>
                                    
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <img src="{{ asset('dist/img/password.svg') }}" alt="mobile">
                                        </span>
                                    </div>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Enter Your Password">

                                    
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="py-3 text-start">
                                    @if($errors->any())
                                    {!! implode('', $errors->all('<div><span class="error text-start" role="alert"><strong>:message</strong> </span></div>')) !!}
                                    @endif
                                </div>
                                <a class="cm-forgot-password" href="#">Forgot password?</a>
                                <button type="submit" class="btn btn-primary cm-login-btn">
                                    {{ __('Login') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
@endsection
