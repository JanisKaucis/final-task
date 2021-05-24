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
<button type="button" onclick="location.href = 'stocks'">My stocks</button>
<br>
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
                <td>0{{ $currency }}</td>
            @else
                <td>{{ $deposit }}{{$currency}}</td>
            @endif
            @if(empty($balance))
                <td>0{{$currency}}</td>
            @else
                <td>{{ $balance }}{{$currency}}</td>
            @endif
        </tr>
    </table>

    Your attached account balance is {{ $parent_account_balance }}{{ $parent_account_currency }}
    <form method="post">
    @csrf <!-- {{ csrf_field() }} -->
        <label for="add">Deposit money: </label>
        <input type="text" id="add" name="add">
        <input type="submit" name="deposit" value="Deposit">
        @if(!empty($amountError))
            {{ $amountError }}
        @endif
    </form>
    <form method="post">
    @csrf <!-- {{ csrf_field() }} -->
        <label for="remove">Withdraw money: </label>
        <input type="text" id="remove" name="remove">
        <input type="submit" name="withdraw" value="Withdraw">
        @if(!empty($withdrawError))
            {{ $withdrawError }}
        @endif
    </form>
    <br>
    @if($balance>0)
        Balance: {{ round($balanceInUsd,2) }} USD <br>
        Buy Stocks <br>
        <form method="post">
        @csrf <!-- {{ csrf_field() }} -->
            <label for="symbol">Enter Company symbol</label>
            <input type="text" id="symbol" name="symbol" class="@error('symbol') is-invalid @enderror">
            <input type="submit" name="find" value="Find">
            @error('symbol')
            {{ $message }}
            @enderror
        </form>

        @if(!empty($companyName))
            <table style="width:80%">
                <tr>
                    <th>Company Name</th>
                    <th>Stock Price</th>
                    <th>Ticker</th>
                    <th>Logo</th>
                </tr>
                <tr>
                    <td>{{ $companyName }}</td>
                    <td>{{ $stockPrice }} USD</td>
                    <td>{{ $companyTicker }}</td>
                    <td><img height="50" src="{{ $companyLogo }}" alt="Company Logo"></td>
                </tr>
            </table>
            @if(!empty($infoMessage))
                {{ $infoMessage }}
            @endif
            <form method="post">
            @csrf <!-- {{ csrf_field() }} -->
                <label for="amount">Enter Amount</label>
                <input type="text" id="amount" name="amount" class="@error('amount') is-invalid @enderror">
                <input type="submit" name="buy" value="Buy">
                @error('amount')
                {{ $message }}
                @enderror
                @if(!empty($buyError))
                    {{ $buyError }}
                @endif
            </form>
            @if(!empty($successMessage))
                {{ $successMessage }}
            @endif
        @endif
    @endif
@endif
</body>
</html>
