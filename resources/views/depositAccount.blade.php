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
<button type="button" onclick="location.href = 'account'">Attached Account</button>
@if($deposit_account == false)
<form method="post">
@csrf <!-- {{ csrf_field() }} -->
    Create Deposit Account:
    <input type="submit" name="create" value="Create">
</form>
@else
Your account:
<table style="width:80%">
    <tr>
        <th>Attached account</th>
        <th>Deposit</th>
        <th>Balance</th>
    </tr>
    <tr>
        <td>{{ $parent_account }}</td>
        @if(empty($deposit))
            <td>0</td>
        @else
        <td>{{ $deposit }}</td>
        @endif
        @if(empty($balance))
            <td>0</td>
        @else
        <td>{{ $balance }}</td>
        @endif
    </tr>
</table>
@endif
</body>
</html>
