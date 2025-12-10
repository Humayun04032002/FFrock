<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Refer & Earn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --primary-color: #6a00f4; --bg-color: #f0f1f6; --card-bg: white; --text-color: #333; }
        body { font-family: 'Roboto', sans-serif; background-color: var(--bg-color); margin: 0; padding: 15px; padding-bottom: 85px; }
        .header { display: flex; align-items: center; margin-bottom: 20px; }
        .back-btn { background: none; border: none; font-size: 24px; color: var(--text-color); cursor: pointer; margin-right: 15px; }
        .header h1 { font-size: 22px; margin: 0; }
        .refer-card { background-color: var(--card-bg); border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.07); }
        .refer-card h1 { margin-top: 0; color: var(--primary-color); }
        .refer-card p { color: #666; line-height: 1.6; }
        .referral-code-box { background-color: #f7f7f7; border: 2px dashed var(--primary-color); padding: 15px; border-radius: 10px; margin: 20px 0; user-select: all; -webkit-user-select: all; }
        .referral-code-box span { font-size: 1.5em; font-weight: bold; letter-spacing: 2px; }
        .share-btn { background-color: var(--primary-color); color: white; padding: 12px 25px; border-radius: 8px; border: none; font-size: 1em; cursor: pointer; transition: background-color 0.2s; }
        .share-btn:hover { background-color: #5500c8; }
    </style>
</head>
<body>
    <div class="header">
        <button onclick="window.history.back()" class="back-btn"><i class="fas fa-arrow-left"></i></button>
        <h1>Refer & Earn</h1>
    </div>

    <div class="refer-card">
        <h1>Refer Friends, Earn Rewards!</h1>
        <p>Share your unique referral code with your friends. When they sign up using your code, you both get exciting rewards!</p>
        <div class="referral-code-box">
            <span>{{ $referralCode }}</span>
        </div>
        <button class="share-btn" onclick="shareCode()">
            <i class="fas fa-share-alt"></i> Share Now
        </button>
    </div>

    @include('partials.bottom-nav')

    <script>
        function shareCode() {
            // কন্ট্রোলার থেকে পাঠানো ভ্যারিয়েবলটি ব্যবহার করা হচ্ছে
            const referralCode = "{{ $referralCode }}";
            const shareText = `Join me on OX FF TOUR and get exciting rewards! Use my referral code: ${referralCode}`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'Join Khelo Bangladesh',
                    text: shareText,
                    url: "{{ url('/') }}" // আপনার সাইটের মূল URL
                }).catch(console.error);
            } else {
                // ফলব্যাক: কোডটি ক্লিপবোর্ডে কপি করা
                navigator.clipboard.writeText(referralCode).then(() => {
                    alert('Referral code copied to clipboard!');
                }, (err) => {
                    alert('Could not copy text: ', err);
                });
            }
        }
    </script>
</body>
</html>