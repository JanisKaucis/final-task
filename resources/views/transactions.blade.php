<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/transactions.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<div class="multi-button">
<button type="button" onclick="location.href = 'logout'">Logout</button>
<button type="button" onclick="location.href = 'account'">Back to account</button>
</div>
Hello {{ $user->name }} {{ $user->surname }}<br>
Your current account balance is: {{ $user->bank_account }} {{ $user->currency }}
@if(!empty($transactions))
    <div class="table-wrapper">
<table class="fl-table">
    <tr>
        <th>Transaction Email</th>
        <th>Transaction</th>
        <th>Transaction Date</th>
    </tr>
    @foreach( $transactions as $row)
    <tr>
        @if($row['sender_email'] == $user->email)
        <td>{{ $row['recipient_email'] }}</td>
        <td>-{{ $row['money_sent'] }}{{ $user->currency }}</td>
        @else
            <td>{{ $row['sender_email'] }}</td>
            <td>+{{ $row['money_sent'] }}{{ $user->currency }}</td>
        @endif
        <td>{{ $row['transaction_date'] }}</td>
    </tr>
    @endforeach
</table>
    </div>
@else
    You have no transactions
@endif
</body>
</html>
