<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard - {{ $siteName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* --- ড্যাশবোর্ডের জন্য নির্দিষ্ট CSS --- */
        :root {
            --primary-color: #6C5CE7; --secondary-color: #A29BFE; --bg-dark: #0F0F1A;
            --bg-light-dark: #1D1D30; --text-light: #F5F6FA; --text-secondary: #A29BFE;
            --border-color: rgba(255, 255, 255, 0.1); --success: #00B894; --danger: #D63031;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-light); }
        .navbar { background: rgba(10, 10, 20, 0.7); backdrop-filter: blur(20px); padding: 0 20px; display: flex; justify-content: space-between; align-items: center; height: 70px; border-bottom: 1px solid var(--border-color); position: sticky; top: 0; z-index: 100; }
        .navbar-brand { font-size: 22px; font-weight: 700; color: var(--text-light); text-decoration: none; display: flex; align-items: center; gap: 12px; }
        .navbar-brand i { color: var(--primary-color); }
        .navbar-actions { display: flex; align-items: center; gap: 10px; }
        .btn-action { background-color: transparent; border: 1px solid var(--border-color); color: var(--text-secondary); padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; text-decoration: none; font-weight: 500; }
        .btn-action:hover { background-color: var(--primary-color); color: white; border-color: var(--primary-color); }
        .btn-action.logout { border-color: var(--danger); color: var(--danger); }
        .btn-action.logout:hover { background-color: var(--danger); color: white; }
        .page-wrapper { display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 70px); padding: 20px; }
        .main-container { width: 100%; max-width: 1200px; }
        .page-header { text-align: center; margin-bottom: 40px; }
        .page-header h1 { font-size: 36px; font-weight: 700; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .page-header p { font-size: 16px; color: var(--text-secondary); margin-top: 5px; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; }
        .d-card { aspect-ratio: 1 / 1; text-decoration: none; color: var(--text-light); background-color: var(--bg-light-dark); border-radius: 20px; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 15px; cursor: pointer; transition: all 0.3s ease; }
        .d-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4); }
        .icon-wrapper { width: 45px; height: 45px; border-radius: 50%; display: grid; place-items: center; margin-bottom: 10px; font-size: 22px; }
        .card-title { font-size: 15px; font-weight: 500; text-align: center; }
        .card-count { font-size: 24px; font-weight: 700; margin-top: 4px; }
        .card-subtext { font-size: 10px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; }
        /* পাসওয়ার্ড মডাল স্টাইল */
        .modal { visibility: hidden; opacity: 0; position: fixed; z-index: 1002; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; transition: all 0.3s; }
        .modal.is-visible { visibility: visible; opacity: 1; }
        .modal-content { background-color: var(--bg-light-dark); padding: 30px; border: 1px solid var(--border-color); width: 90%; max-width: 450px; border-radius: 16px; position: relative; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 15px; margin-bottom: 25px; border-bottom: 1px solid var(--border-color); }
        .modal-header h2 { margin: 0; font-size: 22px; }
        .close-btn { color: #aaa; font-size: 30px; font-weight: bold; cursor: pointer; }
        .form-group { margin-bottom: 15px; } .form-group label { display: block; margin-bottom: 8px; font-weight: 500; } .form-group input { width: 100%; padding: 12px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-light); }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; text-align:center; }
        .alert-success { background-color: rgba(0, 184, 148, 0.2); color: var(--success); }
        .alert-danger { background-color: rgba(214, 48, 49, 0.2); color: var(--danger); }
        @media (max-width: 768px) { .navbar-brand span, .btn-action span { display: none; } .dashboard-grid { grid-template-columns: repeat(2, 1fr); gap: 15px; } }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>