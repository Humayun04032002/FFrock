<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>My Matches</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --bg-color: #f4f6f9; --card-bg: #ffffff; --text-color: #333333; --light-text-color: #777777; --primary-text-color: #00BFA6; --header-bg: #ffffff; }
        body { font-family: 'Roboto', sans-serif; background-color: var(--bg-color); margin: 0; padding-bottom: 85px; }
        .header { background-color: var(--header-bg); text-align: center; padding: 15px; font-size: 1.2em; font-weight: bold; color: var(--text-color); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .match-list-container { padding: 15px; }
        .match-card { background-color: var(--card-bg); border-radius: 12px; margin-bottom: 15px; padding: 15px; display: flex; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .match-rank { font-size: 1.5em; font-weight: bold; color: var(--light-text-color); margin-right: 15px; min-width: 20px; }
        .match-details { flex-grow: 1; }
        .match-title { font-weight: 500; color: var(--primary-text-color); margin: 0 0 5px 0; line-height: 1.3; }
        .match-time { font-size: 0.8em; color: var(--light-text-color); margin-bottom: 8px; }
        .won-amount { font-weight: 500; color: var(--text-color); }
        .match-meta { text-align: right; }
        .match-position { font-size: 1.1em; font-weight: bold; color: var(--light-text-color); margin-bottom: 5px; }
        .entry-fee { background-color: #333; color: white; padding: 4px 10px; border-radius: 5px; font-size: 0.8em; font-weight: bold; display: inline-block; }
        .no-match { text-align: center; color: #888; padding: 40px; background: white; border-radius: 12px; }
    </style>
</head>
<body>
    <div class="header">MY MATCHES</div>

    <div class="match-list-container">
        @forelse ($playedMatches as $index => $match)
            <div class="match-card">
                <div class="match-rank">{{ $index + 1 }}</div>
                <div class="match-details">
                    <p class="match-title">
                        {{ $match->Match_Title }} | {{ $match->Entry_Type }}
                    </p>
                    <p class="match-time">{{ \Carbon\Carbon::parse($match->Match_Time)->format('Y-m-d h:i A') }}</p>
                    <p class="won-amount">Won Amount {{ $match->winnings }} TK</p>
                </div>
                <div class="match-meta">
                    @if ($match->position)
                        <p class="match-position">#{{ $match->position }}</p>
                    @endif
                    <span class="entry-fee">{{ $match->Entry_Fee }} TK</span>
                </div>
            </div>
        @empty
            <p class="no-match">You haven't played any matches yet.</p>
        @endforelse
    </div>

    @include('partials.bottom-nav')
</body>
</html>