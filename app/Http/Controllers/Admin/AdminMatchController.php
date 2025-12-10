<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FreefireMatch;
use App\Models\LudoMatch;
use App\Models\AdminSetting;
use App\Models\Joining;
use App\Models\Result;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Traits\SendsFirebaseNotifications;

class AdminMatchController extends Controller
{
    use SendsFirebaseNotifications;

    private function getModelInstance(string $game_type)
    {
        if ($game_type === 'freefire') return new FreefireMatch();
        if ($game_type === 'ludo') return new LudoMatch();
        abort(404, 'Invalid game type specified.');
    }

    private function findMatchById(string $game_type, $id)
    {
        return $this->getModelInstance($game_type)->findOrFail($id);
    }

    private function findMatchByKey(string $match_key)
    {
        return FreefireMatch::where('Match_Key', $match_key)->first() ?? LudoMatch::where('Match_Key', $match_key)->first();
    }

    public function index(string $game_type)
    {
        $model = $this->getModelInstance($game_type);
        $all_matches = $model->orderByRaw("FIELD(Position, 'OnGoing', 'Match', 'Result')")
                             ->orderBy('Match_Time', 'desc')
                             ->get()
                             ->groupBy('Position');

        return view('admin.matches.index', [
            'all_matches' => $all_matches, 'game_type' => $game_type,
            'page_title' => ($game_type == 'freefire') ? 'FreeFire Matches' : 'Ludo Matches',
            'page_icon' => ($game_type == 'freefire') ? 'fa-crosshairs' : 'fa-dice',
            'site_name' => AdminSetting::first()->{'Splash Title'} ?? 'Admin Panel'
        ]);
    }

    public function create(string $game_type)
    {
        return view('admin.matches.create', [
            'game_type' => $game_type,
            'page_title' => ($game_type == 'freefire') ? 'Add FreeFire Match' : 'Add Ludo Match',
            'page_icon' => ($game_type == 'freefire') ? 'fa-crosshairs' : 'fa-dice',
            'site_name' => AdminSetting::first()->{'Splash Title'} ?? 'Admin Panel'
        ]);
    }

    public function store(Request $request, string $game_type)
    {
        $validated = $request->validate([
            'match_title' => 'required|string|max:255',
            'match_time' => 'required|date',
            'total_prize' => 'required|numeric',
            'prize_1st' => 'nullable|numeric',
            'prize_2nd' => 'nullable|numeric',
            'prize_3rd' => 'nullable|numeric',
            'per_kill' => 'required|numeric',
            'entry_fee' => 'required|numeric',
            'match_type' => 'required|string',
            'entry_type_gameplay' => 'required|string',
            'version' => 'required|string',
            'play_map' => ($game_type == 'freefire') ? 'required|string' : 'nullable|string',
            'player_need' => 'required|integer',
        ]);

        $model = $this->getModelInstance($game_type);
        $model->Match_Key = 'match_' . uniqid();
        $model->Match_Title = $validated['match_title'];
        $model->Match_Time = $validated['match_time'];
        $model->Total_Prize = $validated['total_prize'];
        $model->prize_1st = $validated['prize_1st'] ?? '0';
        $model->prize_2nd = $validated['prize_2nd'] ?? '0';
        $model->prize_3rd = $validated['prize_3rd'] ?? '0';
        $model->Per_Kill = $validated['per_kill'];
        $model->Entry_Fee = $validated['entry_fee'];
        $model->Entry_Type = $validated['entry_type_gameplay'];
        $model->Match_Type = $validated['match_type'];
        $model->Version = $validated['version'];
        $model->Play_Map = ($game_type == 'freefire') ? $validated['play_map'] : 'Ludo';
        $model->Player_Need = $validated['player_need'];
        $model->Player_Join = 0;
        $model->Position = 'Match';
        
        $model->save();

        $matchTime = date('g:i A', strtotime($validated['match_time']));
        $title = "🔥 নতুন ম্যাচ যুক্ত হয়েছে!";
        $body = "{$matchTime} টার ম্যাচ এড করা হয়েছে 🎮❤️";
        
        $this->sendNotifications($title, $body);

        return redirect()->route('admin.matches.index', $game_type)->with('success', 'Match created and notifications sent successfully!');
    }

    public function edit(string $game_type, $match_id)
    {
        $match = $this->findMatchById($game_type, $match_id);
        $site_name = AdminSetting::first()->{'Splash Title'} ?? 'Admin Panel';
        return view('admin.matches.edit', compact('match', 'game_type', 'site_name'));
    }

    public function update(Request $request, string $game_type, $match_id)
    {
        $match = $this->findMatchById($game_type, $match_id);
        $validated = $request->validate([
            'Match_Title' => 'required|string',
            'Total_Prize' => 'required|numeric',
            'Per_Kill' => 'required|numeric',
            'Entry_Fee' => 'required|numeric',
            'Room_ID' => 'nullable|string',
            'Room_Pass' => 'nullable|string',
            'Position' => 'required|string|in:Match,OnGoing,Result',
        ]);
        $match->update($validated);
        return redirect()->route('admin.matches.edit', [$game_type, $match->id])->with('success', 'Match updated successfully!');
    }

    public function destroy(string $game_type, $match_id)
    {
        $match = $this->findMatchById($game_type, $match_id);
        try {
            DB::transaction(function () use ($match) {
                Joining::where('Match_Key', $match->Match_Key)->delete();
                Result::where('match_key', $match->Match_Key)->delete();
                $match->delete();
            });
        } catch (\Exception $e) {
            return redirect()->route('admin.matches.index', ['game_type' => $game_type])->with('error', 'Failed to delete match. Error: ' . $e->getMessage());
        }
        return redirect()->route('admin.matches.index', ['game_type' => $game_type])->with('success', 'Match and all related data have been deleted successfully!');
    }

    /**
     * ম্যাচে জয়েন করা খেলোয়াড়দের তালিকা দেখানোর জন্য সংশোধিত মেথড
     */
    public function showJoiners(string $game_type, string $match_key)
    {
        $match = $this->findMatchByKey($match_key);
        if (!$match) abort(404, 'Match not found.');
        
        // ** পরিবর্তিত অংশ: এখানে ->with('user') বাদ দেওয়া হয়েছে **
        $joiners = Joining::where('Match_Key', $match_key)->get();
        
        return view('admin.matches.joiners', compact('joiners', 'match', 'game_type'));
    }

    public function refundPlayer(Request $request, string $game_type, $join_id)
    {
        $joiner = Joining::findOrFail($join_id);
        $user = User::where('Number', $joiner->Number)->first();
        $entry_fee = (float)$request->input('entry_fee', 0);

        if ($user && $entry_fee > 0) {
            try {
                DB::transaction(function () use ($user, $entry_fee, $joiner, $game_type) {
                    $user->increment('Winning', $entry_fee);
                    $this->getModelInstance($game_type)->where('Match_Key', $joiner->Match_Key)->decrement('Player_Join');
                    $joiner->delete();
                });
                return back()->with('success', "Player refunded successfully!");
            } catch (\Exception $e) {
                return back()->with('error', "Error: " . $e->getMessage());
            }
        }
        return back()->with('error', "Could not refund player. User or entry fee not found.");
    }

    public function showResultForm(string $game_type, string $match_key)
    {
        $match = $this->findMatchByKey($match_key);
        if (!$match) abort(404, 'Match not found.');
        $joiners = Joining::where('Match_Key', $match_key)->get();
        return view('admin.matches.result', compact('match', 'joiners', 'game_type'));
    }

    public function processResult(Request $request, string $game_type, string $match_key)
    {
        $match = $this->findMatchByKey($match_key);
        if (!$match || $match->Position === 'Result') {
            return back()->with('error', 'Result has already been processed or match not found.');
        }
        $winners = [
            1 => ['number' => $request->input('winner_1'), 'prize' => (float)$match->prize_1st],
            2 => ['number' => $request->input('winner_2'), 'prize' => (float)$match->prize_2nd],
            3 => ['number' => $request->input('winner_3'), 'prize' => (float)$match->prize_3rd],
        ];
        try {
            DB::transaction(function () use ($winners, $match, $match_key) {
                foreach ($winners as $position => $winner) {
                    if (!empty($winner['number']) && $winner['prize'] > 0) {
                        User::where('Number', $winner['number'])->increment('Winning', $winner['prize']);
                        Joining::where('Match_Key', $match_key)->where('Number', $winner['number'])->update(['winnings' => $winner['prize'], 'position' => $position]);
                    }
                }
                $match->Position = 'Result';
                $match->save();
            });
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred during processing: ' . $e->getMessage());
        }
        return redirect()->route('admin.matches.result.form', ['game_type' => $game_type, 'match_key' => $match->Match_Key])->with('success', 'Result processed and winnings distributed successfully!');
    }
}