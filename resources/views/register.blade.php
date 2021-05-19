<!doctype html>
<html lang="en">
<head>
    <meta charset="Uid">
    <meta name="viewport"
          content="wid=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
Hello, please register
<form method="post">
@csrf <!-- {{ csrf_field() }} -->
    <div class="alert alert-danger">
    <label for="email">Email:</label>
    <input type="text" name="email"  id="email" class="@error('email') is-invalid @enderror">
    @error('email')
    {{ $message }}
    @enderror<br>
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" class="@error('name') is-invalid @enderror">
    @error('name'){{ $message }}
    @enderror<br>
    <label for="surname">Surname:</label>
    <input type="text" name="surname" id="surname" class="@error('surname') is-invalid @enderror">
        @error('surname')
        {{ $message }}
        @enderror<br>
    <label for="password">Password:</label>
    <input type="text" name="password" id="password" class="@error('password') is-invalid @enderror">
        @error('password')
        {{ $message }}
        @enderror<br>
        <label for="password_confirmation">Repeat Password:</label>
        <input type="text" name="password_confirmation" id="password_confirmation"
               class="@error('password_confirmation') is-invalid @enderror">
        @error('password_confirmation')
        {{ $message }}
        @enderror<br>
    <label for="currency">Currency:</label>
    <select name="currency" id="currency">
        <option value="AUD">AUD</option>
        <option value="BGN">BGN</option>
        <option value="BRL">BRL</option>
        <option value="CAD">CAD</option>
        <option value="CHF">CHF</option>
        <option value="CNY">CNY</option>
        <option value="CZK">CZK</option>
        <option value="DKK">DKK</option>
        <option value="GBP">GBP</option>
        <option value="HKD">HKD</option>
        <option value="HRK">HRK</option>
        <option value="HUF">HUF</option>
        <option value="IDR">IDR</option>
        <option value="ILS">ILS</option>
        <option value="INR">INR</option>
        <option value="ISK">ISK</option>
        <option value="JPY">JPY</option>
        <option value="KRW">KRW</option>
        <option value="MXN">MXN</option>
        <option value="MYR">MYR</option>
        <option value="NOK">NOK</option>
        <option value="NZD">NZD</option>
        <option value="PHP">PHP</option>
        <option value="PLN">PLN</option>
        <option value="RON">RON</option>
        <option value="RUB">RUB</option>
        <option value="SEK">SEK</option>
        <option value="SGD">SGD</option>
        <option value="THB">THB</option>
        <option value="TRY">TRY</option>
        <option value="USD">USD</option>
        <option value="ZAR">ZAR</option>
    </select><br>
    <input type="submit" name="register" value="Register">
        <button type="button" onclick="location.href = '/'">Login</button>
    </div>
</form>
@if(!empty($success))
{{ $success }}
@endif
</body>
</html>
