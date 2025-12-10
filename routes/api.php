<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentNotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * এই রুটটি আপনার পেমেন্ট গেটওয়ে বা অন্য কোনো অ্যাপ থেকে
 * পেমেন্ট নোটিফিকেশন গ্রহণ করার জন্য ব্যবহৃত হবে।
 * সম্পূর্ণ URL হবে: http://yourdomain.com/api/payment-notification
 */
Route::post('/payment-notification', [PaymentNotificationController::class, 'store']);


/**
 * এটি Laravel-এর ডিফল্ট API রুট।
 * এটি সাধারণত মোবাইল অ্যাপ বা ফ্রন্টএন্ড ফ্রেমওয়ার্ক থেকে
 * লগইন করা ব্যবহারকারীর তথ্য আনার জন্য ব্যবহৃত হয়।
 * আমাদের বর্তমান প্রজেক্টে এটি ব্যবহৃত হচ্ছে না, তবে রেখে দেওয়া ভালো।
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});