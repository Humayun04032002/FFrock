<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #030303;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            font-family: "Orbitron", sans-serif;
        }

        /* 🔥 Animated Red Grid Background */
        .grid {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(#ff000010 1px, transparent 1px),
                        linear-gradient(90deg, #ff000010 1px, transparent 1px);
            background-size: 40px 40px;
            animation: gridMove 8s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translateY(0px); }
            100% { transform: translateY(40px); }
        }

        /* 🔥 Main Container */
        .alert-box {
            width: 420px;
            padding: 40px;
            text-align: center;
            color: #ff3b3b;
            border: 2px solid #ff0000;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(5px);
            box-shadow: 0 0 40px #ff000055, inset 0 0 40px #ff000033;
            animation: fadeIn 1s ease-out;
            position: relative;
        }

        /* 🔥 Futuristic scanning borders */
        .alert-box::before {
            content: '';
            position: absolute;
            top: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: red;
            animation: scan 2s linear infinite;
        }

        @keyframes scan {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* 🔥 Security Icon */
        .icon {
            font-size: 80px;
            color: red;
            text-shadow: 0 0 20px red;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 1.8rem;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 3px;
        }

        p {
            font-size: 0.95rem;
            margin-bottom: 25px;
            opacity: 0.8;
            line-height: 1.5;
        }

        /* Countdown */
        .timer {
            font-size: 1rem;
            margin-top: 10px;
        }

        .timer span {
            font-size: 1.2rem;
            color: #fff;
        }

        /* Fade Animation */
        @keyframes fadeIn {
            from {
                transform: scale(0.85);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    <!-- 🔥 Animated Grid -->
    <div class="grid"></div>

    <div class="alert-box">

        <div class="icon">⚠️</div>

        <h1>ACCESS DENIED</h1>
        <p>Unauthorized activity detected.  
        Your access attempt has been logged in the system.</p>

        <div class="timer">
            Redirecting in <span id="countdown">10</span> seconds...
        </div>
    </div>

    <!-- 🔊 Alarm Sound -->
<audio id="alarmSound">
    <source src="https://files.catbox.moe/3k4t3o.mp3" type="audio/mp3">
</audio>

<script>
    const alarm = document.getElementById('alarmSound');
    alarm.volume = 1.0;

    // TRY AUTOPLAY
    const tryPlay = () => {
        alarm.play().catch(() => {});
    };

    // TRY ON LOAD
    window.addEventListener("load", tryPlay);

    // IF AUTOPLAY BLOCKED, PLAY ON FIRST TOUCH
    document.body.addEventListener("touchstart", () => {
        alarm.play();
    }, { once: true });

    // ALSO PLAY ON CLICK FOR DESKTOP
    document.body.addEventListener("click", () => {
        alarm.play();
    }, { once: true });

    // Countdown Redirect
    let seconds = 10;
    const countdownElement = document.getElementById('countdown');

    const interval = setInterval(() => {
        seconds--;
        countdownElement.textContent = seconds;

        if (seconds <= 0) {
            clearInterval(interval);
            window.location.href = "{{ url('/') }}";
        }
    }, 1000);
</script>


</body>
</html>
