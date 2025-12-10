<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Match: {{ $match->Match_Title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6C5CE7; --secondary-color: #A29BFE; --bg-dark: #0F0F1A;
            --bg-light-dark: #1D1D30; --text-light: #F5F6FA; --text-secondary: #A29BFE;
            --border-color: rgba(255, 255, 255, 0.1); --success: #00B894;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-light); }
        .main-content { padding: 30px; max-width: 900px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 24px; }
        .btn-back { text-decoration: none; color: var(--text-secondary); background-color: var(--bg-light-dark); border: 1px solid var(--border-color); padding: 8px 15px; border-radius: 8px; transition: all 0.3s; display: flex; align-items:center; gap: 8px; }
        .btn-back:hover { background-color: var(--primary-color); color: white; }
        .form-section { background-color: var(--bg-light-dark); border-radius: 10px; padding: 25px; margin-bottom: 25px; border: 1px solid var(--border-color); }
        .form-section h2 { font-size: 20px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--border-color); color: var(--secondary-color); display: flex; align-items:center; gap: 10px;}
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-secondary); }
        input[type="text"], input[type="number"], select { width: 100%; padding: 12px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-light); font-size: 1em; }
        .grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .btn-save { width: 100%; padding: 12px; background-color: var(--success); border: none; border-radius: 8px; color: white; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; font-weight: 600; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align:center; border: 1px solid transparent; }
        .alert-success { background-color: rgba(0, 184, 148, 0.2); color: var(--success); }
    </style>
</head>
<body>
    <div class="main-content">
        <header class="header">
            <h1><i class="fas fa-edit"></i> Edit Match</h1>
            <a href="{{ route('admin.matches.index', $game_type) }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Matches</a>
        </header>

        <main>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('admin.matches.update', ['game_type' => $game_type, 'match' => $match->id]) }}" method="post">
                @csrf
                @method('PUT')
                
                <div class="form-section">
                    <h2><i class="fas fa-info-circle"></i> Basic Details</h2>
                    <div class="form-group">
                        <label>Match Title</label>
                        <input type="text" name="Match_Title" value="{{ old('Match_Title', $match->Match_Title) }}">
                    </div>
                    <div class="grid-layout">
                        <div class="form-group"><label>Total Prize</label><input type="number" name="Total_Prize" value="{{ old('Total_Prize', $match->Total_Prize) }}"></div>
                        <div class="form-group"><label>Per Kill</label><input type="number" name="Per_Kill" value="{{ old('Per_Kill', $match->Per_Kill) }}"></div>
                        <div class="form-group"><label>Entry Fee</label><input type="number" name="Entry_Fee" value="{{ old('Entry_Fee', $match->Entry_Fee) }}"></div>
                        <div class="form-group">
                            <label>Match Status</label>
                            <select name="Position">
                                <option value="Match" @selected(old('Position', $match->Position) == 'Match')>Upcoming</option>
                                <option value="OnGoing" @selected(old('Position', $match->Position) == 'OnGoing')>Live / OnGoing</option>
                                <option value="Result" @selected(old('Position', $match->Position) == 'Result')>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <h2><i class="fas fa-key"></i> Room Credentials</h2>
                    <div class="grid-layout">
                        <div class="form-group"><label>Room ID</label><input type="text" name="Room_ID" value="{{ old('Room_ID', $match->Room_ID) }}"></div>
                        <div class="form-group"><label>Password</label><input type="text" name="Room_Pass" value="{{ old('Room_Pass', $match->Room_Pass) }}"></div>
                    </div>
                </div>
                <button type="submit" class="btn-save"><i class="fas fa-save"></i> Save Changes</button>
            </form>
        </main>
    </div>
</body>
</html>