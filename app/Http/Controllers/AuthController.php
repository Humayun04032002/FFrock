<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User; // আমাদের User মডেল

class AuthController extends Controller
{
    /**
     * লগইন এবং রেজিস্ট্রেশন ফর্ম দেখানোর জন্য।
     */
    public function showLoginForm()
    {
        return view('auth.login'); // আমরা resources/views/auth/login.blade.php ফাইল তৈরি করব
    }

    /**
     * নতুন ইউজার রেজিস্ট্রেশন হ্যান্ডেল করার জন্য।
     */
    public function register(Request $request)
    {
        // ডেটা ভ্যালিডেশন
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:aerox_users,email', // <-- Added email
            'number' => 'required|string|size:11|regex:/^01[3-9]\d{8}$/|unique:aerox_users,Number',
            'password' => 'required|string|min:8',
        ], [
            'number.unique' => 'This number is already registered.',
            'number.size' => 'The mobile number must be 11 digits.',
            'number.regex' => 'Invalid mobile number format.',
            'password.min' => 'Password must be at least 8 characters.',
            'email.unique' => 'This email is already registered.', // <-- Email error message
            'email.email' => 'Please provide a valid email address.', // <-- Email format error
        ]);

        if ($validator->fails()) {
            return redirect('/login')
                        ->withErrors($validator, 'register') // 'register' নামে error bag তৈরি করছি
                        ->withInput();
        }

        // ইউজার তৈরি করা
        $user = User::create([
            'Name' => $request->name,
            'email' => $request->email, // <-- Save email
            'Number' => $request->number,
            'Password' => Hash::make($request->password), // পাসওয়ার্ড হ্যাশ করা হচ্ছে
            // ডিফল্ট মানগুলো আপনার ডাটাবেস স্কিমা অনুযায়ী অটোমেটিক সেট হবে
        ]);

        // রেজিস্ট্রেশনের পর অটোমেটিক লগইন
        Auth::login($user);

        // হোম পেজে রিডাইরেক্ট করা
        return redirect()->route('home');
    }

    /**
     * ইউজার লগইন হ্যান্ডেল করার জন্য।
     */
    public function login(Request $request)
    {
        // ডেটা ভ্যালিডেশন
        $validator = Validator::make($request->all(), [
            'number' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('/login')
                        ->withErrors($validator, 'login') // 'login' নামে error bag তৈরি করছি
                        ->withInput();
        }

        // ব্যবহারকারীকে খোঁজা হচ্ছে
        $user = User::where('Number', $request->number)->first();

        // ইউজার আছে কিনা এবং পাসওয়ার্ড সঠিক কিনা তা যাচাই করা
        if (!$user || !Hash::check($request->password, $user->Password)) {
            return redirect('/login')
                ->withErrors(['credentials' => 'No account found with this number or invalid password.'], 'login')
                ->withInput();
        }

        // ইউজার ব্যান কিনা তা যাচাই করা
        if ($user->UsersBan === 'True') {
            return redirect('/login')
                ->withErrors(['banned' => 'Your account has been banned.'], 'login')
                ->withInput();
        }

        // ইউজারকে লগইন করানো
        Auth::login($user);

        // সেশন রিজেনারেট করা (নিরাপত্তার জন্য)
        $request->session()->regenerate();

        // হোম পেজে রিডাইরেক্ট করা
        return redirect()->intended('home');
    }

    /**
     * ইউজারকে লগআউট করার জন্য।
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
