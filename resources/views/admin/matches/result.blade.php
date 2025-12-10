<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Result: {{ $match->Match_Title }}</title>
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
        .main-content { padding: 30px; max-width: 800px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 24px; }
        .btn-back { text-decoration: none; color: var(--text-secondary); background-color: var(--bg-light-dark); border: 1px solid var(--border-color); padding: 8px 15px; border-radius: 8px; transition: all 0.3s; display: flex; align-items:center; gap: 8px; }
        .btn-back:hover { background-color: var(--primary-color); color: white; }
        .form-section { background-color: var(--bg-light-dark); border-radius: 10px; padding: 25px; border: 1px solid var(--border-color); }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-secondary); }
        .form-group label span { font-weight: 700; color: var(--success); background-color: rgba(0, 184, 148, 0.1); padding: 2px 6px; border-radius: 4px; }
        select { width: 100%; padding: 12px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-light); font-size: 1em; }
        select:disabled { background-color: var(--bg-dark); opacity: 0.5; cursor: not-allowed; }
        .btn-submit { width: 100%; padding: 12px; background-color: var(--primary-color); border: none; border-radius: 8px; color: white; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; font-weight: 600; margin-top:10px; }
        .btn-submit:disabled { background-color: #555; cursor: not-allowed; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align:center; border: 1px solid transparent; }
        .alert-success { background-color: rgba(0, 184, 148, 0.2); color: var(--success); }
        .alert-error { background-color: rgba(214, 48, 49, 0.2); color: var(--danger); }
    </style>
</head>
<body>
    <div class="main-content">
        <header class="header">
            <h1>Process Result</h1>
            <a href="{{ route('admin.matches.index', $game_type) }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Matches</a>
        </header>
        <main>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <div class="form-section">
                <form action="{{ route('admin.matches.result.process', ['game_type' => $game_type, 'match_key' => $match->Match_Key]) }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label>1st Place Winner (Prize: <span>৳{{ $match->prize_1st }}</span>)</label>
                        <select name="winner_1" @if ($match->Position == 'Result' || $match->prize_1st <= 0) disabled @endif>
                            <option value="">Select 1st Winner</option>
                            @foreach ($joiners as $player)
                                <option value="{{ $player->Number }}">{{ $player->ingame_name }} ({{ $player->Name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>2nd Place Winner (Prize: <span>৳{{ $match->prize_2nd }}</span>)</label>
                        <select name="winner_2" @if ($match->Position == 'Result' || $match->prize_2nd <= 0) disabled @endif>
                            <option value="">Select 2nd Winner</option>
                            @foreach ($joiners as $player)
                                <option value="{{ $player->Number }}">{{ $player->ingame_name }} ({{ $player->Name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>3rd Place Winner (Prize: <span>৳{{ $match->prize_3rd }}</span>)</label>
                        <select name="winner_3" @if ($match->Position == 'Result' || $match->prize_3rd <= 0) disabled @endif>
                            <option value="">Select 3rd Winner</option>
                            @foreach ($joiners as $player)
                                <option value="{{ $player->Number }}">{{ $player->ingame_name }} ({{ $player->Name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-submit" @if ($match->Position == 'Result') disabled @endif>
                        <i class="fas fa-flag-checkered"></i> 
                        {{ ($match->Position == 'Result') ? 'Result Already Processed' : 'Submit & Distribute Prize' }}
                    </button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>