<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/account.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<div class="multi-button">
<button type="button" onclick="location.href = 'logout'">Logout</button>
<button type="button" onclick="location.href = 'depositAccount'">Deposit Account</button>
    <button type="button" onclick="location.href = 'transactions'">previous transactions</button>
    @if(empty($google2fa))
        <button type="button" onclick="location.href = 'google2fa'">Google 2fa get</button>
    @endif
</div>
<br>
Hello {{ $name }} {{ $surname }},<br>
Your balance:
@if($bank_account == null)
    0
@else
    {{ $bank_account }}
@endif
{{ $currency }}<br>
<div class="form-style-8">
<form method="post">
    @csrf
    <label for="add">Add money to your account:</label><br>
    <input type="text" name="add" id="add" class="@error('add') is-invalid @enderror">
    @error('add')
    {{ $message }}
    @enderror<br>
    <input type="submit" name="approve" value="Add money">
</form>
</div>
<br>
Send money:
<div class="form-style-8">
<form method="post">
    @csrf
    <label for="email">Send To:</label>
    <input type="text" name="email" id="email" class="@error('email') is-invalid @enderror">
    @error('email')
    {{ $message }}
    @enderror<br>
    <label for="amount">Amount:</label>
    <input type="text" name="amount" id="amount" class="@error('amount') is-invalid @enderror">
    @error('amount')
    {{ $message }}
    @enderror<br>
    <label for="secret">Aprove payment with 2fa code:</label><br>
    <input type="text" name="secret" id="secret" class="@error('secret') is-invalid @enderror">
    @if(!empty($googleError))
        {{ $googleError }}
    @endif
    @error('secret')
    {{ $message }}
    @enderror<br>
    <input type="submit" name="send" value="Send money">
</form>
</div>
@if(!empty($emailError))
    {{ $emailError }}
@endif
@if(!empty($amountError))
    {{ $amountError }}
@endif
@if(!empty($success))
    {{ $success }}
@endif
</body>
</html>
