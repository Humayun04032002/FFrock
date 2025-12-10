<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Joined Players - {{ $match->Match_Title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; margin: 0; background: #f0f2f5; color: #333; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .btn-back { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #6C5CE7; color: white; text-decoration: none; border-radius: 8px; transition: 0.3s; }
        .btn-back:hover { background: #5a4cd6; }
        h1 { font-size: 1.8em; margin-bottom: 20px; text-align: center; }
        .table-container { background: white; border-radius: 10px; padding: 15px; overflow-x: auto; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        table { width: 100%; border-collapse: collapse; min-width: 400px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f6fa; text-transform: uppercase; font-weight: 600; font-size: 0.85em; color: #555; }
        tr:hover { background: rgba(108, 92, 231, 0.05); }
        .no-data { text-align: center; padding: 30px 0; color: #777; font-weight: 500; }
        @media(max-width: 600px) {
            th, td { font-size: 0.85em; padding: 10px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('matches.list', ['type' => $match->Match_Type]) }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Back to Matches
</a>

        <h1>Joined Players - {{ $match->Match_Title }}</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>In-Game Name</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($joiners as $index => $joiner)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $joiner->ingame_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="no-data">No players have joined this match yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- ADD THIS BELOW THE TABLE -->
<div class="rules-section" style="background:white; margin-top:20px; padding:15px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
    <h3 style="margin-top:0;"><i class="fas fa-scroll"></i> Rules & Regulations</h3>
    <pre style="white-space:pre-wrap; font-size:14px;">{{ $rulesText }}</pre>
</div>
    </div>
</body>
</html>
