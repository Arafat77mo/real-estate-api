<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Invoice</title>
</head>
<body>
<h1>Invoice</h1>
<p><strong>Transaction Type:</strong> {{ $transactionType }}</p>
<p><strong>Message:</strong> {{ $message }}</p>
<p><strong>Date:</strong> {{ now() }}</p>
<p>Thank you for your transaction!</p>
</body>
</html>
