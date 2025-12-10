<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $page_title }} - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6C5CE7; --secondary-color: #A29BFE; --bg-dark: #0F0F1A;
            --bg-light-dark: #1D1D30; --text-light: #F5F6FA; --text-secondary: #A29BFE;
            --border-color: rgba(255, 255, 255, 0.1); --success: #00B894; --danger: #D63031;
            --warning: #FF9800; --live: #E84393;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-light); }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px; }
        .header-title-wrapper { display: flex; align-items: center; gap: 15px; }
        .header h1 { font-size: 28px; font-weight: 600; }
        .header-actions { display: flex; gap: 15px; }
        .btn { text-decoration: none; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 500; transition: all 0.3s; display: flex; align-items: center; gap: 8px; border: none; cursor: pointer; }
        .btn-create { background-color: var(--primary-color); }
        .btn-create:hover { background-color: var(--secondary-color); }
        .btn-back { background-color: var(--bg-light-dark); border: 1px solid var(--border-color); color: var(--text-secondary); }
        .btn-back:hover { background-color: var(--primary-color); border-color: var(--primary-color); color: white; }

        .tabs { display: flex; justify-content: center; gap: 10px; border-bottom: 1px solid var(--border-color); margin-bottom: 30px; }
        .tab-link { padding: 10px 25px; cursor: pointer; border: none; background: none; font-size: 16px; font-weight: 500; color: var(--text-secondary); border-bottom: 3px solid transparent; transition: all 0.3s; }
        .tab-link.active { border-bottom-color: var(--primary-color); color: var(--text-light); }
        .tab-content { display: none; animation: fadeIn 0.5s; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .matches-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 25px; }
        .match-card { background-color: var(--bg-light-dark); border-radius: 12px; border-left: 5px solid var(--primary-color); padding: 25px; display: flex; flex-direction: column; gap: 18px; transition: transform 0.3s, box-shadow 0.3s; }
        .match-card:hover { transform: translateY(-7px); box-shadow: 0 12px 25px rgba(0,0,0,0.25); }
        .match-card.live { border-left-color: var(--live); }
        .match-card.completed { border-left-color: var(--success); opacity: 0.8; }
        .card-header { display: flex; justify-content: space-between; align-items: flex-start; }
        .card-header h3 { margin: 0; font-size: 18px; line-height: 1.4; color: var(--text-light); }
        .match-status { font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 20px; text-transform: uppercase; }
        .status-live { background-color: var(--live); color: white; }
        .status-upcoming { background-color: var(--warning); color: #111; }
        .status-completed { background-color: var(--success); color: white; }
        .card-details { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; font-size: 14px; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); padding: 18px 0; }
        .detail-item { text-align: center; }
        .detail-item span { display: block; color: var(--text-secondary); font-size: 12px; margin-bottom: 5px; }
        .card-actions { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: auto; padding-top: 15px; }
        .card-actions .action-btn { text-decoration: none; color: white; padding: 8px 12px; border-radius: 6px; font-size: 13px; font-weight: 500; text-align: center; transition: transform 0.2s, background-color 0.2s; display: flex; align-items: center; gap: 6px; border: none; cursor: pointer; }
        .card-actions .action-btn:hover { transform: scale(1.05); }
        .btn-edit { background-color: #4CAF50; } .btn-joiners { background-color: var(--warning); color: #111;}
        .btn-result { background-color: var(--primary-color); } .btn-delete { background-color: var(--danger); }
        .no-match { text-align: center; color: var(--text-secondary); padding: 50px; background-color: var(--bg-light-dark); border-radius: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header-title-wrapper">
                <h1><i class="fas {{ $page_icon }}"></i> {{ $page_title }}</h1>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
                <a href="{{ route('admin.matches.create', $game_type) }}" class="btn btn-create"><i class="fas fa-plus"></i> Create Match</a>
            </div>
        </header>

        <main>
            <div class="tabs">
                <button class="tab-link active" onclick="openTab(event, 'Upcoming')">Upcoming & Live</button>
                <button class="tab-link" onclick="openTab(event, 'Completed')">Completed</button>
            </div>

            <div id="Upcoming" class="tab-content active">
                <div class="matches-grid">
                    @php
                        $live_matches = $all_matches->get('OnGoing', collect());
                        $upcoming_matches = $all_matches->get('Match', collect());
                        $matches_to_show = $live_matches->merge($upcoming_matches);
                    @endphp
                    @forelse ($matches_to_show as $match)
                        <div class="match-card {{ $match->Position == 'OnGoing' ? 'live' : '' }}">
                            <div class="card-header">
                                <h3>{{ $match->Match_Title }}</h3>
                                <span class="match-status {{ $match->Position == 'OnGoing' ? 'status-live' : 'status-upcoming' }}">
                                    {{ $match->Position == 'OnGoing' ? 'Live' : 'Upcoming' }}
                                </span>
                            </div>
                            <div class="card-details">
                                <div class="detail-item"><span><i class="far fa-clock"></i> Time</span> {{ \Carbon\Carbon::parse($match->Match_Time)->format('d M, h:i A') }}</div>
                                <div class="detail-item"><span><i class="fas fa-users"></i> Slots</span> {{ $match->Player_Join }} / {{ $match->Player_Need }}</div>
                                <div class="detail-item"><span><i class="fas fa-trophy"></i> Prize</span> {{ $match->Total_Prize }} BDT</div>
                            </div>
                            <div class="card-actions">
                                <a href="{{ route('admin.matches.edit', ['game_type' => $game_type, 'match' => $match->id]) }}" class="action-btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                <a href="{{ route('admin.matches.joiners', ['game_type' => $game_type, 'match_key' => $match->Match_Key]) }}" class="action-btn btn-joiners"><i class="fas fa-user-friends"></i> Joiners</a>
                                <a href="{{ route('admin.matches.result.form', ['game_type' => $game_type, 'match_key' => $match->Match_Key]) }}" class="action-btn btn-result"><i class="fas fa-flag-checkered"></i> Result</a>
                                <form action="{{ route('admin.matches.destroy', ['game_type' => $game_type, 'match' => $match->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this match and all its related data? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="no-match">No upcoming or live matches found.</p>
                    @endforelse
                </div>
            </div>
            
            <div id="Completed" class="tab-content">
                <div class="matches-grid">
                    @forelse ($all_matches->get('Result', collect()) as $match)
                    <div class="match-card completed">
                       <div class="card-header">
                           <h3>{{ $match->Match_Title }}</h3>
                           <span class="match-status status-completed">Completed</span>
                       </div>
                       <div class="card-details">
                            <div class="detail-item"><span><i class="far fa-clock"></i> Time</span> {{ \Carbon\Carbon::parse($match->Match_Time)->format('d M, h:i A') }}</div>
                            <div class="detail-item"><span><i class="fas fa-users"></i> Slots</span> {{ $match->Player_Join }} / {{ $match->Player_Need }}</div>
                            <div class="detail-item"><span><i class="fas fa-trophy"></i> Prize</span> {{ $match->Total_Prize }} BDT</div>
                        </div>
                       <div class="card-actions">
                           <a href="{{ route('admin.matches.joiners', ['game_type' => $game_type, 'match_key' => $match->Match_Key]) }}" class="action-btn btn-joiners"><i class="fas fa-user-friends"></i> Joiners</a>
                           <form action="{{ route('admin.matches.destroy', ['game_type' => $game_type, 'match' => $match->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this match and all its related data? This action cannot be undone.');">
                               @csrf
                               @method('DELETE')
                               <button type="submit" class="action-btn btn-delete"><i class="fas fa-trash"></i> Delete</button>
                           </form>
                       </div>
                    </div>
                    @empty
                        <p class="no-match">No completed matches found.</p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
    <script>
        function openTab(evt, tabName) {
            let i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
                tabcontent[i].classList.remove("active");
            }
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            document.getElementById(tabName).style.display = "block";
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
        document.addEventListener("DOMContentLoaded", function() {
            if(document.querySelector(".tab-link.active")) {
               document.querySelector(".tab-link.active").click();
            }
        });
    </script>
</body>
</html>