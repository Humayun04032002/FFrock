<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Leaderboard - Top Winners</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --primary-color: #ff9800; --bg-color: #f0f2f5; --card-bg: white; --text-color: #333; }
        body { font-family: 'Roboto', sans-serif; background-color: var(--bg-color); margin: 0; padding: 15px; padding-bottom: 85px; }
        .header { display: flex; align-items: center; justify-content: center; margin-bottom: 20px; position: relative; }
        .back-btn { position: absolute; left: 0; background: none; border: none; font-size: 24px; color: var(--text-color); cursor: pointer; }
        .header h1 { font-size: 22px; margin: 0; }
        .leaderboard-section { background-color: var(--card-bg); border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .leaderboard-title { margin-top: 0; font-size: 1.2em; text-align: center; color: var(--primary-color); border-bottom: 2px solid #eee; padding-bottom: 10px; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .leaderboard-list { list-style: none; padding: 0; margin-top: 20px; }
        .player-row { display: flex; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
        .player-row:last-child { border-bottom: none; }
        .rank { font-size: 1.2em; font-weight: bold; width: 40px; color: #888; text-align: center; }
        .rank-1 { color: #FFD700; } .rank-2 { color: #C0C0C0; } .rank-3 { color: #CD7F32; }
        .avatar { width: 45px; height: 45px; border-radius: 50%; margin-right: 15px; }
        .player-info { flex-grow: 1; }
        .player-info .name { font-weight: 500; font-size: 1.1em; }
        .player-score { font-size: 1.1em; font-weight: bold; color: var(--text-color); }
        .no-data { text-align: center; color: #888; padding: 40px; }
    </style>
</head>
<body>
    <div class="header">
        <button onclick="window.history.back()" class="back-btn"><i class="fas fa-arrow-left"></i></button>
        <h1>Leaderboard</h1>
    </div>

    <div class="leaderboard-section">
        <h2 class="leaderboard-title"><i class="fas fa-crown"></i> Top Winners by Winning Balance</h2>
        <ul class="leaderboard-list">
            @forelse ($topWinners as $index => $player)
                <li class="player-row">
                    <div class="rank rank-{{ $index + 1 }}">#{{ $index + 1 }}</div>
                    {{-- অ্যাসেট ফাইল public ফোল্ডারে রাখতে হবে --}}
                    <img src="{{ asset('assets/images/wired-flat-44-avatar-user-in-circle.gif') }}" alt="Avatar" class="avatar">
                    <div class="player-info"><span class="name">{{ $player->Name }}</span></div>
                    <div class="player-score">৳{{ number_format($player->Winning, 2) }}</div>
                </li>
            @empty
                <p class="no-data">No winners on the leaderboard yet.</p>
            @endforelse
        </ul>
    </div>
    
    @include('partials.bottom-nav')
</body>
</html>