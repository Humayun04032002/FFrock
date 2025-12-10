<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminSetting;

class UserController extends Controller
{
    /**
     * সকল ইউজারের তালিকা দেখানোর জন্য।
     */
    public function index(Request $request)
{
    // Get search keyword if provided
    $search = $request->get('search');

    // Base query
    $query = User::orderBy('id', 'desc');

    // Apply search filters
    if ($search) {
        $query->where('id', $search)
              ->orWhere('Name', 'like', "%{$search}%")
              ->orWhere('Number', 'like', "%{$search}%");
    }

    // Paginate results
    $users = $query->paginate(100);

    // Site name
    $site_name = AdminSetting::first()->{'Splash Title'} ?? 'Admin Panel';

    // Return to view
    return view('admin.users.index', compact('users', 'site_name'));
}


    /**
     * নির্দিষ্ট ইউজারকে এডিট করার ফর্ম দেখানোর জন্য।
     */
    public function edit(User $user) // Route Model Binding ব্যবহার করা হয়েছে
    {
        $site_name = AdminSetting::first()->{'Splash Title'} ?? 'Admin Panel';
        return view('admin.users.edit', compact('user', 'site_name'));
    }

    /**
     * ইউজারের তথ্য আপডেট করার জন্য।
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric',
            'winning' => 'required|numeric',
            'ban_status' => 'required|string|in:True,False',
        ]);

        $user->Name = $request->name;
        $user->Balance = $request->balance;
        $user->Winning = $request->winning;
        $user->UsersBan = $request->ban_status;
        $user->save();

        return redirect()->route('admin.users.edit', $user->id)
                         ->with('success', 'User details updated successfully!');
    }
}