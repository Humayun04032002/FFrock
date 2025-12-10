<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joiners: {{ $match->Match_Title ?? 'Match Joiners' }}</title>
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
        .main-content { 
            width: 100%; 
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px;
        }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 24px; font-weight: 600; }
        .btn-back { text-decoration: none; color: var(--text-secondary); background-color: var(--bg-light-dark); border: 1px solid var(--border-color); padding: 8px 15px; border-radius: 8px; display: flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-back:hover { background-color: var(--primary-color); color: white; }
        .table-container { background-color: var(--bg-light-dark); border-radius: 10px; padding: 20px; border: 1px solid var(--border-color); overflow-x: auto;}
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border-color); white-space: nowrap; }
        th { color: var(--text-secondary); font-weight: 600; text-transform: uppercase; font-size: 12px; }
        tr:hover { background-color: rgba(255, 255, 255, 0.03); }
        .btn-refund { background-color: var(--danger); color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; transition: background-color 0.3s; display: flex; gap: 6px; align-items: center; }
        .btn-refund:hover { background-color: #e55052; }
        .no-data { text-align: center; color: var(--text-secondary); padding: 40px; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid transparent; text-align: center; }
        .alert-success { background-color: rgba(0, 184, 148, 0.2); color: var(--success); border-color: var(--success); }
        .alert-error { background-color: rgba(214, 48, 49, 0.2); color: var(--danger); border-color: var(--danger); }
    </style>
</head>
<body>
    <div class="main-content">
        <header class="header">
            <h1>{{ $match->Match_Title ?? 'Match Joiners' }} <span style="color:var(--text-secondary); font-weight:400;">(List of Players)</span></h1>
            <a href="{{ route('admin.matches.index', $game_type) }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Matches</a>
        </header>

        <main>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <div class="table-container">
                <table>
                    <thead>
                        <tr><th>#</th><th>User Name</th><th>In-Game Name</th><th>User Number</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($joiners as $index => $joiner)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $joiner->Name }}</td>
                                <td>{{ $joiner->ingame_name }}</td>
                                <td>{{ $joiner->Number }}</td>
                                <td>
                                    <form action="{{ route('admin.matches.refund', ['game_type' => $game_type, 'join_id' => $joiner->id]) }}" method="post" 
                                          onsubmit="return confirm('Are you sure you want to refund ৳{{ $match->Entry_Fee ?? 0 }} to this player?');">
                                        @csrf
                                        <input type="hidden" name="entry_fee" value="{{ $match->Entry_Fee ?? 0 }}">
                                        <button type="submit" class="btn-refund"><i class="fas fa-undo"></i> Refund</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="no-data">No players have joined this match yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>