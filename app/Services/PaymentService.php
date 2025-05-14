<?php

namespace App\Services;

use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction($orderId, $amount, $user)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            $transaction = Transaction::create([
                'order_id' => $orderId,
                'user_id' => $user->id,
                'total_amount' => $amount,
                'transaction_status' => 'pending',
                'snap_token' => $snapToken,
            ]);

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'transaction' => $transaction,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function handleCallback($notification)
    {
        $transaction = Transaction::where('order_id', $notification->order_id)->first();
        
        if (!$transaction) {
            return false;
        }

        $transaction->transaction_status = $notification->transaction_status;
        $transaction->payment_type = $notification->payment_type;
        $transaction->transaction_id = $notification->transaction_id;
        
        if ($notification->transaction_status == 'settlement') {
            // Update order status to paid
            // Add your logic here
        }

        $transaction->save();
        
        return true;
    }
} 