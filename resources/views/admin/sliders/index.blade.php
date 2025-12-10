<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Manage Sliders</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #8A2BE2; --secondary-color: #4A00E0; --bg-dark: #10101A;
            --bg-light-dark: #1D1D2C; --text-light: #F5F6FA; --text-secondary: #A9A9D4;
            --border-color: rgba(255, 255, 255, 0.1); --success: #00B894; --danger: #D63031;
            --shadow-color: rgba(0, 0, 0, 0.25);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif; background: var(--bg-dark); color: var(--text-light);
            display: flex; justify-content: center; align-items: flex-start;
            min-height: 100vh; padding: 40px 20px;
        }
        .container { width: 100%; max-width: 900px; text-align: center; }
        .page-header { margin-bottom: 50px; }
        .page-header h1 { font-size: 2.5rem; font-weight: 800; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .form-section { background-color: var(--bg-light-dark); border-radius: 15px; padding: 30px; margin-bottom: 50px; border: 1px solid var(--border-color); box-shadow: 0 10px 30px var(--shadow-color); display: flex; flex-direction: column; align-items: center; gap: 20px; }
        .form-section h2 { font-size: 1.5rem; color: var(--text-light); margin-bottom: 10px; }
        .form-group { width: 100%; max-width: 500px; text-align: left; }
        .form-group input[type="url"] { width: 100%; padding: 15px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-light); font-size: 1rem; transition: all 0.3s ease; }
        .form-group input[type="url"]:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(138, 43, 226, 0.2); }
        .btn-add { padding: 15px 40px; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); border: none; border-radius: 10px; color: white; font-size: 1.1rem; cursor: pointer; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(138, 43, 226, 0.3); }
        .btn-add:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(138, 43, 226, 0.4); }
        .slider-list-header { font-size: 1.8rem; font-weight: 700; color: var(--text-light); margin-bottom: 30px; }
        .slider-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px; }
        .slider-item { position: relative; border-radius: 15px; overflow: hidden; border: 1px solid var(--border-color); transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .slider-item:hover { transform: translateY(-8px) scale(1.03); box-shadow: 0 15px 35px var(--shadow-color); }
        .slider-item::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 50%); opacity: 0; transition: opacity 0.3s ease; }
        .slider-item:hover::before { opacity: 1; }
        .slider-item img { width: 100%; height: 160px; object-fit: cover; display: block; }
        .delete-form { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .delete-btn { background-color: var(--danger); color: white; border: none; border-radius: 50%; width: 45px; height: 45px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; opacity: 0; transition: all 0.3s ease; box-shadow: 0 5px 10px rgba(0,0,0,0.3); transform: scale(0.8); }
        .slider-item:hover .delete-btn { opacity: 1; transform: scale(1); }
        .no-data { color: var(--text-secondary); padding: 50px; background-color: var(--bg-light-dark); border-radius: 15px; font-size: 1.1rem; }
    </style>
</head>
<body>
    <div class="container">
        <header class="page-header">
            <h1>Manage Sliders</h1>
        </header>

        <main>
            <section class="form-section">
                <h2><i class="fas fa-plus-circle"></i> Add a New Slider</h2>
                <form action="{{ route('admin.sliders.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="url" name="img_url" placeholder="Paste a valid image URL here..." required>
                    </div>
                    <button type="submit" class="btn-add">Add Slider</button>
                </form>
            </section>

            <section>
                <h2 class="slider-list-header">Current Sliders</h2>
                <div class="slider-grid">
                    @forelse ($sliders as $slider)
                        <div class="slider-item">
                            <img src="{{ $slider->img }}" alt="Slider Image">
                            <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this slider?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn" title="Delete Slider">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="no-data">No sliders have been added yet.</p>
                    @endforelse
                </div>
            </section>
        </main>
    </div>
</body>
</html>