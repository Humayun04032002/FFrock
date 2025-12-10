<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User: {{ $user->Name }}</title>
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
        .btn-back { 
            text-decoration: none; color: var(--text-secondary); background-color: var(--bg-light-dark); 
            border: 1px solid var(--border-color); padding: 8px 15px; border-radius: 8px; 
            transition: all 0.3s; display: flex; align-items:center; gap: 8px; 
        }
        .btn-back:hover { background-color: var(--primary-color); color: white; }
        
        .form-section { background-color: var(--bg-light-dark); border-radius: 10px; padding: 25px; margin-bottom: 25px; border: 1px solid var(--border-color); }
        .form-section h2 { font-size: 20px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--border-color); color: var(--secondary-color); display: flex; align-items:center; gap: 10px;}
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-secondary); }
        input[type="text"], input[type="number"] { width: 100%; padding: 12px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-light); font-size: 1em; }
        input:disabled { background-color: #2a2a45; cursor: not-allowed; }
        .grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .radio-group { display: flex; gap: 20px; margin-top: 10px; }
        .radio-group label { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px 15px; border-radius: 8px; border: 1px solid var(--border-color); transition: all 0.3s; }
        .radio-group input[type="radio"] { display: none; }
        .radio-group input[type="radio"]:checked + span { color: white; }
        .radio-group input[type="radio"][value="True"] + span i { color: var(--danger); }
        .radio-group input[type="radio"][value="False"] + span i { color: var(--success); }
        .radio-group label:hover { background-color: rgba(255,255,255,0.05); }

        .btn-save { 
            width: 100%; padding: 12px; background-color: var(--success); border: none; 
            border-radius: 8px; color: white; font-size: 16px; cursor: pointer; 
            display: flex; align-items: center; justify-content: center; gap: 10px; font-weight: 600; 
        }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align:center; border: 1px solid transparent; }
        .alert-success { background-color: rgba(0, 184, 148, 0.2); color: var(--success); }
        .alert-error { background-color: rgba(214, 48, 49, 0.2); color: var(--danger); }
    </style>
</head>
<body>
    <div class="main-content">
        <header class="header">
            <h1><i class="fas fa-user-edit"></i> Edit User</h1>
            <a href="{{ route('admin.users.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Users</a>
        </header>

        <main>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.users.update', $user->id) }}" method="post">
                @csrf
                @method('PUT')
                
                <div class="form-section">
                    <h2><i class="fas fa-user-circle"></i> Personal Information</h2>
                    <div class="grid-layout">
                        <div class="form-group"><label>Name</label><input type="text" name="name" value="{{ old('name', $user->Name) }}"></div>
                        <div class="form-group"><label>Number</label><input type="text" value="{{ $user->Number }}" disabled></div>
                    </div>
                </div>

                <div class="form-section">
                    <h2><i class="fas fa-wallet"></i> Balance Management</h2>
                    <div class="grid-layout">
                        <div class="form-group"><label>Main Balance</label><input type="number" step="0.01" name="balance" value="{{ old('balance', $user->Balance) }}"></div>
                        <div class="form-group"><label>Winning</label><input type="number" step="0.01" name="winning" value="{{ old('winning', $user->Winning) }}"></div>
                    </div>
                </div>

                <div class="form-section">
                    <h2><i class="fas fa-user-shield"></i> User Status</h2>
                    <div class="form-group">
                        <label>Select user account status</label>
                        <div class="radio-group">
                            <label><input type="radio" name="ban_status" value="False" @checked(old('ban_status', $user->UsersBan) == 'False')><span><i class="fas fa-check-circle"></i> Active</span></label>
                            <label><input type="radio" name="ban_status" value="True" @checked(old('ban_status', $user->UsersBan) == 'True')><span><i class="fas fa-ban"></i> Banned</span></label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-save"><i class="fas fa-save"></i> Save Changes</button>
            </form>
        </main>
    </div>
</body>
</html>