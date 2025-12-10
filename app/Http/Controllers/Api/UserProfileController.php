<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // User মডেল ইমপোর্ট করা

class UserProfileController extends Controller
{
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'user_id'   => 'sometimes|nullable|integer' // user_id ঐচ্ছিক
        ]);

        try {
            // যদি ব্যবহারকারী লগইন করা থাকে (Sanctum এর মাধ্যমে)
            $user = auth('sanctum')->user();

            // যদি Sanctum auth না থাকে, তাহলে পাঠানো user_id দিয়ে খোঁজা হবে
            if (!$user && $request->filled('user_id')) {
                $user = User::find($request->input('user_id'));
            }
            
            if ($user) {
                // একই টোকেন অন্য কারো থাকলে সেটি মুছে দেওয়া (ঐচ্ছিক কিন্তু ভালো অভ্যাস)
                User::where('fcm_token', $request->input('fcm_token'))->update(['fcm_token' => null]);
                
                // নতুন টোকেন সেভ করা
                $user->fcm_token = $request->input('fcm_token');
                $user->save();
                return response()->json(['success' => true, 'message' => 'FCM token updated successfully.']);
            }

            return response()->json(['success' => false, 'message' => 'User not found or not authenticated.'], 404);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update token: ' . $e->getMessage()], 500);
        }
    }
}```
**গুরুত্বপূর্ণ পরিবর্তন:**
*   API রাউটের `middleware('auth:sanctum')` অংশটি আপাতত তুলে দিতে পারেন `routes/api.php` থেকে, যদি আপনার সাইটে API অথেন্টিকেশন সেটআপ করা না থাকে।
*   জাভাস্ক্রিপ্ট থেকে আমরা এখন `user_id` পাঠাচ্ছি, যা কন্ট্রোলার গ্রহণ করে সঠিক ব্যবহারকারীর `fcm_token` আপডেট করবে।

এই তিনটি ধাপ সম্পন্ন করার পর, আপনার ওয়েবসাইটে লগইন করা ব্যবহারকারীরা নোটিফিকেশন `Allow` করার অপশন পাবে এবং আপনার অ্যাডমিন প্যানেল থেকে পাঠানো নোটিফিকেশন তারা ব্রাউজারেও দেখতে পাবে।