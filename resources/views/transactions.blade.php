<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<button type="button" onclick="location.href = 'logout'">Logout</button>
<button type="button" onclick="location.href = 'account'">Back to account</button>
<br>
Hello {{ $user->name }} {{ $user->surname }}<br>
Your current account balance is: {{ $user->bank_account }} {{ $user->currency }}
<table style="width:80%">
    <tr>
        <th>Recipient Email</th>
        <th>Money Sent</th>
        <th>Transaction Date</th>
    </tr>
    @foreach( $transactions as $row)
    <tr>
        <td>{{ $row['recipient_email'] }}</td>
        <td>{{ $row['money_sent'] }}</td>
        <td>{{ $row['transaction_date'] }}</td>
    </tr>
    @endforeach
</table>
</body>
</html>
