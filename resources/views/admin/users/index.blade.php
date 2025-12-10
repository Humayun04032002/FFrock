<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6C5CE7; --secondary-color: #A29BFE; --bg-dark: #0F0F1A;
            --bg-light-dark: #1D1D30; --text-light: #F5F6FA; --text-secondary: #A29BFE;
            --border-color: rgba(255, 255, 255, 0.1); --success: #00B894; --danger: #D63031; --warning: #FF9800;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-light); }

        .main-content { width: 100%; max-width: 1200px; margin: 0 auto; padding: 30px; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-title-wrapper { display: flex; align-items: center; gap: 12px; }
        .header h1 { font-size: 28px; font-weight: 600; }

        .btn-back {
            text-decoration: none; color: var(--text-secondary); background-color: var(--bg-light-dark);
            border: 1px solid var(--border-color); padding: 8px 15px; border-radius: 8px;
            font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px; transition: all 0.3s;
        }
        .btn-back:hover { background-color: var(--primary-color); color: white; border-color: var(--primary-color); }

        /* Search box */
        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .search-box input {
            flex: 1;
            background-color: var(--bg-light-dark);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 15px;
            color: var(--text-light);
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }
        .search-box input:focus { border-color: var(--primary-color); }
        .search-box button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }
        .search-box button:hover { background-color: #5849d1; }

        .table-container { background-color: var(--bg-light-dark); border-radius: 10px; padding: 20px; border: 1px solid var(--border-color); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border-color); white-space: nowrap; }
        th { color: var(--text-secondary); font-weight: 600; text-transform: uppercase; font-size: 12px; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: rgba(255, 255, 255, 0.03); }

        .status-badge { padding: 4px 10px; border-radius: 15px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .status-active { background-color: rgba(0, 184, 148, 0.2); color: var(--success); }
        .status-banned { background-color: rgba(214, 48, 49, 0.2); color: var(--danger); }

        .actions { display: flex; gap: 10px; }
        .action-btn {
            text-decoration: none; color: white; padding: 6px 12px; border-radius: 6px;
            font-size: 13px; font-weight: 500; display: flex; align-items: center;
            gap: 6px; transition: opacity 0.3s;
        }
        .action-btn:hover { opacity: 0.8; }
        .btn-edit { background-color: var(--primary-color); }
        .btn-notify { background-color: var(--warning); color:#111; }
        .no-data { text-align: center; color: var(--text-secondary); padding: 40px; }

        /* Pagination */
        .pagination-wrapper { margin-top: 20px; display: flex; justify-content: center; }
        .pagination-wrapper .pagination { display: flex; list-style: none; padding: 0; }
        .pagination-wrapper .pagination li { margin: 0 5px; }
        .pagination-wrapper .pagination li a, .pagination-wrapper .pagination li span {
            color: var(--text-secondary); background-color: var(--bg-light-dark); border: 1px solid var(--border-color);
            padding: 8px 15px; border-radius: 8px; text-decoration: none; transition: all 0.3s;
        }
        .pagination-wrapper .pagination li.active span { background-color: var(--primary-color); color: white; border-color: var(--primary-color); }
        .pagination-wrapper .pagination li.disabled span { background-color: #2a2a45; cursor: not-allowed; }
        .pagination-wrapper .pagination li a:hover { background-color: var(--primary-color); color: white; }
    </style>
</head>
<body>
    <div class="main-content">
        <header class="header">
            <div class="header-title-wrapper">
                <h1><i class="fas fa-users-cog"></i> All Users</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </header>

        <!-- 🔍 Search Section -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="search-box">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by ID, Name or Number...">
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>

        <main>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Name</th><th>Number</th><th>Main Balance</th><th>Winning Balance</th><th>Status</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->Name }}</td>
                                <td>{{ $user->Number }}</td>
                                <td>৳ {{ number_format($user->Balance, 2) }}</td>
                                <td>৳ {{ number_format($user->Winning, 2) }}</td>
                                <td>
                                    <span class="status-badge {{ $user->UsersBan == 'False' ? 'status-active' : 'status-banned' }}">
                                        {{ $user->UsersBan == 'False' ? 'Active' : 'Banned' }}
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn btn-edit"><i class="fas fa-user-edit"></i> Edit</a>
                                    <a href="#" class="action-btn btn-notify"><i class="fas fa-bell"></i> Notify</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="no-data">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
        </main>
    </div>
</body>
</html>
