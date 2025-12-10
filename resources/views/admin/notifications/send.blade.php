<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notification - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #8A2BE2; --secondary-color: #4A90E2; --bg-dark: #0a0a14;
            --bg-light-dark: #141421; --text-light: #f0f0f0; --text-secondary: #a0a0b0;
            --border-color: rgba(255, 255, 255, 0.1); --success: #28a745; --danger: #dc3545;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            background-image: radial-gradient(circle at 1% 1%, rgba(138, 43, 226, 0.15), transparent 30%), radial-gradient(circle at 99% 99%, rgba(74, 144, 226, 0.15), transparent 30%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .panel {
            background: var(--bg-light-dark);
            padding: 40px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 600px;
            position: relative;
        }
        .panel-header { text-align: center; margin-bottom: 30px; }
        .panel-header h2 { font-size: 28px; font-weight: 700; color: var(--text-light); margin: 0; }
        .panel-header p { color: var(--text-secondary); margin-top: 5px; }
        .form-group { margin-bottom: 25px; }
        label { display: block; margin-bottom: 10px; font-weight: 500; color: var(--text-secondary); font-size: 14px; }
        .input-field { width: 100%; padding: 15px; background-color: #2c2c2c; border: 1px solid #444; border-radius: 8px; box-sizing: border-box; color: var(--text-light); font-size: 16px; transition: border-color 0.3s, box-shadow 0.3s; }
        .input-field:focus { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.25); outline: none; }
        textarea.input-field { resize: vertical; min-height: 140px; }
        .btn { width: 100%; padding: 16px; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(138, 43, 226, 0.4); }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 25px; text-align: center; font-size: 15px; border: 1px solid transparent; }
        .alert-success { background-color: rgba(40, 167, 69, 0.2); color: #28a745; border-color: #28a745; }
        .alert-danger { background-color: rgba(220, 53, 69, 0.2); color: #dc3545; border-color: #dc3545; }
        .back-btn { position: absolute; top: 20px; left: 20px; width: 40px; height: 40px; background-color: rgba(255, 255, 255, 0.1); color: var(--text-light); border-radius: 50%; display: grid; place-items: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s, transform 0.3s; }
        .back-btn:hover { background-color: var(--primary-color); transform: scale(1.1); }
    </style>
</head>
<body>
<div class="panel">
    
    <a href="{{ route('admin.dashboard') }}" class="back-btn" title="Back to Dashboard">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="panel-header">
        <h2><i class="fas fa-paper-plane" style="color: var(--primary-color); margin-right: 10px;"></i>Send Notification</h2>
        <p>Compose and send a push notification to all users.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{!! session('error') !!}</div>
    @endif

    <form method="POST" action="{{ route('admin.notifications.send') }}">
        @csrf
        <div class="form-group">
            <label for="title">Notification Title</label>
            <input type="text" id="title" name="title" class="input-field" value="{{ old('title') }}" placeholder="e.g., New Match Added!" required>
        </div>
        <div class="form-group">
            <label for="body">Notification Body</label>
            <textarea id="body" name="body" class="input-field" placeholder="Enter your message details here..." required>{{ old('body') }}</textarea>
        </div>
        <div class="form-group">
            <label for="image_url">Image URL (Optional)</label>
            <input type="text" id="image_url" name="image_url" class="input-field" value="{{ old('image_url') }}" placeholder="https://example.com/image.png">
        </div>
        <button type="submit" class="btn">
            <i class="fas fa-satellite-dish"></i>
            <span>Broadcast Now</span>
        </button>
    </form>
</div>
</body>
</html>