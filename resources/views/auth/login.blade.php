@extends('layouts.auth')

@section('content')
<div class="min-vh-100 row justify-content-center align-items-center">
    <div class="card">
        <!-- <div class="card-header">{{ __('Login') }}</div> -->

        <div class="card-body text-center">
            <span class="logo">OnField</span>
            <h1>Welcome back !</h1>
            <h2>Sign In</h2>
            <p>Welcome Back, Please sign in to continue</p>
            <form method="POST" id="loginFrm" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <img src="{{ asset('public/dist/img/smartphone.svg') }}" alt="mobile">
                        </span>
                    </div>
                    <input id="email" type="text" class="form-control" name="mobile" placeholder="Enter Your Mobile Number" value="{{ old('mobile') }}" autofocus>

                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <img src="{{ asset('public/dist/img/password.svg') }}" alt="mobile">
                        </span>
                    </div>
                    <input id="password" type="password" class="form-control" name="password" placeholder="Enter Your Password">


                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="far fa-eye-slash"></i>
                        </span>
                    </div>
                </div>
                <div class="py-3 text-start">
                    @if($errors->any())
                    {!! implode('', $errors->all('<div><span class="text-danger text-start" role="alert"><strong>:message</strong> </span></div>')) !!}
                    @endif
                </div>
                <a class="cm-forgot-password" href="{{route('password.request')}}">Forgot password?</a>
                <button type="submit" class="btn btn-primary cm-login-btn">
                    {{ __('Login') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection