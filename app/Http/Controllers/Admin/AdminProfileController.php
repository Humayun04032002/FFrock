<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * অ্যাডমিনের পাসওয়ার্ড পরিবর্তন করে এবং সকল ডিভাইস থেকে লগআউট করে দেয়।
     */
    public function changePassword(Request $request)
    {
        // --- ধাপ ১: ইনপুট ভ্যালিডেশন ---
        // নিশ্চিত করা হচ্ছে যে বর্তমান পাসওয়ার্ড সঠিক এবং নতুন পাসওয়ার্ডটি শক্তিশালী।
        $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // --- ধাপ ২: নতুন পাসওয়ার্ড ডাটাবেসে আপডেট করা ---
        // বর্তমানে লগইন করা অ্যাডমিনকে খুঁজে বের করা হচ্ছে
        $admin = Auth::guard('admin')->user();
        
        // নতুন পাসওয়ার্ড হ্যাশ করে ডাটাবেসে সেভ করা হচ্ছে
        $admin->forceFill([
            'password' => Hash::make($request->new_password),
        ])->save();

        // --- ধাপ ৩: অন্য সকল ডিভাইস থেকে অ্যাডমিনকে লগআউট করা ---
        // 'admin' গার্ড ব্যবহার করে এই কাজটি করা হচ্ছে।
        // এটি বর্তমান সেশন ছাড়া বাকি সব সেশনকে ইনভ্যালিডেট করে দেবে।
        Auth::guard('admin')->logoutOtherDevices($request->new_password);

        // --- ধাপ ৪: বর্তমান ডিভাইস থেকেও লগআউট করা ---
        // পাসওয়ার্ড পরিবর্তনের পর বর্তমান সেশনটিও লগআউট করে দেওয়া একটি ভালো অভ্যাস।
        Auth::guard('admin')->logout();

        // সেশনটি ইনভ্যালিডেট করা হচ্ছে এবং টোকেন রিজেনারেট করা হচ্ছে
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // --- ধাপ ৫: সফল বার্তা সহ লগইন পেজে রিডাইরেক্ট করা ---
        return redirect()->route('admin.login')
                         ->with('status_success', 'Password changed successfully! Please log in again with your new password.');
    }
}