@extends('layouts.auth')

@section('content')
<div class="min-vh-100 row justify-content-center align-items-center">
    <div class="card">
        <!-- <div class="card-header">{{ __('Login') }}</div> -->

        <div class="card-body text-center">
            <span class="logo">OnField</span>
            <h1>Welcome back !</h1>
            <h2>Forgot Password</h2>
            <p>Please enter mobile no to continue</p>
            <form method="POST" action="">
                @csrf

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <img src="{{ asset('public/dist/img/smartphone.svg') }}" alt="mobile">
                        </span>
                    </div>
                    <input id="mobile" type="text" class="form-control" name="mobile" placeholder="Enter Your Mobile Number" value="{{ old('mobile') }}" autofocus>

                </div>
                <div class="text-start">
                    @if($errors->any())
                    {!! implode('', $errors->all('<div><span class="text-danger text-start" role="alert"><strong>:message</strong> </span></div>')) !!}
                    @endif
                </div>
                <button type="submit" class="btn btn-primary cm-login-btn">
                    {{ __('Send OTP') }}
                </button>
                <a class="cm-forgot-password" href="{{route('login')}}">Login</a>
            </form>
        </div>
    </div>
</div>
@endsection