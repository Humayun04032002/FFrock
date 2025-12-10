<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="refresh" content="3;url={{ route('login') }}">
<title>{{ $settings->{'Splash Title'} }} - Loading</title>

<style>
    * { margin:0; padding:0; box-sizing:border-box; outline:none; user-select:none; }
    html, body { width:100%; height:100%; overflow:hidden; font-family:Arial,sans-serif; }
    body {
        display:flex; flex-direction:column; justify-content:center; align-items:center;
        color:#fff;
        background: linear-gradient(-45deg,#ff0057,#ff00d4,#00ffd4,#0057ff,#ff0057);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
    @keyframes gradientBG {0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}

    /* Particle streaks (neon bullets) */
    .streaks { position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; overflow:hidden; z-index:0;}
    .streak {
        position:absolute;
        width:2px; height:60px;
        background: linear-gradient(180deg, rgba(255,255,255,0.8), rgba(255,255,255,0));
        border-radius:50%;
        filter: blur(2px);
        opacity:0.8;
        transform: translateY(100vh);
        animation: streakMove linear infinite;
    }
    @keyframes streakMove {
        0% { transform: translateY(100vh) rotate(0deg); opacity:0; }
        10% { opacity:1; }
        100% { transform: translateY(-20vh) rotate(10deg); opacity:0; }
    }

    /* Particle dots for depth */
    .particles { position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:0;}
    .particle { position:absolute; width:3px; height:3px; background:rgba(255,255,255,0.5); border-radius:50%; animation: particleMove linear infinite; }
    @keyframes particleMove {0%{transform:translateY(100vh);}100%{transform:translateY(-10vh);}}

    /* Main loading content */
    .loading-container {
        position:relative; z-index:1; text-align:center; flex-grow:1;
        display:flex; flex-direction:column; justify-content:center; align-items:center; padding:20px; width:100%; max-width:400px;
    }
    .loading-container img { width:120px; height:120px; object-fit:contain; margin-bottom:20px; filter: drop-shadow(0 0 20px #ff00d4); animation: pulse 2s infinite alternate; }
    @keyframes pulse {0%{transform:scale(1);}100%{transform:scale(1.05);}}
    .loading-container h1 { font-size:2rem; margin-bottom:20px; text-shadow:0 0 10px #fff,0 0 20px #ff00d4,0 0 30px #00ffd4; animation:textGlow 2s infinite alternate;}
    @keyframes textGlow {0%{text-shadow:0 0 10px #fff,0 0 20px #ff00d4,0 0 30px #00ffd4;}100%{text-shadow:0 0 20px #fff,0 0 40px #ff00d4,0 0 60px #00ffd4;}}

    /* Loading bar */
    .loading-bar { width:100%; max-width:150px; height:5px; background: rgba(255,255,255,0.2); border-radius:2px; overflow:hidden; margin:20px auto; }
    .loading-bar-inner { width:0; height:100%; background: linear-gradient(90deg,#ff00d4,#00ffd4,#ff0057); animation: loading 3s linear forwards;}
    @keyframes loading {0%{width:0;}100%{width:100%;}}

    .footer-text { font-size:1rem; margin-bottom:20px; color:#a0a0a0; text-shadow:0 0 5px #fff; }

    @media(max-width:500px){.loading-container h1{font-size:1.5rem;}.loading-container img{width:90px;height:90px;}.loading-bar{max-width:120px;}}
</style>
</head>
<body>
<div class="streaks"></div>
<div class="particles"></div>

<div class="loading-container">
    <img src="{{ $settings->{'Splash Logo URL'} }}" alt="{{ $settings->{'Splash Title'} }}">
    <h1>{{ $settings->{'Splash Title'} }}</h1>
    <div class="loading-bar"><div class="loading-bar-inner"></div></div>
</div>
<p class="footer-text">Number 1 Bangladeshi FF Tournament App</p>

<script>
    const streakContainer = document.querySelector('.streaks');
    const particleContainer = document.querySelector('.particles');

    // Neon streaks
    for(let i=0;i<30;i++){
        const streak = document.createElement('div');
        streak.classList.add('streak');
        streak.style.left = Math.random()*100+'vw';
        streak.style.height = 20 + Math.random()*80+'px';
        streak.style.animationDuration = (2 + Math.random()*3)+'s';
        streak.style.animationDelay = Math.random()*5+'s';
        streakContainer.appendChild(streak);
    }

    // Floating particles
    for(let i=0;i<60;i++){
        const particle = document.createElement('div');
        particle.classList.add('particle');
        particle.style.left = Math.random()*100+'vw';
        particle.style.width = particle.style.height = (2+Math.random()*4)+'px';
        particle.style.animationDuration = (4+Math.random()*6)+'s';
        particle.style.animationDelay = Math.random()*5+'s';
        particleContainer.appendChild(particle);
    }
</script>
</body>
</html>
