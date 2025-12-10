<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Settings - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6C5CE7; --secondary-color: #A29BFE; --bg-dark: #0F0F1A;
            --bg-light-dark: #1D1D30; --text-light: #F5F6FA; --text-secondary: #A29BFE;
            --border-color: rgba(255, 255, 255, 0.1); --success: #00B894; --danger: #D63031;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-light); }
        
        .main-content { max-width: 1100px; margin: 0 auto; padding: 30px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-title-wrapper { display: flex; align-items: center; gap: 20px; }
        .header h1 { font-size: 28px; font-weight: 600; }
        .user-info { font-size: 16px; color: var(--text-secondary); }
        .user-info span { font-weight: 600; color: var(--text-light); }
        .btn-back { text-decoration: none; color: var(--text-secondary); background-color: var(--bg-light-dark); border: 1px solid var(--border-color); padding: 8px 15px; border-radius: 8px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-back:hover { background-color: var(--primary-color); color: white; border-color: var(--primary-color); }
        .form-section { background-color: var(--bg-light-dark); border-radius: 10px; padding: 25px; margin-bottom: 25px; border: 1px solid var(--border-color); }
        .form-section h2 { font-size: 20px; font-weight: 500; margin-top: 0; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--border-color); color: var(--secondary-color); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-secondary); }
        input[type="text"], input[type="number"], input[type="url"], textarea, select { width: 100%; padding: 12px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-light); font-size: 1em; transition: border-color 0.3s, box-shadow 0.3s; }
        input:focus, textarea:focus, select:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.2); }
        textarea { resize: vertical; min-height: 100px; }
        .form-group p { font-size: 0.9em; color: #888; margin-top: 8px; }
        .grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .payment-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .btn-save { width: auto; padding: 12px 30px; background-color: var(--primary-color); border: none; border-radius: 8px; color: white; font-size: 16px; font-weight: 600; cursor: pointer; transition: background-color 0.3s; display: block; margin-top: 10px; }
        .btn-save:hover { background-color: var(--secondary-color); }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid transparent; }
        .alert-success { background-color: rgba(0, 184, 148, 0.2); color: var(--success); border-color: var(--success); }
        .alert-error { background-color: rgba(214, 48, 49, 0.2); color: var(--danger); border-color: var(--danger); }
    </style>
</head>
<body>
    <div class="main-content">
        <header class="header">
            <div class="header-title-wrapper">
                <h1>App Settings</h1>
                <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            </div>
            <div class="user-info">
                </span>
            </div>
        </header>

        <main>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            
            <form action="{{ route('admin.settings.update') }}" method="post">
                @csrf
                <div class="form-section">
                    <h2><i class="fas fa-mobile-alt"></i> Splash Screen</h2>
                    <div class="grid-layout">
                        <div class="form-group"><label for="splash_title">Splash Screen Title</label><input type="text" id="splash_title" name="splash_title" value="{{ old('splash_title', $settings->{'Splash Title'}) }}"></div>
                        <div class="form-group"><label for="splash_logo_url">Splash Logo URL</label><input type="url" id="splash_logo_url" name="splash_logo_url" value="{{ old('splash_logo_url', $settings->{'Splash Logo URL'}) }}"></div>
                    </div>
                </div>
                <div class="form-section">
                    <h2><i class="fas fa-bullhorn"></i> Homepage Notice</h2>
                    <div class="form-group"><label for="home_notice">This text will scroll on the user's homepage.</label><textarea id="home_notice" name="home_notice">{{ old('home_notice', $notice->Notice) }}</textarea></div>
                </div>
                <div class="form-section">
                    <h2><i class="fas fa-cogs"></i> Payment Verification Mode</h2>
                    <div class="form-group">
                        <label for="payment_mode">Select how payments should be verified</label>
                        <select id="payment_mode" name="payment_mode">
                            <option value="Auto" @selected(old('payment_mode', $settings->Payment_Mode) == 'Auto')>Automatic (via SMS App)</option>
                            <option value="Manual" @selected(old('payment_mode', $settings->Payment_Mode) == 'Manual')>Manual (Admin Approval)</option>
                        </select>
                        <p>'Automatic' instantly adds balance if TrxID from SMS app matches. 'Manual' sends requests for admin approval.</p>
                    </div>
                </div>
                <div class="form-section">
                    <h2><i class="fas fa-money-check-alt"></i> Payment Numbers</h2>
                    <div class="payment-grid">
                        <div class="form-group"><label>bKash Number</label><input type="text" name="bkash" value="{{ old('bkash', $settings->{'bKash Number'}) }}"></div>
                        <div class="form-group"><label>Nagad Number</label><input type="text" name="nagad" value="{{ old('nagad', $settings->{'Nagad Number'}) }}"></div>
                        <div class="form-group"><label>Rocket Number</label><input type="text" name="rocket" value="{{ old('rocket', $settings->{'Rocket Number'}) }}"></div>
                    </div>
                </div>
                <div class="form-section">
                    <h2><i class="fas fa-exchange-alt"></i> Transaction Limits</h2>
                    <div class="grid-layout">
                        <div class="form-group"><label>Minimum Deposit</label><input type="number" step="0.01" name="min_deposit" value="{{ old('min_deposit', $settings->{'Minimum Deposit'}) }}"></div>
                        <div class="form-group"><label>Minimum Withdraw</label><input type="number" step="0.01" name="min_withdraw" value="{{ old('min_withdraw', $settings->{'Minimum Withdraw'}) }}"></div>
                    </div>
                </div>
                <div class="form-section">
                    <h2><i class="fas fa-link"></i> Important Links</h2>
                    <div class="grid-layout">
                        <div class="form-group"><label>Support Link</label><input type="url" name="support_link" value="{{ old('support_link', $settings->Support) }}"></div>
                        <div class="form-group"><label>App Link</label><input type="url" name="app_link" value="{{ old('app_link', $settings->{'APP LINK'}) }}"></div>
                    </div>
                </div>
                <button type="submit" class="btn-save"><i class="fas fa-save"></i> Save All Settings</button>
            </form>
        </main>
    </div>
</body>
</html>