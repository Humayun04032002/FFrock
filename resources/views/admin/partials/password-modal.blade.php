{{-- পাসওয়ার্ড পরিবর্তনের জন্য মডাল --}}
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Change Password</h2>
            <span class="close-btn" onclick="document.getElementById('passwordModal').classList.remove('is-visible');">×</span>
        </div>
        <div class="modal-body">
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any() && session('from_password_change'))
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.password.change') }}" method="post">
                @csrf
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" required>
                </div>
                <button type="submit" class="btn-action" style="width: 100%; background-color: var(--primary-color); color: white; justify-content: center; font-weight: 600;">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>