<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminSetting;

class ProfileController extends Controller
{
    /**
     * প্রোফাইল পেজ দেখানোর জন্য।
     */
    public function index()
    {
        // লগইন করা ব্যবহারকারীর সকল তথ্য আনা হচ্ছে
        $user = Auth::user();

        // অ্যাডমিন সেটিংস থেকে সাপোর্ট লিংক আনা হচ্ছে
        $adminSettings = AdminSetting::find(1);
        $supportLink = $adminSettings ? $adminSettings->Support : '#';

        return view('profile', [
            'user' => $user,
            'supportLink' => $supportLink,
        ]);
    }
}