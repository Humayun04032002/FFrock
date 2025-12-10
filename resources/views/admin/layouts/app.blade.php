<!DOCTYPE html>
<html lang="en">
<head>
<meta name="google-site-verification" content="0zGIm2YhI5ORDUVoqWlIo9E2hBdIxLuxfDOgBNRBik8" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Admin Panel') - {{ $site_name ?? 'Khelo' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* --- অ্যাডমিন প্যানেলের সকল CSS কোড এখানে থাকবে --- */
        :root {
            --primary-color: #6C5CE7; --secondary-color: #A29BFE; --bg-dark: #0F0F1A;
            --bg-light-dark: #1D1D30; --text-light: #F5F6FA; --text-secondary: #A29BFE;
            --border-color: rgba(255, 255, 255, 0.1); --success: #00B894; --danger: #D63031;
            --warning: #FF9800; --live: #E84393;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-light); display: flex; }
        
        /* Sidebar */
        .sidebar { width: 250px; background-color: var(--bg-light-dark); height: 100vh; padding: 20px; position: fixed; left: 0; top: 0; display: flex; flex-direction: column; border-right: 1px solid var(--border-color); z-index: 100; }
        .sidebar-header { text-align: center; margin-bottom: 30px; }
        .sidebar-header h2 { color: var(--primary-color); display: flex; align-items: center; justify-content: center; gap: 10px; }
        .sidebar-nav { flex-grow: 1; }
        .sidebar-nav a { display: flex; align-items: center; gap: 15px; color: var(--text-secondary); text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 10px; transition: all 0.3s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background-color: var(--primary-color); color: white; }
        .sidebar-nav a i { width: 20px; text-align: center; }
        .logout-link { margin-top: auto; }
        .logout-link .logout-button { background-color: rgba(214, 48, 49, 0.2); color: var(--danger); display: flex; align-items: center; gap: 15px; text-decoration: none; padding: 12px 15px; border-radius: 8px; transition: background-color 0.3s, color 0.3s; width: 100%; border: none; }
        .logout-link .logout-button:hover { background-color: var(--danger); color: white; }

        /* Main Content */
        .main-content { margin-left: 250px; width: calc(100% - 250px); padding: 30px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px; }
        .header-title-wrapper { display: flex; align-items: center; gap: 15px; }
        .header h1 { font-size: 28px; font-weight: 600; }
        .header-actions { display: flex; gap: 15px; }
        .btn { text-decoration: none; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 500; transition: all 0.3s; display: flex; align-items: center; gap: 8px; border: none; cursor: pointer; }
        .btn-create { background-color: var(--primary-color); }
        .btn-create:hover { background-color: var(--secondary-color); }
        .btn-back { background-color: var(--bg-light-dark); border: 1px solid var(--border-color); color: var(--text-secondary); }
        .btn-back:hover { background-color: var(--primary-color); border-color: var(--primary-color); color: white; }

        /* Tabs */
        .tabs { display: flex; justify-content: center; gap: 10px; border-bottom: 1px solid var(--border-color); margin-bottom: 30px; }
        .tab-link { padding: 10px 25px; cursor: pointer; border: none; background: none; font-size: 16px; font-weight: 500; color: var(--text-secondary); border-bottom: 3px solid transparent; transition: all 0.3s; }
        .tab-link.active { border-bottom-color: var(--primary-color); color: var(--text-light); }
        .tab-content { display: none; animation: fadeIn 0.5s; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* Match Cards */
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
        
        /* Form Styles */
        .form-section { background-color: var(--bg-light-dark); border-radius: 10px; padding: 25px; margin-bottom: 25px; border: 1px solid var(--border-color); }
        .form-section h2 { font-size: 20px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--border-color); color: var(--secondary-color); display: flex; align-items: center; gap: 10px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-secondary); }
        input[type="text"], input[type="number"], input[type="datetime-local"], select { width: 100%; padding: 12px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-light); font-size: 1em; }
        input:focus, select:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.2); }
        .grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .prize-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .alert-error { background-color: rgba(214, 48, 49, 0.2); color: var(--danger); padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid var(--danger); }

        @media (max-width: 992px) { .grid-layout, .prize-grid { grid-template-columns: 1fr; } }
        @media (max-width: 768px) {
            body { flex-direction: column; } .sidebar { width: 100%; height: auto; position: static; }
            .main-content { margin-left: 0; width: 100%; }
            .header { flex-direction: column; align-items: flex-start; gap: 15px; }
        }
    </style>
</head>
<body>
    @include('admin.partials.sidebar')
    <div class="main-content">
        @yield('content')
    </div>
    @yield('scripts')
</body>
</html>