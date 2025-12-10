<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Manage Game Rules</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6C5CE7; --secondary-color: #A29BFE; --bg-dark: #10101A;
            --bg-light-dark: #1D1D2C; --text-light: #F5F6FA; --text-secondary: #A9A9D4;
            --border-color: rgba(255, 255, 255, 0.1); --success: #00B894;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif; background-color: var(--bg-dark);
            color: var(--text-light); display: flex; justify-content: center;
            align-items: flex-start; min-height: 100vh; padding: 30px 15px;
        }
        .container { width: 100%; max-width: 900px; }
        .header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px; padding: 0 10px;
        }
        .header h1 {
            font-size: 2rem; font-weight: 700; color: var(--text-light);
            display: flex; align-items: center; gap: 12px;
        }
        .btn-back {
            text-decoration: none; color: var(--text-secondary); background-color: var(--bg-light-dark);
            border: 1px solid var(--border-color); padding: 10px 20px; border-radius: 8px;
            font-weight: 500; display: flex; align-items: center; gap: 8px; transition: all 0.3s;
        }
        .btn-back:hover { background-color: var(--primary-color); color: white; }
        .success-msg {
            background-color: rgba(0, 184, 148, 0.2); color: var(--success); border: 1px solid var(--success);
            padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center;
            font-weight: 500;
        }
        .rules-manager {
            background-color: var(--bg-light-dark); border-radius: 15px;
            padding: 20px; border: 1px solid var(--border-color);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        .tabs { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; }
        .tab-link {
            padding: 10px 15px; cursor: pointer; border: none; background: none;
            font-size: 1rem; font-weight: 500; color: var(--text-secondary);
            border-radius: 8px; transition: all 0.3s;
        }
        .tab-link.active { background-color: var(--primary-color); color: white; }
        .tab-content { display: none; animation: fadeIn 0.5s; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .form-group { margin-bottom: 20px; }
        textarea {
            width: 100%; min-height: 250px; padding: 15px;
            background-color: var(--bg-dark); border: 1px solid var(--border-color);
            border-radius: 10px; color: var(--text-light); font-size: 1rem; resize: vertical;
        }
        textarea:focus { outline: none; border-color: var(--primary-color); }
        .form-actions { text-align: center; margin-top: 20px; }
        .btn-save {
            padding: 12px 40px; background-color: var(--success); border: none; border-radius: 8px;
            color: white; font-size: 1.1rem; cursor: pointer; font-weight: 600;
            display: inline-flex; align-items: center; gap: 10px; transition: background-color 0.3s;
        }
        .btn-save:hover { background-color: #00d2a1; }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1><i class="fas fa-scroll"></i> Manage Game Rules</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </header>
        <main>
            @if (session('success'))
                <p class="success-msg">{{ session('success') }}</p>
            @endif

            <div class="rules-manager">
                <div class="tabs">
                    @foreach ($defined_categories as $index => $category)
                        <button class="tab-link {{ $index == 0 ? 'active' : '' }}" onclick="openTab(event, '{{ str_replace(' ', '_', $category) }}')">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>
                
                <form action="{{ route('admin.rules.update') }}" method="post">
                    @csrf
                    @foreach ($defined_categories as $index => $category)
                        @php
                            $cat_id = str_replace(' ', '_', $category);
                        @endphp
                        <div id="{{ $cat_id }}" class="tab-content {{ $index == 0 ? 'active' : '' }}">
                            <div class="form-group">
                                <textarea name="rules[{{ $category }}]" placeholder="Enter rules for {{ $category }}...">{{ $rules_from_db[$category] ?? '' }}</textarea>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-actions">
                        <button type="submit" class="btn-save"><i class="fas fa-save"></i> Save All Rules</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        function openTab(evt, tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }
        // ডিফল্টভাবে প্রথম ট্যাবটি খোলার জন্য
         document.addEventListener("DOMContentLoaded", function() {
            if(document.querySelector(".tab-link.active")) {
               document.querySelector(".tab-link.active").click();
            }
        });
    </script>
</body>
</html>