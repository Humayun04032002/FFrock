<!DOCTYPE html>
<html>
<head>
    <title>Match Details - {{ $match->Match_Title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <style>
        :root {
            --primary-color: #6a00f4;
            --bg-color: #f0f1f6;
            --card-bg: white;
            --text-color: #333;
            --text-secondary: #555;
        }
        body { font-family: 'Roboto', sans-serif; background-color: var(--bg-color); margin: 0; }
        .banner { width: 100%; height: 200px; object-fit: cover; }
        .details-container { padding: 20px; margin-bottom: 250px; }
        .title-section { text-align: center; margin-top: -50px; position: relative; z-index: 2; }
        .title-section h1 { background-color: var(--card-bg); display: inline-block; padding: 10px 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .timer { font-size: 1.5em; font-weight: bold; color: var(--text-color); text-align: center; margin-top: 20px; background-color: white; padding: 15px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .timer span { color: var(--primary-color); }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 25px; }
        .info-card { background-color: var(--card-bg); padding: 15px; border-radius: 10px; text-align: center; }
        .info-card i { font-size: 24px; color: var(--primary-color); margin-bottom: 8px; }
        .info-card h4 { margin: 0; font-size: 1.1em; }
        .info-card p { margin: 2px 0 0; color: var(--text-secondary); }
        .rules-section { background-color: var(--card-bg); padding: 20px; border-radius: 12px; margin-top: 20px; }
        .rules-section h3 { margin-top: 0; display: flex; align-items: center; gap: 10px; }
        .rules-section pre { white-space: pre-wrap; font-family: inherit; line-height: 1.6; color: var(--text-secondary); }
        .btn-view {
            display: block;
            width: calc(100% - 40px);
            margin: 20px auto 0 auto;
            padding: 12px 0;
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: background-color 0.3s;
        }
        .btn-view:hover { background-color: #5200c4; }
        .join-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: var(--card-bg);
            padding: 15px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            max-height: 70%;
            overflow-y: auto;
            z-index: 10;
        }
        .join-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: var(--primary-color);
            color: white;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
        }
        .join-btn:disabled { background: #9E9E9E; cursor: not-allowed; }
        .join-form-group { margin-bottom: 10px; }
        .join-form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .join-form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; }
        .message { padding: 10px; border-radius: 8px; margin: 0 0 15px 0; text-align: center; font-weight: bold; }
        .error { background-color: #ffebee; color: #c62828; }
        #success-animation-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); z-index: 2000; justify-content: center; align-items: center; flex-direction: column; color: white; text-align: center; opacity: 0; transition: opacity 0.3s; }
        #success-animation-overlay.visible { display: flex; opacity: 1; }
        #success-animation-overlay h2 { font-size: 1.5em; margin-top: 20px; animation: textFadeIn 0.5s 0.5s forwards; opacity: 0; }
        #success-animation-overlay p { font-size: 1em; opacity: 0; animation: textFadeIn 0.5s 0.8s forwards; }
        @keyframes textFadeIn { to { opacity: 1; } }
    </style>
</head>
<body>
    <img src="{{ asset($gameType === 'ludo' ? 'assets/images/04112025105845265_banner.jpeg' : 'assets/images/03112025175115927_banner.jpeg') }}" class="banner">

    <div class="details-container">
        <div class="title-section">
            <h1>{{ $match->Match_Title }}</h1>
        </div>

        <div class="timer" id="match-timer" data-time="{{ $match->Match_Time }}">Loading Timer...</div>

        @if (session('error'))
            <p class="message error">{{ session('error') }}</p>
        @endif
        @if ($errors->any())
            <p class="message error">{{ $errors->first() }}</p>
        @endif

        <div class="info-grid">
            <div class="info-card"><i class="fas fa-trophy"></i><h4>Total Prize</h4><p>৳{{ $match->Total_Prize }}</p></div>
            <div class="info-card"><i class="fas fa-coins"></i><h4>Per Kill</h4><p>৳{{ $match->Per_Kill }}</p></div>
            <div class="info-card"><i class="fas fa-users"></i><h4>Type</h4><p>{{ $match->Entry_Type }}</p></div>
            <div class="info-card"><i class="fas fa-map-marked-alt"></i><h4>Map</h4><p>{{ $match->Play_Map }}</p></div>
        </div>

 @php
            $joinersCount = \App\Models\Joining::where('Match_Key', $match->Match_Key)->count();
        @endphp
        @if($joinersCount > 0)
            <a href="{{ route('user.matches.joiners', $match->id) }}" class="btn-view">
                View Joined Players ({{ $joinersCount }})
            </a>
        @endif   
        <div class="rules-section">
            <h3><i class="fas fa-scroll"></i> Rules & Regulations</h3>
            <pre>{{ $rulesText }}</pre>
        </div>

        

    <div class="join-footer">
        @php
            $isFull = $match->Player_Join >= $match->Player_Need;
            $hasLowBalance = (float)$user->Balance < (float)$match->Entry_Fee;
            $buttonDisabled = $isJoined || $isFull || $hasLowBalance;
        @endphp

        @if (!$isJoined && $hasLowBalance)
            <p class="message error">Insufficient Balance!</p>
        @endif

        <form id="joinForm" action="{{ route('match.join', ['id' => $match->id]) }}" method="post">
            @csrf
            <input type="hidden" name="game_type" value="{{ $gameType }}">
            <div id="player-inputs"></div>

            <button type="submit" class="join-btn" @if($buttonDisabled) disabled @endif>
                @if ($isJoined)
                    ✓ Already Joined
                @elseif ($isFull)
                    Match Full
                @else
                    Join Now (৳{{ $match->Entry_Fee }})
                @endif
            </button>
        </form>
    </div>

    @if (session('success'))
        <div id="success-animation-overlay" class="visible">
            <lottie-player 
                src="https://lottie.host/e2898144-6725-4070-a353-8395171732ad/qX2xms2T7w.json"  
                background="transparent" speed="1" style="width: 250px; height: 250px;" autoplay>
            </lottie-player>
            <h2>{{ session('success') }}</h2>
            <p>Redirecting to My Matches...</p>
        </div>
    @endif

    <script>
        // Match Timer
        const timerElement = document.getElementById('match-timer');
        if(timerElement) {
            const matchTime = new Date(timerElement.getAttribute('data-time').replace(' ', 'T')).getTime();
            const countdown = setInterval(function() {
                const now = new Date().getTime();
                const distance = matchTime - now;
                if (distance < 0) {
                    clearInterval(countdown);
                    timerElement.innerHTML = "Match Started!";
                    return;
                }
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                timerElement.innerHTML = `Starts In: ${days > 0 ? '<span>' + days + '</span>d ' : ''}<span>${hours}</span>h <span>${minutes}</span>m <span>${seconds}</span>s`;
            }, 1000);
        }

        // Player Input Fields
        const oldInputs = @json(session()->getOldInput());
        const matchType = "{{ $match->Entry_Type }}";
        const playerInputsContainer = document.getElementById('player-inputs');
        let playersNeeded = 1;
        if (matchType === 'Duo') playersNeeded = 2;
        if (matchType === 'Squad') playersNeeded = 4;

        for (let i = 1; i <= playersNeeded; i++) {
            let labelText = (i === 1) ? "Your In-Game Name (Required)" : `Teammate ${i-1} Name (Optional)`;
            let isRequired = (i === 1) ? 'required' : '';
            let fieldName = `ingame_name_${i}`;
            let oldValue = oldInputs[fieldName] ? oldInputs[fieldName] : '';
            playerInputsContainer.innerHTML += `
                <div class="join-form-group">
                    <label>${labelText}:</label>
                    <input type="text" name="${fieldName}" ${isRequired} value="${oldValue}">
                </div>
            `;
        }

        @if (session('success'))
            document.querySelectorAll('button').forEach(button => button.disabled = true);
            const overlay = document.getElementById('success-animation-overlay');
            if(overlay) { overlay.classList.add('visible'); }
            setTimeout(() => { 
                window.location.href = '{{ route("my.matches") }}';
            }, 2500);
        @endif
    </script>
</body>
</html>
