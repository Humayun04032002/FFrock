<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    /**
     * লিডারবোর্ড পেজ দেখানোর জন্য।
     */
    public function index()
    {
        // আপনার SQL কোয়েরিটি Eloquent দিয়ে লেখা হয়েছে
        // orderByRaw ব্যবহার করে আমরা CAST ফাংশনটি ব্যবহার করছি
        $topWinners = User::select('Name', 'Winning')
                            ->where(DB::raw('CAST(Winning AS UNSIGNED)'), '>', 0)
                            ->orderByRaw('CAST(Winning AS UNSIGNED) DESC')
                            ->limit(10)
                            ->get();

        return view('leaderboard', [
            'topWinners' => $topWinners,
        ]);
    }
}