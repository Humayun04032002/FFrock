<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\FreefireMatch;
use App\Models\LudoMatch;
use App\Models\Joining;
use App\Models\Rule;
use App\Models\User;
use Carbon\Carbon;


class MatchController extends Controller
{
    /**
     * ম্যাচের তালিকা দেখানোর জন্য।
     */
    public function index($type)
    {
        $userNumber = Auth::user()->Number;

        $joinedMatchKeys = Joining::where('Number', $userNumber)
                                  ->pluck('Match_Key')
                                  ->all();

        $model = FreefireMatch::class;
        $pageTitle = 'Matches';
        $matchTypeCondition = 'BR MATCH';

        switch ($type) {
            case 'br_match':
                $matchTypeCondition = 'BR MATCH';
                $pageTitle = 'BR Matches';
                break;
            case 'clash_squad':
                $matchTypeCondition = 'Clash Squad';
                $pageTitle = 'Clash Squad';
                break;
            case 'cs_2v2':
                $matchTypeCondition = 'CS 2 VS 2';
                $pageTitle = 'CS 2 VS 2';
                break;
            case 'lone_wolf':
                $matchTypeCondition = 'LONE WOLF';
                $pageTitle = 'Lone Wolf';
                break;
            case 'ludo':
                $model = LudoMatch::class;
                $matchTypeCondition = 'Ludo';
                $pageTitle = 'Ludo Matches';
                break;
            case 'free_match':
                $matchTypeCondition = 'FREE MATCH';
                $pageTitle = 'Free Matches';
                break;
            default:
                return redirect()->route('matches.list', ['type' => 'br_match']);
        }

        $matches = $model::where('Match_Type', $matchTypeCondition)
                         ->whereIn('Position', ['OnGoing', 'Match'])
                         ->orderByRaw("FIELD(Position, 'OnGoing', 'Match')")
                         ->orderBy('id', 'desc')
                         ->get();

        return view('matches', [
            'pageTitle' => $pageTitle,
            'matches' => $matches,
            'joinedMatchKeys' => $joinedMatchKeys,
            'type' => $type,
        ]);
    }

    /**
     * একটি নির্দিষ্ট ম্যাচের বিস্তারিত পেজ।
     */
    public function showDetails($game, $id)
    {
        $model = ($game === 'ludo') ? LudoMatch::class : FreefireMatch::class;
        $match = $model::find($id);

        if (!$match) {
            abort(404, 'Match not found!');
        }

        $user = Auth::user();

        $isJoined = Joining::where('Match_Key', $match->Match_Key)
                           ->where('Number', $user->Number)
                           ->exists();

        $rules = Rule::where('match_category', $match->Match_Type)->first();
        $rulesText = $rules ? $rules->rules_text : "Standard tournament rules apply. No cheating or hacking is allowed.";

        return view('match-details', [
            'match' => $match,
            'user' => $user,
            'isJoined' => $isJoined,
            'rulesText' => $rulesText,
            'gameType' => $game,
        ]);
    }

    /**
     * ম্যাচে জয়েন করার প্রক্রিয়া।
     */
    public function joinMatch(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'ingame_name_1' => 'required|string|max:100',
            'ingame_name_2' => 'nullable|string|max:100',
            'ingame_name_3' => 'nullable|string|max:100',
            'ingame_name_4' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Please enter at least your in-game name.')->withInput();
        }

        $user = Auth::user();
        $model = ($request->game_type === 'ludo') ? LudoMatch::class : FreefireMatch::class;
        $match = $model::findOrFail($id);
        $matchTime = Carbon::parse($match->Match_Time);

if (now()->greaterThanOrEqualTo($matchTime)) {
    return back()->with('error', 'Match already started! You cannot join now.');
}

        if (Joining::where('Match_Key', $match->Match_Key)->where('Number', $user->Number)->exists()) {
            return back()->with('error', 'You have already joined this match.');
        }

        if ($match->Player_Join >= $match->Player_Need) {
            return back()->with('error', 'Sorry, the match is full.');
        }

        if ((float)$user->Balance < (float)$match->Entry_Fee) {
            return back()->with('error', 'Insufficient balance to join.');
        }

        $teamPlayers = collect($request->only(['ingame_name_1', 'ingame_name_2', 'ingame_name_3', 'ingame_name_4']))
                        ->filter()
                        ->values()
                        ->all();

        try {
            DB::transaction(function () use ($user, $match, $request, $teamPlayers) {
                $user->Balance -= (float)$match->Entry_Fee;
                $user->Total_Played += 1;
                $user->save();

                Joining::create([
                    'Match_Key' => $match->Match_Key,
                    'Name' => $user->Name,
                    'Number' => $user->Number,
                    'ingame_name' => $request->ingame_name_1,
                    'team_info' => json_encode($teamPlayers),
                ]);

                $match->Player_Join += 1;
                $match->save();
            });
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred. Please try again.');
        }

        return redirect()->route('match.details', ['game' => $request->game_type, 'id' => $id])->with('success', 'Joined Successfully!');
    }

    /**
     * জয়নার লিস্ট দেখানোর জন্য।
     */
    public function showJoiners($matchId)
    {
        // Match can be Freefire or Ludo
        $match = FreefireMatch::find($matchId) ?? LudoMatch::find($matchId);

        if (!$match) {
            abort(404, 'Match not found');
        }

        // Joined players
        $joiners = Joining::where('Match_Key', $match->Match_Key)->get(['ingame_name']);

        // Load rules by match_category
        $rules = Rule::where('match_category', $match->Match_Type)->first();
        $rulesText = $rules ? $rules->rules_text : "Standard tournament rules apply.";

        // Send rules to view
        return view('user.match-joiners', compact('match', 'joiners', 'rulesText'));
    }

    /**
     * ---------- MATCH RULES PAGE ----------
     */
    public function rules($id)
    {
        $match = FreefireMatch::find($id) ?? LudoMatch::find($id);

        if (!$match) {
            abort(404, 'Match not found');
        }

        $rules = Rule::where('match_category', $match->Match_Type)->first();
        $rulesText = $rules ? $rules->rules_text : "No rules found for this match.";

        return view('match-rules', compact('match', 'rulesText'));
    }
}
