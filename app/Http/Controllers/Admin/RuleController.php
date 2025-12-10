<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rule;
use App\Models\AdminSetting; // সাইডবারের জন্য

class RuleController extends Controller
{
    /**
     * Rules ম্যানেজমেন্ট পেজ দেখানোর জন্য।
     */
    public function index()
    {
        $defined_categories = ['BR MATCH', 'Clash Squad', 'CS 2 VS 2', 'LONE WOLF', 'Ludo', 'FREE MATCH'];
        
        // ডেটাবেস থেকে সকল নিয়মাবলী একটি associative array তে আনা হচ্ছে
        $rules_from_db = Rule::pluck('rules_text', 'match_category');
        
        // সাইডবারের জন্য সাইটের নাম
        $site_name = AdminSetting::first()->{'Splash Title'} ?? 'Admin Panel';

        return view('admin.rules.index', compact('defined_categories', 'rules_from_db', 'site_name'));
    }

    /**
     * সকল নিয়মাবলী আপডেট করার জন্য।
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'rules' => 'required|array',
            'rules.*' => 'nullable|string', // প্রতিটি নিয়ম স্ট্রিং হতে হবে
        ]);

        foreach ($validated['rules'] as $category => $text) {
            // updateOrCreate মেথড ব্যবহার করে কোড সহজ করা হয়েছে
            // যদি ক্যাটাগরি থাকে, তাহলে আপডেট করবে; না থাকলে নতুন তৈরি করবে।
            Rule::updateOrCreate(
                ['match_category' => $category],
                ['rules_text' => $text ?? ''] // যদি টেক্সট null হয়, তাহলে খালি স্ট্রিং সেভ হবে
            );
        }

        return back()->with('success', 'All rules have been updated successfully!');
    }
}