<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>{{ $pageTitle }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    .match-card {
    background-color: var(--card-bg);
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    border: 3px solid red !important;
}

        :root { --primary-color: #FF0000; --secondary-color: #2E7D32; --bg-color: #f0f2f5; --card-bg: white; --text-color: #333; --light-text-color: #666; }
        body { font-family: 'Roboto', sans-serif; background-color: var(--bg-color); margin: 0; padding: 15px; }
        .header { display: flex; align-items: center; margin-bottom: 20px; }
        .back-btn { background: none; border: none; font-size: 24px; color: var(--text-color); cursor: pointer; margin-right: 15px; }
        .header h1 { font-size: 22px; margin: 0; color: var(--text-color); }
        .match-card { background-color: var(--card-bg); border-radius: 12px; margin-bottom: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e0e0e0; }
        .match-card-header { display: flex; align-items: center; padding: 15px; border-bottom: 1px solid #eee; position: relative; }
        .match-card-header img { width: 50px; height: 50px; border-radius: 8px; margin-right: 15px; }
        .header-info h3 { margin: 0; font-size: 1.1em; color: var(--text-color); }
        .header-info p { margin: 2px 0 0; font-size: 0.8em; color: var(--light-text-color); }
        .match-id { position: absolute; top: 0; right: 0; background-color: #ff0000; color: white; padding: 3px 10px; font-size: 0.8em; font-weight: bold; border-bottom-left-radius: 8px; }
        .match-card-body { padding: 15px; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; text-align: center; }
        .stat-block p { font-size: 0.7em; color: var(--light-text-color); margin: 0 0 4px 0; text-transform: uppercase; }
        .stat-block h4 { margin: 0; font-size: 1em; color: var(--text-color); }
        .match-card-progress { padding: 0 15px 15px; }
        .progress-bar { width: 100%; background-color: #e0e0e0; border-radius: 50px; height: 10px; overflow: hidden; }
        .progress-bar-fill { background: linear-gradient(45deg, #FFC107, #FF9800); height: 100%; border-radius: 50px; transition: width 0.5s ease; }
        .progress-info { display: flex; justify-content: space-between; font-size: 0.8em; color: var(--light-text-color); margin-top: 5px; }
        .joined-status { padding: 5px 12px; font-weight: bold; border: 1px solid var(--primary-color); color: var(--primary-color); border-radius: 8px; }
        .match-card-actions { padding: 15px; border-top: 1px solid #eee; display: flex; gap: 10px; }
        .action-btn { flex: 1; padding: 10px; background: #f0f2f5; border: 1px solid #ddd; border-radius: 8px; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background-color 0.2s; text-decoration: none; color: inherit; }
        .action-btn:hover { background-color: #e0e0e0; }
        .match-card-footer {
    background: none !important;   /* remove gradient */
    color: #000 !important;        /* make text black */
    text-align: center;
    padding: 12px;
    font-size: 1.1em;
    font-weight: bold;
}

        .no-match { text-align: center; color: #888; padding: 40px; background: white; border-radius: 12px; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
        .modal-content { background-color: white; margin: 30% auto; padding: 20px; border-radius: 12px; width: 90%; max-width: 400px; animation: zoomIn 0.3s; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .modal-header h2 { margin: 0; font-size: 1.2em; }
        .close-btn { font-size: 24px; color: #888; cursor: pointer; font-weight: bold; }
        .prize-list, .password-display { list-style: none; padding: 0; }
        .prize-list li, .password-display p { margin: 10px 0; font-size: 1em; }
        .prize-list li strong { color: var(--primary-color); }
        @keyframes zoomIn { from { transform: scale(0.7); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
</head>
<body>
    <div class="header">
        <button onclick="window.location.href='{{ route('home') }}'" class="back-btn"><i class="fas fa-arrow-left"></i></button>
        <h1>{{ $pageTitle }}</h1>
    </div>

    @forelse ($matches as $match)
        @php
            $progress = ($match->Player_Need > 0) ? ($match->Player_Join / $match->Player_Need) * 100 : 0;
            $isUserJoined = in_array($match->Match_Key, $joinedMatchKeys);
        @endphp
        <div class="match-card">
            <div class="match-card-header">
                <img src="{{ asset($type === 'ludo' ? 'assets/images/ludo.jpeg' : 'assets/images/IMG_20251113_175156_516.jpeg') }}" alt="Game Icon">
                <div class="header-info">
                    <h3>{{ $match->Match_Title }}</h3>
                    <p>{{ \Carbon\Carbon::parse($match->Match_Time)->format('d-m-Y \a\t h:i A') }}</p>
                </div>
                <div class="match-id">#{{ $match->id }}</div>
            </div>
            <div class="match-card-body">
                <div class="stat-block"><p>WIN PRIZE</p><h4>৳{{ $match->Total_Prize }}</h4></div>
                <div class="stat-block"><p>TYPE</p><h4>{{ $match->Entry_Type }}</h4></div>
                <div class="stat-block"><p>ENTRY FEE</p><h4>৳{{ $match->Entry_Fee }}</h4></div>
                <div class="stat-block"><p>PER KILL</p><h4>৳{{ $match->Per_Kill }}</h4></div>
                <div class="stat-block"><p>MAP</p><h4>{{ $match->Play_Map }}</h4></div>
                <div class="stat-block"><p>VERSION</p><h4>{{ $match->Version }}</h4></div>
            </div>
            <div class="match-card-progress">
                <div class="progress-bar"><div class="progress-bar-fill" style="width: {{ $progress }}%;"></div></div>
                <div class="progress-info">
                    <span>Only {{ max(0, $match->Player_Need - $match->Player_Join) }} Spots Left</span>
                    @if($isUserJoined)
                        <span class="joined-status">✓ Joined</span>
                    @else
                        <span>{{ $match->Player_Join }}/{{ $match->Player_Need }}</span>
                    @endif
                </div>
            </div>

            <!-- ✅ Added Join Button (safe, no route changed) -->
            <div class="match-card-actions">
                @if(!$isUserJoined)
                    <a href="{{ route('match.details', ['game' => $type, 'id' => $match->id]) }}" class="action-btn" style="background: var(--primary-color); color: white;">
                        <i class="fas fa-user-plus"></i> Join
                    </a>
                @else
                    <button class="action-btn" disabled style="background: #c8e6c9; color: var(--secondary-color); cursor: not-allowed;">
                        <i class="fas fa-check-circle"></i> Joined
                    </button>
                @endif

                <button class="action-btn" onclick="showRoomDetails({{ $isUserJoined ? 'true' : 'false' }}, '{{ $match->Position }}', '{{ $match->Room_ID ?? 'TBA' }}', '{{ $match->Room_Pass ?? 'TBA' }}')">
                    <i class="fas fa-key"></i> Room Password
                </button>
                <button class="action-btn" onclick="showPrizeDetails('{{ $match->Total_Prize }}', '{{ $match->prize_1st ?? '0' }}', '{{ $match->prize_2nd ?? '0' }}', '{{ $match->prize_3rd ?? '0' }}')">
                    <i class="fas fa-trophy"></i> Prize Details
                </button>
            </div>

            <a href="{{ route('user.matches.joiners', $match->id) }}" style="text-decoration: none;">
                <div class="match-card-footer" data-time="{{ $match->Match_Time }}">
                    <i class="fas fa-clock"></i> STARTS IN: <span class="countdown">Loading...</span>
                </div>
            </a>
        </div>
    @empty
        <p class='no-match'>No upcoming matches available in this category.</p>
    @endforelse

    <div id="passwordModal" class="modal"><div class="modal-content"><div class="modal-header"><h2>Room Details</h2><span class="close-btn" onclick="closeModal('passwordModal')">×</span></div><div id="passwordDisplay" class="password-display"></div></div></div>
    <div id="prizeModal" class="modal"><div class="modal-content"><div class="modal-header"><h2>Prize Pool Details</h2><span class="close-btn" onclick="closeModal('prizeModal')">×</span></div><ul id="prizeList" class="prize-list"></ul></div></div>

    <script>
        document.querySelectorAll('.match-card-footer').forEach(footer => {
            const countdownElement = footer.querySelector('.countdown');
            if (!countdownElement) return;
            const matchTime = new Date(footer.getAttribute('data-time').replace(' ', 'T')).getTime();
            const updateCountdown = () => {
                const now = new Date().getTime();
                const distance = matchTime - now;
                if (distance < 0) {
                    countdownElement.innerHTML = "Match Started";
                    if (footer.intervalId) clearInterval(footer.intervalId);
                    return;
                }
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                countdownElement.innerHTML = `${String(days).padStart(2, '0')}d ${String(hours).padStart(2, '0')}h ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}s`;
            };
            updateCountdown();
            footer.intervalId = setInterval(updateCountdown, 1000);
        });

        function openModal(modalId) { document.getElementById(modalId).style.display = "block"; }
        function closeModal(modalId) { document.getElementById(modalId).style.display = "none"; }

        function showRoomDetails(isJoined, position, roomId, roomPass) {
            const passwordDisplay = document.getElementById('passwordDisplay');
            if (isJoined) {
                if (position === 'OnGoing') {
                    passwordDisplay.innerHTML = `<p><strong>Room ID:</strong> ${roomId}</p><p><strong>Password:</strong> ${roomPass}</p>`;
                } else {
                    passwordDisplay.innerHTML = `<p>Room ID & Password will be available here shortly before the match starts.</p>`;
                }
            } else {
                passwordDisplay.innerHTML = `<p>You must join the match to see the Room ID and Password.</p>`;
            }
            openModal('passwordModal');
        }

        function showPrizeDetails(totalPrize, prize1, prize2, prize3) {
            const prizeList = document.getElementById('prizeList');
            let prizeHTML = `<li><strong>Total Prize Pool:</strong> ৳${totalPrize}</li><hr>`;
            if (prize1 && parseFloat(prize1) > 0) { prizeHTML += `<li><strong>Winner #1:</strong> ৳${prize1}</li>`; }
            if (prize2 && parseFloat(prize2) > 0) { prizeHTML += `<li><strong>Runner-up #2:</strong> ৳${prize2}</li>`; }
            if (prize3 && parseFloat(prize3) > 0) { prizeHTML += `<li><strong>Placement #3:</strong> ৳${prize3}</li>`; }
            prizeList.innerHTML = prizeHTML;
            openModal('prizeModal');
        }

        window.onclick = function(event) { if (event.target.classList.contains('modal')) { event.target.style.display = "none"; } }
    </script>
</body>
</html>
