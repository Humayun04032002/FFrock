<aside class="sidebar">
    <div>
        <div class="sidebar-header">
            {{-- আমরা এই সাইটের নাম কন্ট্রোলার থেকে পাঠাবো --}}
            <h2><i class="fas fa-shield-halved"></i> {{ $site_name ?? 'Admin Panel' }}</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fas fa-users"></i> Users</a>
            {{-- অন্যান্য লিংকগুলো পরে যোগ করা হবে --}}
            <a href="#"><i class="fas fa-wallet"></i> Add Money</a>
            <a href="#"><i class="fas fa-hand-holding-dollar"></i> Withdraw</a>
            <a href="#"><i class="fas fa-images"></i> Sliders</a>
            <a href="#"><i class="fas fa-dice"></i> Ludo Matches</a>
            <a href="#"><i class="fas fa-crosshairs"></i> FF Matches</a>
            <a href="#"><i class="fas fa-scroll"></i> Rules</a>
            <a href="#"><i class="fas fa-cog"></i> App Settings</a>
        </nav>
    </div>
    <div class="logout-link">
         <form action="{{ route('admin.logout') }}" method="post">
            @csrf
            <button type="submit" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</button>
         </form>
    </div>
    {{-- সাইডবারের জন্য একটি বেসিক স্টাইল --}}
    <style>.logout-button { background: none; border: none; color: inherit; font: inherit; cursor: pointer; padding: 0; width: 100%; text-align: left; }</style>
</aside>