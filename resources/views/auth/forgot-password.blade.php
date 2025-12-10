<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Khelo Bangladesh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { display:flex; justify-content:center; align-items:center; min-height:100vh; background:#F0F2F5; font-family:sans-serif; }
        .wrapper { width:100%; max-width:400px; background:white; padding:40px; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.1); }
        h2 { margin:0 0 15px; text-align:center; color:#1c1e21; font-size:24px; }
        p { text-align:center; color:#606770; font-size:14px; margin-bottom:25px; }
        input { width:100%; padding:12px 15px; border:1px solid #dddfe2; border-radius:8px; font-size:15px; margin-bottom:15px; }
        .btn { width:100%; padding:12px; border:none; border-radius:8px; background:linear-gradient(45deg,#6200EE,#3700B3); color:white; font-size:16px; font-weight:600; cursor:pointer; }
        .success-msg { background:#e8f5e9; color:#2e7d32; padding:10px; border-radius:6px; font-size:14px; margin-bottom:15px; }
        .error-msg { background:#ffebee; color:#c62828; padding:10px; border-radius:6px; font-size:14px; margin-bottom:15px; }
        a { color:#6200EE; text-decoration:none; display:block; text-align:center; margin-top:20px; }
    </style>
</head>
<body>

<div class="wrapper">

    <h2>Forgot Password</h2>
    <p>Enter your email to receive a password reset link.</p>

    {{-- Success message --}}
    @if(session('status'))
        <div class="success-msg">{{ session('status') }}</div>
    @endif

    {{-- Error messages --}}
    @if ($errors->any())
        <div class="error-msg">
            @foreach ($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
        <button type="submit" class="btn">Send Reset Link</button>
    </form>

    <a href="{{ route('login') }}">← Back to Login</a>
</div>

</body>
</html>
