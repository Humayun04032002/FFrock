<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendingPayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentNotificationController extends Controller
{
    public function store(Request $request)
    {
        // .env ফাইল থেকে গোপন কী আনা হচ্ছে
        $secretApiKey = env('PAYMENT_API_SECRET_KEY', 'default_secret_key');

        // নিরাপত্তা চেক
        if ($request->input('api_key') !== $secretApiKey) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized Access.'], 401);
        }

        // ভ্যালিডেশন
        $validator = Validator::make($request->all(), [
            'trx_id' => 'required|string|unique:pending_payments,trx_id',
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        try {
            PendingPayment::create([
                'trx_id' => $request->trx_id,
                'amount' => $request->amount,
                'method' => $request->method,
                'received_at' => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Payment data received successfully.']);

        } catch (\Exception $e) {
            // কোনো ডেটাবেস এরর হলে লগ ফাইলে লেখা হবে
            Log::error('Payment Notification Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Database Error.'], 500);
        }
    }
}