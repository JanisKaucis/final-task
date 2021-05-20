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
Generate google two factor authentication:
<button type="button" onclick="location.href = 'google2fa'">Go</button>
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
    <input type="text" name="add" id="add"><br>
    <input type="submit" name="approve" value="Add money">
</form>
Send money:
<form method="post">
    @csrf
    <label for="email">Email To:</label>
    <input type="text" name="email" id="email"><br>
    <label for="amount">Amount:</label>
    <input type="text" name="amount" id="amount"><br>
    <input type="submit" name="send" value="Send money">
</form>
</body>
</html>
