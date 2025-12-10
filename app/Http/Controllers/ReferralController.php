<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    /**
     * "Refer & Earn" পেজ দেখানোর জন্য।
     */
    public function index()
    {
        // লগইন করা ব্যবহারকারীর নম্বর থেকে রেফারেল কোড তৈরি করা
        $userNumber = Auth::user()->Number;
        $referralCode = 'OXFF' . substr($userNumber, -6);

        // ভিউতে রেফারেল কোড পাঠানো
        return view('referral', [
            'referralCode' => $referralCode,
        ]);
    }
}