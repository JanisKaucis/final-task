<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/token.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<div class="login-box">
<button class="login" type="button" onclick="location.href = '/'">Login Page</button>
<form method="post">
@csrf <!-- {{ csrf_field() }} -->
    <div class="user-box">
    <label for="token">Enter your Token:</label><br>
    <input type="text" name="token" id="token" class="@error('token') is-invalid @enderror">
    @error('token')
    {{ $message }}
    @enderror
    </div>
    <input type="submit" name="login" value="Login">
</form>
@if(!empty($loginErr))
    {{ $loginErr }}
@endif
</div>
</body>
</html>
