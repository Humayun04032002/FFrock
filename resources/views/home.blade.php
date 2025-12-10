<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home - OX FF TOUR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* আপনার দেওয়া মূল CSS কোড - কোনো পরিবর্তন করা হয়নি */
        :root { --primary-color: #6a00f4; --bg-color: #f7f8fc; --card-bg: #ffffff; --card-border-color: #e3e8f0; --section-title-color: #3f4a59; --card-title-color: #2c3e50; --card-subtitle-color: #7f8c8d; --fab-bg: linear-gradient(45deg, #FDC830, #F37335); }
        body { font-family: 'Roboto', sans-serif; background-color: var(--bg-color); margin: 0; padding-bottom: 85px; }
        .main-container { padding: 15px; }
        .slider-container { position: relative; width: 100%; aspect-ratio: 16 / 8; margin-bottom: 20px; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.1); background-color: #ddd; }
        .view-pager { display: flex; height: 100%; transition: transform 0.5s ease-in-out; }
        .slide { min-width: 100%; height: 100%; }
        .slide a, .slide img { display: block; width: 100%; height: 100%; object-fit: cover; }
        .dots-container { position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); display: flex; z-index: 10; }
        .dot { height: 8px; width: 8px; margin: 0 5px; background-color: rgba(255,255,255,0.7); border-radius: 50%; cursor: pointer; }
        .dot.active { background-color: white; }
        .no-slider-message { display: flex; justify-content: center; align-items: center; height: 100%; color: #888; }
        .notice-bar { background-color: white; padding: 10px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .notice-icon { width: 20px; height: 20px; margin-right: 10px; }
        .marquee { white-space: nowrap; overflow: hidden; width: 100%; }
        .marquee span { display: inline-block; padding-left: 100%; animation: marquee 15s linear infinite; font-weight: 500; }
        @keyframes marquee { 0% { transform: translate(0, 0); } 100% { transform: translate(-100%, 0); } }
        .section-header { font-size: 1.2em; font-weight: 700; color: #3f4a59; margin: 15px 0; text-align: center; }
        .match-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .game-card { background-color: #ffffff; border-radius: 16px; border: 2px solid #e3e8f0; overflow: hidden; text-decoration: none; color: #2c3e50; }
        .game-card .banner { width: 100%; aspect-ratio: 4 / 3; background-size: cover; background-position: center; }
        .game-card .info { padding: 12px; text-align: center; background-color: white; box-shadow: 0px -5px 15px -5px rgba(0,0,0,0.1); }
        .game-card .title { font-weight: 700; font-size: 1.1em; margin: 0; }
        .game-card .count { font-size: 0.85em; color: #7f8c8d; margin-top: 4px; }
        .fab { position: fixed; width: 56px; height: 56px; background: linear-gradient(45deg, #FDC830, #F37335); border-radius: 50%; bottom: 90px; right: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 999; text-decoration: none; }
        .fab i { font-size: 24px; color: white; }
        
        /* ========= স্মার্ট নোটিফিকেশন বাটনের জন্য CSS ========= */
        #smart-bell-button {
            position: fixed; bottom: 160px; right: 20px;
            width: 50px; height: 50px; background: white;
            border-radius: 50%; display: none; /* ডিফল্টভাবে লুকানো */
            justify-content: center; align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 1001; cursor: pointer; transition: all 0.3s ease;
        }
        #smart-bell-button.visible { display: flex; }
        #smart-bell-button i { font-size: 22px; color: var(--primary-color); }
        #smart-bell-button span {
            position: absolute; right: 45px; background: var(--primary-color);
            color: white; padding: 5px 12px; border-radius: 15px;
            font-size: 14px; white-space: nowrap; transform: scaleX(0);
            transform-origin: right; transition: transform 0.3s ease;
        }
        #smart-bell-button.show-text span { transform: scaleX(1); }
    </style>
</head>
<body>
    <!-- আপনার মূল HTML কোড - কোনো পরিবর্তন করা হয়নি -->
    <div class="main-container">
        <div class="slider-container">
            @if($sliders->isNotEmpty())
                <div class="view-pager" id="viewPager">
                    @foreach ($sliders as $slide)
                        <div class="slide"><a href="{{ $slide->link ?? '#' }}"><img src="{{ $slide->img }}" alt="Promotional Banner"></a></div>
                    @endforeach
                </div>
                <div class="dots-container" id="dotsContainer"></div>
            @else
                <div class="no-slider-message"><span>No Sliders Available</span></div>
            @endif
        </div>

        <div class="notice-bar">
            <img src="{{ asset('assets/images/notification_icon.png') }}" class="notice-icon" alt="Notice">
            <div class="marquee"><span>{{ $noticeText }}</span></div>
        </div>

        <h2 class="section-header">ALL MATCHES</h2>
        <div class="match-grid">
            <a href="{{ route('matches.list', ['type' => 'br_match']) }}" class="game-card"><div class="banner" style="background-image: url('{{ asset('assets/images/IMG_20251113_175119_665.jpeg') }}');"></div><div class="info"><p class="title">BR MATCH</p><p class="count">{{ $gameCounts['br_match'] }} Matches Found</p></div></a>
            <a href="{{ route('matches.list', ['type' => 'clash_squad']) }}" class="game-card"><div class="banner" style="background-image: url('{{ asset('assets/images/IMG_20251113_175156_516.jpeg') }}');"></div><div class="info"><p class="title">Clash Squad</p><p class="count">{{ $gameCounts['clash_squad'] }} Matches Found</p></div></a>
            <a href="{{ route('matches.list', ['type' => 'cs_2v2']) }}" class="game-card"><div class="banner" style="background-image: url('{{ asset('assets/images/cs_2v2_banner.jpg') }}');"></div><div class="info"><p class="title">CS 2 VS 2</p><p class="count">{{ $gameCounts['cs_2v2'] }} Matches Found</p></div></a>
            <a href="{{ route('matches.list', ['type' => 'lone_wolf']) }}" class="game-card"><div class="banner" style="background-image: url('{{ asset('assets/images/IMG_20251113_174616_636.jpeg') }}');"></div><div class="info"><p class="title">LONE WOLF</p><p class="count">{{ $gameCounts['lone_wolf'] }} Matches Found</p></div></a>
            <a href="{{ route('matches.list', ['type' => 'ludo']) }}" class="game-card"><div class="banner" style="background-image: url('{{ asset('assets/images/ludo.jpeg') }}');"></div><div class="info"><p class="title">Ludo</p><p class="count">{{ $gameCounts['ludo'] }} Matches Found</p></div></a>
            <a href="{{ route('matches.list', ['type' => 'free_match']) }}" class="game-card"><div class="banner" style="background-image: url('{{ asset('assets/images/IMG_20251113_175135_559.jpeg') }}');"></div><div class="info"><p class="title">Free Match</p><p class="count">{{ $gameCounts['free_match'] }} Matches Found</p></div></a>
        </div>
    </div>
    
    <a href="{{ $supportLink }}" class="fab" target="_blank"><i class="fas fa-headset"></i></a>
    <div id="smart-bell-button"><i class="fas fa-bell"></i><span>Subscribe</span></div>
    
    @include('partials.bottom-nav')

    <!-- পুরনো Slider Script -->
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const viewPager = document.getElementById('viewPager');
    const dotsContainer = document.getElementById('dotsContainer');
    if (!viewPager) return; // no sliders

    const slides = viewPager.querySelectorAll('.slide');
    const totalSlides = slides.length;
    let currentIndex = 0;
    let autoSlideInterval;

    // Create dots dynamically
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (i === 0) dot.classList.add('active');
        dot.addEventListener('click', () => {
            currentIndex = i;
            updateSlider();
            resetAutoSlide();
        });
        dotsContainer.appendChild(dot);
    }

    const dots = dotsContainer.querySelectorAll('.dot');

    function updateSlider() {
        const offset = -currentIndex * 100;
        viewPager.style.transform = `translateX(${offset}%)`;
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalSlides;
        updateSlider();
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(nextSlide, 4000);
    }

    // Start automatic sliding
    autoSlideInterval = setInterval(nextSlide, 4000);
});
</script>
<!-- Popup Notification -->
<div id="popup-notification" style="
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background-color: rgba(0,0,0,0.6);
  justify-content: center;
  align-items: center;
  z-index: 9999;
">
  <div style="
    background: #fff;
    border-radius: 20px;
    width: 90%;
    max-width: 380px;
    text-align: center;
    overflow: hidden;
    box-shadow: 0 0 25px rgba(0,0,0,0.3);
    animation: popupFadeIn 0.4s ease;
  ">
    <!-- Header Section -->
    <div style="
      background: #FF0000;
      color: #fff;
      font-size: 18px;
      font-weight: bold;
      padding: 15px;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
    ">
      📢 OXFF TOUR Important Notice
    </div>

    <!-- Message Section -->
    <div style="padding: 25px 15px;">
      <p style="font-size: 12px; color: #333; line-height: 1.6;">
        ​👉 We Support only fair players 😎
​ দয়া করে নিয়মগুলো ভালোভাবে পড়ুন এবং মানুন সম্মত mode এর নিয়ম আলাদা 📝। যারা নিয়ম মেনে খেলতে পারবেন তাদের জন্য <strong>OX FF TOUR সেরা</strong> 👍। নিয়ম না মানতে পারলে ম্যাচে জয়েন করবেন না leave and go এই অ্যাপস তাদের জন্য !

​⛔️ একের অধিক (বেশি) ফ্রি ফায়ার আইডি দিয়ে এ অ্যাপে এ খেললে তাদের অ্যাপে থেকে ব্যান করা হবে!

​⛔️ যে কোনো ম্যাচে কোনো প্লেয়ারকে যদি ‘Garena Free Fire was eliminated by the system due to abnormal behaviour’ নোটিস দেখিয়ে সিস্টেম এলিমিনেট করে, তবে তাকে সঙ্গে সঙ্গে আমাদের অ্যাপ থেকে স্থায়ীভাবে (Permanent) ব্যান করা হবে!

​✅ withdraw Min-70 Max-5000 টাকার মধ্যে করা যাবে (5 টাকা খরচ কাটা হবে)। ভুয়া ইনফো দিলে রিকোয়েস্ট বাতিল হবে। প্রত্যেক দিনের উইথড্র সকাল ১১:০০ টার আগে পাবেন! আপনি সকাল ১১:০০টার পর উইথড্র রিকোয়েস্ট পাঠান তাহলে সেটা পরের দিন সকাল ১১:০০ টায় পাবেন, সারাদিন যতবার ইচ্ছে ততবার উইথড্র! 😉✅

​✅ যে কোন সমস্যায় আমাদের Discord/Whatsapp/Email এ জানাবেন, সমস্যা নিয়ে যদি আপনি আমাদের সাথে যোগাযোগ না করেন সে ক্ষেত্রে আপনার নিজেরই ক্ষতি আর আমরা সেটা জানতেও পারব না, তাই নির্দ্বিধায়আমাদেরকে মেসেজ করবেন

​⏰রুম আইডি/পাস ম্যাচ শুরুর ১০ মিনিট আগে শেয়ার করা হবে।
      </p>

      
    </div>

    <!-- Footer / Close Section -->
    <button onclick="closePopup()" style="
      width: 100%;
      background: #FF0000;
      color: white;
      border: none;
      font-size: 16px;
      padding: 12px 0;
      border-bottom-left-radius: 20px;
      border-bottom-right-radius: 20px;
      cursor: pointer;
    ">✖ CLOSE</button>
  </div>
</div>

<!-- Animation + Script -->
<style>
  @keyframes popupFadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
  }
</style>

<script>
  function showPopup() {
    document.getElementById('popup-notification').style.display = 'flex';
  }

  function closePopup() {
    document.getElementById('popup-notification').style.display = 'none';
  }

  // Show popup automatically after 2 seconds
  window.onload = function() {
    setTimeout(showPopup, 1000);
  };
</script>

    
    <!-- ========= Firebase এবং নোটিফিকেশন সিস্টেমের গোল্ডেন এবং চূড়ান্ত স্ক্রিপ্ট ========= -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>
    <script src="{{ asset('js/firebase-config.js') }}"></script>
    
    <script>
        // নিশ্চিত করা হচ্ছে যে firebaseConfig লোড হয়েছে
        if (typeof firebaseConfig !== 'undefined') {
            if (!firebase.apps.length) {
                firebase.initializeApp(firebaseConfig);
            }
            
            const messaging = firebase.messaging();
            const smartBellButton = document.getElementById('smart-bell-button');
            let bellClickCount = 0;

            function isWebView() {
                const userAgent = navigator.userAgent || navigator.vendor || window.opera;
                return /wv\)|WebView|Version\/\d\.\d|\[FB_IAB|FBAN|FBAV/i.test(userAgent);
            }

            function requestPermission() {
                Notification.requestPermission().then((permission) => {
                    if (permission === 'granted') {
                        if (smartBellButton) smartBellButton.classList.remove('visible');
                        showSuccessNotification();
                        getTokenAndSend();
                    } else {
                        if (smartBellButton) smartBellButton.classList.add('visible');
                    }
                });
            }

            function getTokenAndSend() {
                messaging.getToken({ vapidKey: firebaseVapidKey })
                    .then(sendTokenToServer)
                    .catch(err => console.error("Token retrieval error: ", err));
            }
            
            function sendTokenToServer(token) {
                if (!token) return;
                fetch('{{ route("store.token") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ token: token })
                });
            }

            function showSuccessNotification() {
                // ব্রাউজারের নিজস্ব Notification API ব্যবহার করে সফলতার বার্তা দেখানো
                const title = "✅ Notification Activated!";
                const options = {
                    body: "You'll now receive all updates from {{ config('app.name', 'OX FF TOUR') }}. ❤️📌",
                    icon: '{{ asset("assets/images/notification_icon.png") }}',
                    // ভাইব্রেশন যোগ করা হলো (মোবাইলের জন্য)
                    vibrate: [200, 100, 200]
                };
                // নিশ্চিত করা হচ্ছে যে সার্ভিস ওয়ার্কার রেডি আছে
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.ready.then(function(registration) {
                        registration.showNotification(title, options);
                    });
                }
            }

            // ** চূড়ান্ত সমাধান: ফোরগ্রাউন্ড মেসেজ হ্যান্ডলিং **
            // ওয়েবসাইট খোলা থাকা অবস্থায়ও নোটিফিকেশন দেখাবে
            messaging.onMessage((payload) => {
                console.log('Foreground message received: ', payload);
                const notificationTitle = payload.notification.title;
                const notificationOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.image || '{{ asset("assets/images/notification_icon.png") }}'
                };
                new Notification(notificationTitle, notificationOptions);
            });

            @auth
                window.addEventListener('load', () => {
                    if (isWebView()) return;

                    // লগইন করার পর সরাসরি পারমিশন পপ-আপ
                    if (Notification.permission === 'default') {
                        requestPermission();
                    } 
                    // যদি অনুমতি আগে থেকেই দেওয়া থাকে
                    else if (Notification.permission === 'granted') {
                        getTokenAndSend();
                    } 
                    // যদি ব্যবহারকারী আগে থেকেই ব্লক করে থাকেন
                    else if (Notification.permission === 'denied') {
                        if (smartBellButton) smartBellButton.classList.add('visible');
                    }
                });

                // স্মার্ট বেল বাটনের জন্য ইভেন্ট লিসেনার
                if (smartBellButton) {
                    smartBellButton.addEventListener('click', () => {
                        bellClickCount++;
                        smartBellButton.classList.add('show-text');
                        
                        // দ্বিতীয় ক্লিকে পারমিশন চাওয়া হবে
                        if (bellClickCount > 1) {
                            requestPermission();
                            smartBellButton.classList.remove('show-text');
                            bellClickCount = 0;
                        } else {
                            // প্রথম ক্লিকে লেখাটি ২.৫ সেকেন্ডের জন্য দেখা যাবে
                            setTimeout(() => {
                                smartBellButton.classList.remove('show-text');
                            }, 2500);
                        }
                    });
                }
            @endauth
        }
    </script>
    <h2 class="section-header" style="margin-top: 30px; display: flex; align-items:center; justify-content:center; gap:10px;">
    SEE LIVE MATCHES
    <span style="width:12px; height:12px; background:red; border-radius:50%; animation: blink 1s infinite;"></span>
</h2>

<div style="background:#fff; border-radius:16px; padding:10px; border:2px solid #e3e8f0; box-shadow:0 5px 20px rgba(0,0,0,0.05);">
    <div class="video-wrapper" style="position:relative; width:100%; padding-bottom:56.25%; overflow:hidden; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); transform: translateZ(0);">
        <iframe 
            src="https://www.youtube.com/embed/k93YJqCh_5E?autoplay=1&controls=0&modestbranding=1&rel=0" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen
            style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;">
        </iframe>
    </div>
    <p style="text-align:center; margin-top:10px; font-size:14px; color:#7f8c8d;">
        Watch the ongoing tournament live!
    </p>
</div>

<style>
@keyframes blink {
    0%,50%,100% { opacity:1; }
    25%,75% { opacity:0; }
}
</style>
</body>
</html>