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
    <label for="token">Enter your Token:</label>
    <input type="text" name="token" id="token" class="@error('token') is-invalid @enderror">
    @error('token')
    {{ $message }}
    @enderror<br>
    <input type="submit" name="login" value="Login">
</form>
</body>
</html>
