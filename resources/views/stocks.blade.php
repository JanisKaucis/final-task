<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/stocks.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<div class="multi-button">
    <button type="button" onclick="location.href = 'logout'">Logout</button>
    <button type="button" onclick="location.href = 'depositAccount'">Deposit Account</button>
</div>
@if($stocks->count() > 0)
    <div class="table-wrapper">
        <table class="fl-table">
            <tr>
                <th>Company name</th>
                <th>Symbol</th>
                <th>Price at buy</th>
                <th>Amount</th>
                <th>Total price</th>
                <th>Current price</th>
                <th>Logo</th>
            </tr>

            @foreach($stocks as $stock)
                <tr>
                    <td>{{ $stock->company_name }}</td>
                    <td>{{ $stock->symbol }}</td>
                    <td>{{ $stock->price_at_buy }} USD</td>
                    <td>{{ $stock->amount }}</td>
                    <td>{{ $stock->total_price }} USD</td>
                    <td>{{ $stock->current_price }} USD</td>
                    <td><img height="50px" src="{{ $stock->logo }}" alt="Stock Logo"></td>
                </tr>
            @endforeach
        </table>
    </div>
    <br>
    Sell Stocks <br>
    <div class="form-style-8">
    <form method="post">
    @csrf <!-- {{ csrf_field() }} -->
        <label for="symbol">Enter Company logo</label>
        <input type="text" id="symbol" name="symbol" class="@error('symbol') is-invalid @enderror">
        @if(!empty($symbolError))
            {{ $symbolError }}
        @endif
        @error('symbol')
        {{ $message }}
        @enderror <br>
        <label for="amount">Enter Amount</label>
        <input type="text" id="amount" name="amount" class="@error('amount') is-invalid @enderror">
        @if(!empty($amountError))
            {{ $amountError }}
        @endif
        @error('amount')
        {{ $message }}
        @enderror <br>
        <input type="submit" name="sell" value="Sell">
    </form>
    </div>
@else
    No stocks available
@endif

</body>
</html>
