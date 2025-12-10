<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $site_name }} - Admin Login</title> <!-- এখানে পরিবর্তন করা হয়েছে -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6C5CE7; --secondary: #A29BFE; --dark: #0F0F1A;
            --light: #F5F6FA; --danger: #D63031; --gold: #FFD700;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body {
            background-color: #0F0F1A; color: #F5F6FA; display: flex;
            justify-content: center; align-items: center; min-height: 100vh;
        }
        .login-container {
            width: 100%; max-width: 400px; padding: 30px;
            background: rgba(13, 13, 26, 0.9); backdrop-filter: blur(10px);
            border-radius: 10px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header img {
            width: 80px; height: 80px; margin-bottom: 15px; border-radius: 50%;
            border: 3px solid var(--gold);
        }
        .login-header h2 { color: var(--gold); font-size: 24px; }
        .login-header p { color: var(--secondary); font-size: 14px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; color: var(--secondary); font-weight: 500; }
        .form-control {
            width: 100%; padding: 12px 15px; background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px;
            color: white; font-size: 14px; transition: all 0.3s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); background: rgba(108, 92, 231, 0.1); }
        .input-icon { position: relative; }
        .input-icon i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--secondary); }
        .input-icon input { padding-left: 45px; }
        .btn { width: 100%; padding: 12px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; transition: all 0.3s; margin-top: 10px; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #5a4bc2; }
        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .alert-danger { background: rgba(214, 48, 49, 0.2); color: var(--danger); border-left: 3px solid var(--danger); }
        .forgot-password { text-align: right; margin-top: -15px; margin-bottom: 20px; }
        .forgot-password a { color: var(--secondary); font-size: 13px; text-decoration: none; }
        .forgot-password a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('admin.png') }}" alt="{{ $site_name }} Logo">
            <h2>Admin Login</h2>
            <p>Sign in to access the dashboard</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" value="{{ old('username') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
            </div>
            <div class="forgot-password"><a href="#">Forgot Password?</a></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
    </div>
</body>
</html>