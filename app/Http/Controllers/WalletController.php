<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AdminSetting;
use App\Models\AddMoney;
use App\Models\Withdraw;
use App\Models\PendingPayment;

class WalletController extends Controller
{
    // index() মেথডটি অপরিবর্তিত আছে এবং এটি সঠিকভাবে কাজ করছে।
    public function index()
    {
        $user = Auth::user();
        $adminSettings = AdminSetting::first();
        $deposits = DB::table('aerox_addmoney')->select('Method', 'Amount', 'Date', 'Status', DB::raw("'Deposit' as type"));
        $transactions = DB::table('aerox_withdraw')->select('Method', 'Amount', 'Date', 'Status', DB::raw("'Withdraw' as type"))
            ->where('Number', $user->Number)
            ->unionAll($deposits->where('Number', $user->Number))
            ->orderBy('Date', 'desc')->limit(20)->get();
            
        return view('wallet', ['user' => $user, 'adminSettings' => $adminSettings, 'transactions' => $transactions]);
    }

    // handleTransaction() মেথডটিকে আরও শক্তিশালী করা হয়েছে।
    public function handleTransaction(Request $request)
    {
        $user = Auth::user();
        $adminSettings = AdminSetting::firstOrFail(); // নিশ্চিত করে যে সেটিংস আছে
        $action = $request->input('action');
        $amount = abs((float)($request->input('amount') ?? 0));

        switch ($action) {
            case 'add_money':
                return $this->addMoney($request, $user, $adminSettings, $amount);
            case 'withdraw':
                return $this->withdraw($request, $user, $adminSettings, $amount);
            case 'transfer_balance':
                return $this->transferBalance($request, $user, $amount);
            default:
                return redirect()->route('wallet.index')
                    ->with('status_type', 'error')
                    ->with('status_message', 'Invalid Action!');
        }
    }

    // --- Add Money: আপনার দেওয়া লজিক অনুযায়ী হুবহু তৈরি ---
    private function addMoney(Request $request, User $user, AdminSetting $adminSettings, float $amount)
    {
        $trx_id = trim($request->input('transaction_id', ''));
        $method_from_user = $request->input('method');

        // ভ্যালিডেশন (আপনার কোড অনুযায়ী)
        if (strlen($trx_id) < 5 || $trx_id !== strtoupper($trx_id) || !ctype_alnum($trx_id)) {
            return redirect()->route('wallet.index')->with('status_type', 'error')->with('status_message', 'Transaction ID is invalid.');
        }
        if ($amount <= 0) {
            return redirect()->route('wallet.index')->with('status_type', 'error')->with('status_message', 'Please provide a valid amount.');
        }

        $paymentMode = $adminSettings->Payment_Mode ?? 'Manual';

        // Auto Mode Logic
        if ($paymentMode === 'Auto') {
            $pendingPayment = PendingPayment::where('trx_id', $trx_id)->first();
            if ($pendingPayment) {
                if (abs((float)$pendingPayment->amount - $amount) < 0.01) {
                    try {
                        DB::transaction(function () use ($user, $amount, $method_from_user, $trx_id, $pendingPayment) {
                            $user->increment('Balance', $amount);
                            AddMoney::create(['Name' => $user->Name, 'Number' => $user->Number, 'Method' => $method_from_user, 'Amount' => $amount, 'Payment' => $trx_id, 'Date' => now(), 'Status' => 'Complete']);
                            $pendingPayment->delete();
                        });
                        return redirect()->route('wallet.index')->with('status_type', 'success')->with('status_message', 'Payment successful! Amount added.');
                    } catch (\Exception $e) {
                        return redirect()->route('wallet.index')->with('status_type', 'error')->with('status_message', 'Database error. Contact support.');
                    }
                } else {
                    return redirect()->route('wallet.index')->with('status_type', 'error')->with('status_message', 'Amount does not match for this Transaction ID.');
                }
            }
        }
        
        // Manual Mode or Auto Mode Fallback
        if (AddMoney::where('Payment', $trx_id)->exists()) {
            return redirect()->route('wallet.index')->with('status_type', 'error')->with('status_message', 'This Transaction ID has already been used.');
        }

        AddMoney::create(['Name' => $user->Name, 'Number' => $user->Number, 'Method' => $method_from_user, 'Amount' => $amount, 'Payment' => $trx_id, 'Date' => now(), 'Status' => 'Pending']);
        $message = ($paymentMode === 'Auto') ? 'Request sent for review as auto-verify failed.' : 'Request submitted successfully!';
        return redirect()->route('wallet.index')->with('status_type', 'success')->with('status_message', $message);
    }
    
    // --- Withdraw: আপনার দেওয়া লজিক অনুযায়ী হুবহু তৈরি ---
    private function withdraw(Request $request, User $user, AdminSetting $adminSettings, float $amount)
    {
        $current_winning = (float)$user->Winning;
        $min_withdraw = (float)($adminSettings->{'Minimum Withdraw'} ?? 100);
        $account_no = trim($request->input('account_no', ''));
        
        if ($amount <= 0 || $amount > $current_winning || $amount < $min_withdraw || empty($account_no)) {
             return redirect()->route('wallet.index')->with('status_type', 'error')->with('status_message', 'Invalid amount, insufficient winning balance, or account number empty.');
        }

        try {
            DB::transaction(function() use ($user, $amount, $request, $account_no) {
                $user->decrement('Winning', $amount);
                Withdraw::create([
                    'Name' => $user->Name, 'Number' => $user->Number, 'Method' => $request->input('method'),
                    'Amount' => $amount, 'Payment' => $account_no, 'Date' => now(), 'Status' => 'Pending'
                ]);
            });
            return redirect()->route('wallet.index')->with('status_type', 'success')->with('status_message', 'Withdraw request submitted!');
        } catch (\Exception $e) {
            return redirect()->route('wallet.index')->with('status_type', 'error')->with('status_message', 'Something went wrong. Please try again.');
        }
    }

    // --- Transfer Balance: আপনার দেওয়া লজিক অনুযায়ী হুবহু তৈরি ---
    private function transferBalance(Request $request, User $user, float $amount)
    {
        $current_winning = (float)$user->Winning;

        if ($amount <= 0 || $amount > $current_winning) {
            return redirect()->route('wallet.index')->with('status_type', 'error')->with('status_message', 'Invalid amount or insufficient winning balance.');
        }
        
        try {
            DB::transaction(function () use ($user, $amount) {
                $user->decrement('Winning', $amount);
                $user->increment('Balance', $amount);
            });
            return redirect()->route('wallet.index')->with('status_type', 'success')->with('status_message', 'Balance transferred successfully!');
        } catch (\Exception $e) {
            return redirect()->route('wallet.index')->with('status_type', 'error')->with('status_message', 'Something went wrong during transfer.');
        }
    }
}