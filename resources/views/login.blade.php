<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form method="post">
@csrf <!-- {{ csrf_field() }} -->
    <label for="email">Email:</label>
    <input type="text" name="email" id="email" class="@error('email') is-invalid @enderror">
    @error('email')
    {{ $message }}
    @enderror<br>
    <label for="password">Password</label>
    <input type="text" name="password" id="password" class="@error('password') is-invalid @enderror">
    @error('password')
    {{ $message }}
    @enderror<br>
    <input type="submit" name="login" value="Login">
    <button type="button" onclick="location.href = '/register'">Register</button>
</form>
@if(!empty($loginErr))
    {{ $loginErr }}
@endif
</body>
</html>
