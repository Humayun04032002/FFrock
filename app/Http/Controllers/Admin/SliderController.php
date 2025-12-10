<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;

class SliderController extends Controller
{
    // সকল স্লাইডার দেখানোর জন্য
    public function index()
    {
        $sliders = Slider::orderBy('id', 'desc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    // নতুন স্লাইডার যোগ করার জন্য
    public function store(Request $request)
    {
        $validated = $request->validate([
            'img_url' => 'required|url',
        ]);

        Slider::create([
            'img' => $validated['img_url'],
        ]);

        return back()->with('success', 'Slider added successfully!');
    }

    // স্লাইডার ডিলিট করার জন্য
    public function destroy(Slider $slider)
    {
        $slider->delete();
        return back()->with('success', 'Slider deleted successfully!');
    }
}