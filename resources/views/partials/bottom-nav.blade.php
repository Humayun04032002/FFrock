<div class="bottom-nav">
    {{-- Home Page Link --}}
    <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>

    {{-- My Matches Page Link --}}
    <a href="{{ route('my.matches') }}" class="nav-item {{ request()->routeIs('my.matches') ? 'active' : '' }}">
        <i class="fas fa-gamepad"></i>
        <span>My Match</span>
    </a>

    {{-- Leaderboard Page Link --}}
    <a href="{{ route('leaderboard.index') }}" class="nav-item {{ request()->routeIs('leaderboard.index') ? 'active' : '' }}">
        <i class="fas fa-trophy"></i>
        <span>Leaderboard</span>
    </a>

    {{-- Wallet Page Link --}}
    <a href="{{ route('wallet.index') }}" class="nav-item {{ request()->routeIs('wallet.index') ? 'active' : '' }}">
        <i class="fas fa-wallet"></i>
        <span>Wallet</span>
    </a>

    {{-- Profile Page Link --}}
    <a href="{{ route('profile.index') }}" class="nav-item {{ request()->routeIs('profile.index') ? 'active' : '' }}">
        <i class="fas fa-user"></i>
        <span>Profile</span>
    </a>
</div>

{{-- এই স্টাইলটি একটি কেন্দ্রীয় CSS ফাইলে (যেমন public/css/app.css) রাখা সবচেয়ে ভালো --}}
{{-- @once ডাইরেক্টিভ নিশ্চিত করে যে এই স্টাইল ব্লকটি একটি পেজে মাত্র একবারই লোড হবে --}}
@once
    <style>
        .bottom-nav { 
            position: fixed; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            background-color: #ffffff; 
            display: flex; 
            justify-content: space-around; 
            padding: 5px 0; 
            box-shadow: 0 -2px 10px rgba(0,0,0,0.08); 
            border-top-left-radius: 20px; 
            border-top-right-radius: 20px; 
            z-index: 1000; /* নিশ্চিত করে যেন এটি অন্য সবকিছুর উপরে থাকে */
        }
        .nav-item { 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            color: #6c757d; 
            text-decoration: none; 
            font-size: 11px; 
            padding: 8px 10px; 
            width: 19%; /* ৫টি আইটেমের জন্য সমান জায়গা */
            border-radius: 16px; 
            transition: all 0.2s ease-in-out;
        }
        .nav-item.active { 
            color: white; 
            background: #3A7BD5; /* আপনার পছন্দের অ্যাক্টিভ রঙ */
        }
        .nav-item i { 
            font-size: 20px; 
            margin-bottom: 4px; 
        }
    </style>
@endonce