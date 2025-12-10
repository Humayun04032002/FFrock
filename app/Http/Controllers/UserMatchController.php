<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Joining;

class UserMatchController extends Controller
{
    public function index()
    {
        $userNumber = Auth::user()->Number;

        // আপনার SQL কোয়েরিটি Laravel Query Builder দিয়ে লেখা হয়েছে
        // এটি UNION ALL এবং JOIN উভয়ই সমর্থন করে।

        $freefireMatches = DB::table('aerox_freefire')
            ->select('id', 'Match_Key', 'Match_Title', 'Match_Time', 'Entry_Fee', 'Entry_Type')
            ->addSelect(DB::raw("'freefire' as game_type")); // গেমের ধরন সনাক্ত করতে

        $allMatches = DB::table('aerox_ludo')
            ->select('id', 'Match_Key', 'Match_Title', 'Match_Time', 'Entry_Fee', 'Entry_Type')
            ->addSelect(DB::raw("'ludo' as game_type")) // গেমের ধরন সনাক্ত করতে
            ->unionAll($freefireMatches);

        $playedMatches = DB::table('aerox_joining as j')
            ->joinSub($allMatches, 'm', function ($join) {
                $join->on('j.Match_Key', '=', 'm.Match_Key');
            })
            ->where('j.Number', $userNumber)
            ->select('m.*', 'j.winnings', 'j.position')
            ->orderBy('m.Match_Time', 'desc')
            ->get();

        return view('my-matches', [
            'playedMatches' => $playedMatches,
        ]);
    }
}