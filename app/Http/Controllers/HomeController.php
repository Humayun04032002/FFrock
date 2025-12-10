<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSetting;
use App\Models\Notice;
use App\Models\Slider;
use App\Models\FreefireMatch;
use App\Models\LudoMatch;
use Illuminate\Support\Facades\DB; // DB facade ব্যবহার করার জন্য

class HomeController extends Controller
{
    public function index()
    {
        // অ্যাডমিন সেটিংস (সাপোর্ট লিংক)
        $adminSettings = AdminSetting::find(1);
        $supportLink = $adminSettings ? $adminSettings->Support : '#';

        // নোটিশ
        $notice = Notice::find(1);
        $noticeText = $notice ? $notice->Notice : 'Welcome to Khelo Bangladesh!';

        // স্লাইডার
        $sliders = Slider::orderBy('id', 'desc')->get();

        // ম্যাচের সংখ্যা গণনা
        // Laravel-এর query builder ব্যবহার করে আমরা count করছি, যা অনেক কার্যকর
        $gameCounts = [
            'br_match' => FreefireMatch::where('Match_Type', 'BR MATCH')->where('Position', 'Match')->count(),
            'clash_squad' => FreefireMatch::where('Match_Type', 'Clash Squad')->where('Position', 'Match')->count(),
            'cs_2v2' => FreefireMatch::where('Match_Type', 'CS 2 VS 2')->where('Position', 'Match')->count(),
            'lone_wolf' => FreefireMatch::where('Match_Type', 'LONE WOLF')->where('Position', 'Match')->count(),
            'ludo' => LudoMatch::where('Match_Type', 'Ludo')->where('Position', 'Match')->count(),
            'free_match' => FreefireMatch::where('Match_Type', 'FREE MATCH')->where('Position', 'Match')->count(),
        ];
        
        // ভিউতে ডেটা পাঠানো হচ্ছে
        return view('home', [
            'supportLink' => $supportLink,
            'noticeText' => $noticeText,
            'sliders' => $sliders,
            'gameCounts' => $gameCounts,
        ]);
    }
}