<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FirebaseController extends Controller
{
    /**
     * ব্যবহারকারীর FCM টোকেন সংরক্ষণ বা আপডেট করে।
     */
    public function storeToken(Request $request)
    {
        // রিকোয়েস্ট থেকে টোকেনটি ভ্যালিডেট করা হচ্ছে
        $request->validate([
            'token' => 'required|string',
        ]);

        // বর্তমানে লগইন করা ব্যবহারকারীকে পাওয়া যাচ্ছে
        $user = Auth::user();

        // যদি ব্যবহারকারী লগইন করা থাকে
        if ($user) {
            try {
                // ব্যবহারকারীর 'FCM_Token' কলামটি আপডেট করা হচ্ছে
                // আপনার টেবিল অনুযায়ী কলামের নাম 'FCM_Token'
                $user->FCM_Token = $request->token;
                $user->save();

                // সফল হলে একটি JSON রেসপন্স পাঠানো হচ্ছে
                return response()->json(['message' => 'Token stored successfully for user: ' . $user->Number]);

            } catch (\Exception $e) {
                // কোনো ডেটাবেস এরর হলে লগ করা হবে এবং এরর রেসপন্স পাঠানো হবে
                \Log::error('FCM Token Store Error: ' . $e->getMessage());
                return response()->json(['message' => 'Could not store token due to a database error.'], 500);
            }
        }

        // যদি কোনো কারণে ব্যবহারকারী লগইন করা না থাকে
        return response()->json(['message' => 'User not authenticated.'], 403);
    }
}