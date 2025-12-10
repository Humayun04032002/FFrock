<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSetting; // আমাদের বানানো AdminSetting মডেলটি এখানে ব্যবহার করছি

class SplashController extends Controller
{
    public function show()
    {
        // aerox_admin টেবিল থেকে id=1 এর ডেটা আনা হচ্ছে
        // এটি আপনার "SELECT ... FROM aerox_admin WHERE id = 1" কোয়েরির সমতুল্য
        $settings = AdminSetting::find(1);

        // যদি ডেটাবেসে কোনো কারণে সেটিংস না পাওয়া যায়, তবে একটি ডিফল্ট মান সেট করা হবে
        if (!$settings) {
            $settings = (object) [ // অবজেক্ট হিসেবে তৈরি করছি যাতে ভিউতে ব্যবহার করতে সুবিধা হয়
                'Splash Title' => 'Khelo Bangladesh',
                'Splash Logo URL' => 'https://i.postimg.cc/659wmfBF/IMG-20250622-160711-697.jpg' // একটি ডিফল্ট লোগো
            ];
        }

        // 'splash' নামের ভিউ ফাইলটি লোড করা হচ্ছে এবং $settings ডেটা পাঠানো হচ্ছে
        return view('splash', ['settings' => $settings]);
    }
}