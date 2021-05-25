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
Your Secret Key: <br>
{{ $secretKey }} <br>
Your QR Code: <br>
{{ $qrCode }} <br>
<form method="post">
@csrf <!-- {{ csrf_field() }} -->
    <label for="verify">Verify QR Code</label>
    <input type="text" id="verify" name="verify">
    <input type="submit" name="send" value="Verify">
</form>
@if(!empty($error))
{{ $error }}
@endif
</body>
</html>
