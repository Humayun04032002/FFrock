<!DOCTYPE html>
<html>
<head>
    <title>{{ $match->Match_Title }} - Rules</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { background:#f5f5f5; font-family: Arial; padding:20px; }
        .box { background:white; padding:20px; border-radius:10px; }
        pre { white-space:pre-wrap; font-size:15px; line-height:1.6; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Rules & Regulations</h2>
        <pre>{{ $rulesText }}</pre>
    </div>
</body>
</html>
