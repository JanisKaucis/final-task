<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<div class="login-box">
    <form method="post">
    @csrf <!-- {{ csrf_field() }} -->
        <div class="user-box">
            <label for="email">Email</label>
            <br>
            <input type="text" name="email" id="email" class="@error('email') is-invalid @enderror">
            @error('email')
            {{ $message }}
            @enderror
        </div>
        <div class="user-box">
            <label for="password">Password</label>
            <br>
            <input type="password" name="password" id="password" class="@error('password') is-invalid @enderror">
            @error('password')
            {{ $message }}
            @enderror
        </div>
        <input type="submit" name="login" value="Login">
        <button class="register" type="button" onclick="location.href = '/register'">Register</button>
    </form>
    @if(!empty($loginErr))
        {{ $loginErr }}
    @endif
</div>
</body>
</html>
