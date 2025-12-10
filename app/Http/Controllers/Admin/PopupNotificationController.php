<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopupNotification;
use Illuminate\Http\Request;

class PopupNotificationController extends Controller
{
    public function index()
    {
        $popup = PopupNotification::first();
        return view('admin.popup.index', compact('popup'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable|boolean'
        ]);

        $popup = PopupNotification::firstOrNew(['id' => 1]);
        $popup->fill($data);
        $popup->save();

        return back()->with('success', 'Popup Notification Updated Successfully!');
    }
}
