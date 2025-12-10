<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminSetting;
use App\Models\Notice;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    // সেটিংস পেজ দেখানোর জন্য
    public function index()
    {
        // firstOrFail() ব্যবহার করে নিশ্চিত করা হচ্ছে যে সেটিংস এবং নোটিশ আছে
        $settings = AdminSetting::firstOrFail();
        $notice = Notice::firstOrFail();

        return view('admin.settings.index', compact('settings', 'notice'));
    }

    // সকল সেটিংস আপডেট করার জন্য
    public function update(Request $request)
    {
        $validated = $request->validate([
            'bkash' => 'nullable|string',
            'nagad' => 'nullable|string',
            'rocket' => 'nullable|string',
            'min_deposit' => 'required|numeric',
            'min_withdraw' => 'required|numeric',
            'support_link' => 'nullable|url',
            'app_link' => 'nullable|url',
            'home_notice' => 'required|string',
            'splash_title' => 'required|string',
            'splash_logo_url' => 'nullable|url',
            'payment_mode' => 'required|string|in:Auto,Manual',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // aerox_admin টেবিল আপডেট করা
                $admin_settings = AdminSetting::firstOrFail();
                $admin_settings->update([
                    'bKash Number' => $validated['bkash'],
                    'Nagad Number' => $validated['nagad'],
                    'Rocket Number' => $validated['rocket'],
                    'Minimum Deposit' => $validated['min_deposit'],
                    'Minimum Withdraw' => $validated['min_withdraw'],
                    'Support' => $validated['support_link'],
                    'APP LINK' => $validated['app_link'],
                    'Splash Title' => $validated['splash_title'],
                    'Splash Logo URL' => $validated['splash_logo_url'],
                    'Payment_Mode' => $validated['payment_mode'],
                ]);

                // aerox_notice টেবিল আপডেট করা
                $notice = Notice::firstOrFail();
                $notice->update(['Notice' => $validated['home_notice']]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating settings: ' . $e->getMessage());
        }

        return back()->with('success', 'All settings have been updated successfully!');
    }
}