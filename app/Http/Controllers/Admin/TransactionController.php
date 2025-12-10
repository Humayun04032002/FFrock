<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AddMoney;
use App\Models\Withdraw;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * 'addmoney' বা 'withdraw' অনুযায়ী পেন্ডিং লেনদেনের তালিকা দেখায়।
     */
    public function index(string $type)
    {
        if (!in_array($type, ['addmoney', 'withdraw'])) {
            abort(404);
        }

        $model = ($type === 'addmoney') ? new AddMoney() : new Withdraw();
        $page_title = ($type === 'addmoney') ? 'Add Money Requests' : 'Withdraw Requests';
        $page_icon = ($type === 'addmoney') ? 'fa-wallet' : 'fa-hand-holding-dollar';
        
        $transactions = $model->where('Status', 'Pending')->orderBy('id', 'desc')->get();

        return view('admin.transactions.index', compact('transactions', 'type', 'page_title', 'page_icon'));
    }

    /**
     * একটি নির্দিষ্ট লেনদেনকে Approve বা Reject করে।
     */
    public function update(Request $request, string $type, int $id)
    {
        if (!in_array($type, ['addmoney', 'withdraw'])) {
            abort(404);
        }

        $request->validate([
            'action' => 'required|string|in:approve,reject',
            'user_number' => 'required|string|exists:aerox_users,Number',
            'amount' => 'required|numeric|min:0',
        ]);

        $model = ($type === 'addmoney') ? AddMoney::class : Withdraw::class;
        $transaction = $model::findOrFail($id);

        $action = $request->input('action');
        $user_number = $request->input('user_number');
        $amount = (float)$request->input('amount');
        $user = User::where('Number', $user_number)->first();

        if (!$user) {
            return back()->with('error', 'User not found!');
        }

        DB::transaction(function () use ($transaction, $action, $type, $user, $amount) {
            if ($action === 'approve') {
                if ($type === 'addmoney') {
                    $user->increment('Balance', $amount);
                }
                $transaction->Status = 'Complete';
            } elseif ($action === 'reject') {
                if ($type === 'withdraw') {
                    // যদি উইথড্র বাতিল হয়, টাকা ইউজারের উইনিং ব্যালেন্সে ফেরত যাবে
                    $user->increment('Winning', $amount);
                }
                $transaction->Status = 'Failed';
            }
            $transaction->save();
        });

        return redirect()->route('admin.transactions.index', $type)->with('success', 'Transaction has been updated!');
    }
}