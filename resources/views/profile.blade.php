<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>My Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    .admin-badge {
    width: 30px;
    height: 30px;
    margin-left: 5px;
    vertical-align: middle;
    position: relative;
    top: -2px;
    animation: shine 2s infinite; /* glow animation */
}

    /* Shining / glowing animation for developer badge */
.developer-badge {
    width: 30px;
    height: 30px;
    margin-left: 5px; /* spacing from other badges */
    vertical-align: middle;
    position: relative;
    top: -2px;
    animation: shine 2s infinite;
}

@keyframes shine {
    0% {
        transform: scale(1);
        filter: drop-shadow(0 0 0px #FFD700);
    }
    50% {
        transform: scale(1.2);
        filter: drop-shadow(0 0 8px #FFD700);
    }
    100% {
        transform: scale(1);
        filter: drop-shadow(0 0 0px #FFD700);
    }
}

        :root {
            --header-bg-start: #ff6a6a;
            --header-bg-end: #cc0000;
            --button-bg: #cc0000;
            --bg-color: #f0f2f5;
            --card-bg: white;
            --text-color: #333;
            --light-text-color: #666;
            --fab-bg: #FDC830;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding-bottom: 85px;
        }
        .profile-header {
            background: linear-gradient(135deg, var(--header-bg-start), var(--header-bg-end));
            color: white;
            text-align: center;
            padding: 30px 20px;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
        }
        .profile-header .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            object-fit: cover;
        }
        .profile-header .user-name {
            margin: 10px 0 5px;
            font-size: 1.5em;
            font-weight: 700;
        }
        .profile-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .stat-item {
            flex: 1;
        }
        .stat-item .value {
            font-size: 1.4em;
            font-weight: bold;
        }
        .stat-item .label {
            font-size: 0.8em;
            opacity: 0.9;
            margin-top: 5px;
        }
        .main-content {
            padding: 20px;
        }
        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .menu-list-item a {
            display: flex;
            align-items: center;
            background-color: var(--card-bg);
            padding: 18px 15px;
            margin-bottom: 12px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .menu-list-item a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .menu-list-item i {
            font-size: 20px;
            color: var(--button-bg);
            width: 45px;
            text-align: center;
        }
        .logout-form {
            margin: 20px auto;
            width: 90%;
        }
        .logout-btn {
            width: 100%;
            padding: 15px;
            text-align: center;
            background-color: var(--button-bg);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .logout-btn:hover {
            background-color: #0056b3;
        }
        .fab {
            position: fixed;
            width: 56px; height: 56px;
            background: linear-gradient(45deg, var(--fab-bg), #F37335);
            border-radius: 50%;
            bottom: 90px;
            right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 999;
            text-decoration: none;
        }
        .fab i { font-size: 24px; color: white; }
        .verified-badge {
    width: 30px;
    height: 30px;
    margin-left: 0px;
    vertical-align: middle;
    position: relative;
    top: -2px; /* slightly lift it to align with text */
}
    </style>
</head>
<body>
    <div class="profile-header">
        <img src="{{ asset('assets/images/wired-flat-44-avatar-user-in-circle.gif') }}" alt="Avatar" class="avatar">
        <h2 class="user-name">
    {{ $user->Name }}

    {{-- Pro Badge: For users who played 2 or more matches --}}
    @if($user->Total_Played >= 10)
        <img src="{{ asset('assets/images/152875.png') }}" alt="Pro Badge" class="verified-badge" title="Pro Player">
    @endif

    {{-- Verified Badge: For users who won 500 or more --}}
    @if($user->Winning >= 500)
        <img src="{{ asset('assets/images/152877.png') }}" alt="Verified" class="verified-badge" title="Verified Player">
    @endif
    @if(isset($user->is_developer) && $user->is_developer == 1)
    <img src="{{ asset('assets/images/developer-badge.png') }}" 
         alt="Developer Badge" 
         class="verified-badge developer-badge"
         title="Developer">
@endif
{{-- Admin Badge: Only for admin users --}}
@if(isset($user->is_admin) && $user->is_admin == 1)
    <img src="{{ asset('assets/images/adminbadge.png') }}" 
         alt="Admin Badge" 
         class="verified-badge admin-badge"
         title="Admin">
@endif


</h2>

        <div class="profile-stats">
            <div class="stat-item">
                <div class="value">{{ $user->Total_Played }}</div>
                <div class="label">Match Played</div>
            </div>
            <div class="stat-item">
                <div class="value">৳ {{ number_format($user->Balance, 2) }}</div>
                <div class="label">Balance</div>
            </div>
            <div class="stat-item">
                <div class="value">৳ {{ number_format($user->Winning, 2) }}</div>
                <div class="label">Won</div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <ul class="menu-list">
            <li class="menu-list-item"><a href="{{ route('wallet.index') }}"><i class="fas fa-wallet"></i><span>Wallet</span></a></li>
            <li class="menu-list-item"><a href="{{ route('wallet.index') }}"><i class="fas fa-money-bill-wave"></i><span>Withdraw</span></a></li>
            <li class="menu-list-item"><a href="{{ route('referral.index') }}"><i class="fas fa-share-alt"></i><span>Refer and Earn</span></a></li>
            <li class="menu-list-item"><a href="{{ route('my.matches') }}"><i class="fas fa-history"></i><span>My Matches</span></a></li>
            <li class="menu-list-item"><a href="{{ route('leaderboard.index') }}"><i class="fas fa-chart-line"></i><span>Top Players</span></a></li>
            <li class="menu-list-item">
  <a href="{{ asset('download/oxfftour.apk') }}" target="_blank">
    <i class="fas fa-mobile"></i>
    <span>Download App</span>
  </a>
</li>


            <li class="menu-list-item"><a href="{{ route('developer.profile') }}" target="_blank"><i class="fas fa-code"></i><span>Developer Profile</span></a></li>
        </ul>
        
        {{-- লগআউটের জন্য ফর্ম ব্যবহার করা সবচেয়ে নিরাপদ --}}
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <a href="{{ $supportLink }}" class="fab" target="_blank"><i class="fas fa-headset"></i></a>
    
    @include('partials.bottom-nav')
</body>
</html>