<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register - OX FF TOUR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* আপনার দেওয়া সম্পূর্ণ CSS কোড এখানে থাকবে */
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #F0F2F5; font-family: sans-serif; }
        .main-container { width: 100%; max-width: 400px; overflow: hidden; position: relative; min-height: 600px; }
        .form-container { position: absolute; width: 100%; transition: transform 0.6s ease-in-out; padding: 20px; box-sizing: border-box; }
        #signin-container { transform: translateX(0); opacity: 1; z-index: 2; }
        #signup-container { transform: translateX(100%); opacity: 0; z-index: 1; }
        .main-container.signup-mode #signin-container { transform: translateX(-100%); opacity: 0; z-index: 1;}
        .main-container.signup-mode #signup-container { transform: translateX(0); opacity: 1; z-index: 2;}
        .form-wrapper { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .form-header { text-align: center; margin-bottom: 30px; }
        .form-header h2 { margin: 0; color: #1c1e21; font-size: 24px; }
        .input-group { position: relative; margin-bottom: 20px; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #90949c; }
        .input-field { width: 100%; padding: 12px 15px 12px 45px; border: 1px solid #dddfe2; border-radius: 8px; font-size: 15px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; border: none; border-radius: 8px; background: linear-gradient(45deg, #6200EE, #3700B3); color: white; font-size: 16px; font-weight: 600; cursor: pointer; }
        .extra-links { text-align: right; margin-top: 10px; font-size: 13px; }
        .extra-links a { color: #6200EE; text-decoration: none; }
        .bottom-text { text-align: center; margin-top: 25px; font-size: 14px; color: #606770; }
        .bottom-text a { color: #6200EE; font-weight: 600; text-decoration: none; cursor: pointer; }
        .error-msg { background: #ffebee; color: #c62828; padding: 10px; border-radius: 6px; margin-bottom: 15px; text-align: left; font-size: 14px; list-style-position: inside; }
    </style>
</head>
<body>
    {{-- যদি রেজিস্ট্রেশনে কোনো এরর থাকে তাহলে সাইন-আপ ফর্ম দেখানো হবে --}}
    <div class="main-container {{ $errors->hasBag('register') ? 'signup-mode' : '' }}" id="mainContainer">

        <!-- Sign In Form -->
        <div class="form-container" id="signin-container">
            <div class="form-wrapper">
                <div class="form-header"><h2>Sign In</h2></div>
                {{-- লগইন এরর দেখানো হবে --}}
                @if ($errors->hasBag('login'))
                    <div class="error-msg">
                        <ul>
                            @foreach ($errors->login->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf {{-- CSRF টোকেন, যা ফর্মকে সুরক্ষিত রাখে --}}
                    <div class="input-group"><i class="fas fa-phone"></i><input type="text" name="number" class="input-field" placeholder="Mobile Number" value="{{ old('number') }}" required></div>
                    <div class="input-group"><i class="fas fa-lock"></i><input type="password" name="password" class="input-field" placeholder="Password" required></div>
                    <div class="extra-links"><a href="{{ route('password.request') }}">Forgot Password?</a></div>
                    <button type="submit" class="btn">Sign In</button>
                </form>
                <div class="bottom-text">Don't have an account? <a id="show-signup">Sign Up</a></div>
            </div>
        </div>

        <!-- Sign Up Form -->
<div class="form-container" id="signup-container">
    <div class="form-wrapper">
        <div class="form-header"><h2>Create Account</h2></div>

        {{-- Registration errors --}}
        @if ($errors->hasBag('register'))
            <div class="error-msg">
                <ul>
                    @foreach ($errors->register->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" class="input-field" placeholder="Full Name" value="{{ old('name') }}" required>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" class="input-field" placeholder="Email Address" value="{{ old('email') }}" required>
            </div>
            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="number" class="input-field" placeholder="Mobile Number" value="{{ old('number') }}" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="input-field" placeholder="Password" required>
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>

        <div class="bottom-text">Already have an account? <a id="show-signin">Sign In</a></div>
    </div>
</div>

    </div>

    <script>
        const mainContainer = document.getElementById('mainContainer');
        const showSignupBtn = document.getElementById('show-signup');
        const showSigninBtn = document.getElementById('show-signin');
        showSignupBtn.addEventListener('click', () => mainContainer.classList.add('signup-mode'));
        showSigninBtn.addEventListener('click', () => mainContainer.classList.remove('signup-mode'));
    </script>
</body>
</html>