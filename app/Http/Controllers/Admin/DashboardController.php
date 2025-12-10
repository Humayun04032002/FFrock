<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AddMoney;
use App\Models\Withdraw;
use App\Models\AdminSetting;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // ড্যাশবোর্ডের জন্য প্রয়োজনীয় ডেটা সংগ্রহ করা
        $dashboardData = [
            'total_users' => User::count(),
            'pending_addmoney' => AddMoney::where('Status', 'Pending')->count(),
            'pending_withdraw' => Withdraw::where('Status', 'Pending')->count(),
            'site_name' => AdminSetting::first()->{'Splash Title'} ?? 'Admin Panel',
        ];

        // ভিউতে ডেটা পাঠানো হচ্ছে
        return view('admin.dashboard', $dashboardData);
    }
}