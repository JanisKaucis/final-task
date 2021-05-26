<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/google.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
Your Secret Key: <br>
{{ $secretKey }} <br>
Your QR Code: <br>
{{ $qrCode }} <br>
<div class="form-style-8">
<form method="post">
@csrf <!-- {{ csrf_field() }} -->
    <label for="verify">Verify QR Code</label>
    <input type="text" id="verify" name="verify">
    <input type="submit" name="send" value="Verify">
</form>
</div>
@if(!empty($error))
{{ $error }}
@endif
</body>
</html>
