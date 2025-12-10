<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0">
    <title>Dashboard - {{ $site_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #8A2BE2; --secondary-color: #4A90E2; --bg-dark: #0a0a14;
            --bg-light-dark: #141421; --text-light: #f0f0f0; --text-secondary: #a0a0b0;
            --border-color: rgba(255, 255, 255, 0.1); --success: #28a745; --danger: #dc3545;
        }
        @property --gradient-angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-light); background-image: radial-gradient(circle at 1% 1%, rgba(138, 43, 226, 0.15), transparent 30%), radial-gradient(circle at 99% 99%, rgba(74, 144, 226, 0.15), transparent 30%); }
        .navbar { background: rgba(10, 10, 20, 0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); padding: 0 40px; display: flex; justify-content: space-between; align-items: center; height: 75px; border-bottom: 1px solid var(--border-color); position: sticky; top: 0; z-index: 100; }
        .navbar-brand { font-size: 24px; font-weight: 700; color: var(--text-light); text-decoration: none; display: flex; align-items: center; gap: 12px; }
        .navbar-brand i { color: var(--primary-color); }
        .navbar-actions { display: flex; align-items: center; gap: 15px; }
        .btn-action { background-color: transparent; border: 1px solid var(--border-color); color: var(--text-secondary); padding: 10px 18px; border-radius: 8px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; text-decoration: none; font-weight: 500; }
        .btn-action:hover { background-color: var(--primary-color); color: white; border-color: var(--primary-color); transform: translateY(-2px); }
        .btn-action.logout-btn { border-color: var(--danger); color: var(--danger); }
        .btn-action.logout-btn:hover { background-color: var(--danger); color: white; }
        .main-container { padding: 50px 40px; }
        .page-header { text-align: center; margin-bottom: 50px; animation: fadeInDown 0.8s ease-out; }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .page-header h1 { font-size: 40px; font-weight: 700; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .page-header p { font-size: 18px; color: var(--text-secondary); margin-top: 5px; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 25px; justify-content: center; }
        .d-card { aspect-ratio: 1 / 1; text-decoration: none; color: var(--text-light); background-color: var(--bg-light-dark); border-radius: 20px; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px; cursor: pointer; transition: transform 0.3s ease, box-shadow 0.3s ease; animation: popIn 0.6s ease-out forwards; opacity: 0; transform: scale(0.9); animation-delay: calc(var(--delay) * 70ms); overflow: hidden; }
        @keyframes popIn { to { opacity: 1; transform: scale(1); } }
        .d-card::before, .d-card::after { content: ''; position: absolute; top: 50%; left: 50%; width: 200%; height: 200%; border-radius: inherit; z-index: 0; transform: translate(-50%, -50%); }
        .d-card::before { background: conic-gradient(from var(--gradient-angle), var(--primary-color), var(--secondary-color) 25%, var(--primary-color) 50%); animation: spin 4s linear infinite paused; }
        .d-card::after { background: var(--bg-light-dark); inset: 2px; }
        .d-card:hover { transform: translateY(-10px) scale(1.05); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5); }
        .d-card:hover::before { animation-play-state: running; }
        @keyframes spin { to { --gradient-angle: 360deg; } }
        .card-content { z-index: 1; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .icon-wrapper { width: 50px; height: 50px; border-radius: 50%; display: grid; place-items: center; margin-bottom: 12px; font-size: 24px; transition: all 0.3s ease; }
        .d-card:hover .icon-wrapper { transform: scale(1.1); }
        .card-title { font-size: 15px; font-weight: 500; text-align: center; }
        .card-count { font-size: 28px; font-weight: 700; margin-top: 4px; }
        .card-subtext { font-size: 11px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; }
        .modal { visibility: hidden; opacity: 0; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; transition: visibility 0.3s, opacity 0.3s; }
        .modal.is-visible { visibility: visible; opacity: 1; }
        .modal-content { background-color: var(--bg-light-dark); padding: 40px; border: 1px solid var(--border-color); width: 90%; max-width: 500px; border-radius: 16px; position: relative; transform: translateY(20px); transition: transform 0.3s, opacity 0.3s; }
        .modal.is-visible .modal-content { transform: translateY(0); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; margin-bottom: 30px; border-bottom: 1px solid var(--border-color); }
        .modal-header h2 { margin: 0; font-size: 24px; }
        .close-btn { color: #aaa; font-size: 32px; font-weight: bold; cursor: pointer; transition: color 0.3s; }
        .close-btn:hover { color: var(--text-light); }
        .modal-body .form-group { margin-bottom: 20px; }
        .modal-body label { display: block; margin-bottom: 8px; font-weight: 500; }
        .modal-body input[type="password"] { width: 100%; padding: 14px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-light); }
        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border-left-width: 3px; border-left-style: solid; }
        .alert-success { background: rgba(40, 167, 69, 0.2); color: #28a745; border-left-color: #28a745; }
        .alert-danger { background: rgba(220, 53, 69, 0.2); color: #dc3545; border-left-color: #dc3545; }
        @media (max-width: 768px) { .navbar { padding: 0 20px; } .btn-action span { display: none; } .main-container { padding: 30px 20px; } .dashboard-grid { grid-template-columns: 1fr 1fr; gap: 20px; } }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand"><i class="fas fa-shield-virus"></i><span>{{ $site_name }}</span></a>
        <div class="navbar-actions">
            <button id="changePasswordBtn" class="btn-action"><i class="fas fa-key"></i> <span>Password</span></button>
            <form action="{{ route('admin.logout') }}" method="post" style="display: inline;">
                @csrf
                <button type="submit" class="btn-action logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></button>
            </form>
        </div>
    </nav>

    <div class="main-container">
        <header class="page-header">
            <h1>Admin Control Hub</h1>
            <p>Welcome, {{ Auth::guard('admin')->user()->username }}. All systems are operational.</p>
        </header>
        <main class="dashboard-grid">
            @php
            $cards = [
                ['route' => route('admin.users.index'), 'icon' => 'fa-users', 'color' => '#3498db', 'title' => 'Users', 'count' => $total_users, 'sub' => 'Total Players'],
                ['route' => route('admin.transactions.index', ['type' => 'addmoney']), 'icon' => 'fa-wallet', 'color' => '#2ecc71', 'title' => 'Add Money', 'count' => $pending_addmoney, 'sub' => 'Pending'],
                ['route' => route('admin.transactions.index', ['type' => 'withdraw']), 'icon' => 'fa-hand-holding-dollar', 'color' => '#e74c3c', 'title' => 'Withdraw', 'count' => $pending_withdraw, 'sub' => 'Pending'],
                ['route' => route('admin.sliders.index'), 'icon' => 'fa-images', 'color' => '#9b59b6', 'title' => 'Sliders', 'sub' => 'Manage'],
                ['route' => route('admin.matches.index', ['game_type' => 'freefire']), 'icon' => 'fa-crosshairs', 'color' => '#f39c12', 'title' => 'FF Matches', 'sub' => 'Manage'],
                ['route' => route('admin.matches.index', ['game_type' => 'ludo']), 'icon' => 'fa-dice', 'color' => '#1abc9c', 'title' => 'Ludo', 'sub' => 'Manage'],
                ['route' => route('admin.rules.index'), 'icon' => 'fa-scroll', 'color' => '#34495e', 'title' => 'Rules', 'sub' => 'Manage'],
                ['route' => route('admin.settings.index'), 'icon' => 'fa-cog', 'color' => '#7f8c8d', 'title' => 'Settings', 'sub' => 'Configure'],
                
                // *** পরিবর্তিত অংশ: নোটিফিকেশন বাটনের পাথ আপডেট করা হয়েছে ***
                // এখানে route() helper ব্যবহার করা হয়েছে যা আপনার web.php ফাইল থেকে সঠিক URL তৈরি করবে।
                // এটি 'http://haglu.battlecore.top/admin/send-notification' URL-টিই তৈরি করবে।
                ['route' => route('admin.notifications.form'), 'icon' => 'fa-bell', 'color' => '#E91E63', 'title' => 'Notification', 'sub' => 'Send Push']
            ];
            @endphp
            @foreach ($cards as $i => $card)
            <a href="{{ $card['route'] }}" class="d-card" style="--delay: {{ $i }}">
                <div class="card-content">
                    <div class="icon-wrapper" style="color: {{ $card['color'] }}; filter: drop-shadow(0 0 10px {{ $card['color'] }}B3);">
                        <i class="fas {{ $card['icon'] }}"></i>
                    </div>
                    <h3 class="card-title">{{ $card['title'] }}</h3>
                    @if (isset($card['count']))
                        <p class="card-count">{{ $card['count'] }}</p>
                        <p class="card-subtext">{{ $card['sub'] }}</p>
                    @else
                        <p class="card-subtext" style="margin-top: 4px;">{{ $card['sub'] }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </main>
    </div>

    <!-- The Modal HTML -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header"><h2>Change Password</h2><span class="close-btn">×</span></div>
            <div class="modal-body">
                @if (session('status_success'))
                    <div class="alert alert-success">{{ session('status_success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin.password.change') }}" method="post">
                    @csrf
                    <div class="form-group"><label>Current Password</label><input type="password" name="current_password" required></div>
                    <div class="form-group"><label>New Password</label><input type="password" name="new_password" required></div>
                    <div class="form-group"><label>Confirm New Password</label><input type="password" name="new_password_confirmation" required></div>
                    <button type="submit" class="btn-action" style="width: 100%; background-color: var(--primary-color); color: white; justify-content: center; font-weight: 600;">Update Password</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById("passwordModal");
            const openBtn = document.getElementById("changePasswordBtn");
            const closeBtn = document.querySelector("#passwordModal .close-btn");
            const openModal = () => modal.classList.add('is-visible');
            const closeModal = () => modal.classList.remove('is-visible');
            if (openBtn) { openBtn.addEventListener('click', openModal); }
            if (closeBtn) { closeBtn.addEventListener('click', closeModal); }
            window.addEventListener('click', (event) => { if (event.target === modal) closeModal(); });
            @if ($errors->any() || session('status_success'))
                openModal();
            @endif
        });
    </script>
</body>
</html>