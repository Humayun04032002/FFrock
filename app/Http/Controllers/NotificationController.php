<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\SendsFirebaseNotifications; // আমাদের নতুন এবং কার্যকরী Trait

class NotificationController extends Controller
{
    use SendsFirebaseNotifications;

    /**
     * নোটিফিকেশন পাঠানোর ফর্ম পেজটি দেখানোর জন্য।
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        // এই ভিউ ফাইলটি আপনার নোটিফিকেশন পাঠানোর ফর্ম দেখায়
        return view('admin.notifications.send');
    }

    /**
     * ফর্ম থেকে প্রাপ্ত ডেটা দিয়ে অ্যাপ এবং ওয়েব উভয় প্ল্যাটফর্মে নোটিফিকেশন পাঠায়।
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendNotification(Request $request)
    {
        // ফর্ম থেকে আসা ডেটা ভ্যালিডেট করা হচ্ছে
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        // Trait থেকে মেথডটি কল করে নোটিফিকেশন পাঠানো হচ্ছে
        $this->sendNotifications($request->title, $request->body, $request->image_url);

        return redirect()->back()->with('success', 'Notification broadcast has been initiated for all platforms.');
    }
}