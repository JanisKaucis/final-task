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
<button type="button" onclick="location.href = 'logout'">Logout</button>
<br>
@if(empty($google2fa))
Generate google two factor authentication:
<button type="button" onclick="location.href = 'google2fa'">Go</button>
@endif
<br>
See previous transactions:
<button type="button" onclick="location.href = 'transactions'">Click</button>
<br>
Hello {{ $name }} {{ $surname }},<br>
Your balance:
@if($bank_account == null)
    0
@else
    {{ $bank_account }}
@endif
{{ $currency }}<br>
<form method="post">
    @csrf
    <label for="add">Add money to your account:</label><br>
    <input type="text" name="add" id="add" class="@error('add') is-invalid @enderror">
    @error('add')
    {{ $message }}
    @enderror<br>
    <input type="submit" name="approve" value="Add money">
</form>
Send money:
<form method="post">
    @csrf
    <label for="email">Email To:</label>
    <input type="text" name="email" id="email" class="@error('email') is-invalid @enderror">
    @if(!empty($emailError))
        {{ $emailError }}
    @endif
    @error('email')
    {{ $message }}
    @enderror<br>
    <label for="amount">Amount:</label>
    <input type="text" name="amount" id="amount" class="@error('amount') is-invalid @enderror">
    @if(!empty($amountError))
        {{ $amountError }}
    @endif
    @error('amount')
    {{ $message }}
    @enderror<br>
    <label for="secret">Aprove payment with 2fa code:</label><br>
    <input type="text" name="secret" id="secret" class="@error('secret') is-invalid @enderror">
    @error('secret')
    {{ $message }}
    @enderror<br>
    <input type="submit" name="send" value="Send money">
</form>
@if(!empty($success))
    {{ $success }}
@endif
</body>
</html>
