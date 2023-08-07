<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{asset('public/dist/img/favicon-32x32.png')}}">
    <title>{{config('app.name')}}</title>
    @include('admin.layouts.styles')
</head>

<body class="cm-layout cm-login-page">
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>
</body>

<script src="{{ asset('public/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="{{asset('public/asset/js/auth.js')}}"></script>
<script src="{{asset('public/asset/js/common.js')}}"></script>
<script>
    $('input, :input').attr('autocomplete', 'off');
</script>
</html>