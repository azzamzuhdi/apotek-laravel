<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
        ]);

        $orderId = 'ORDER-' . Str::random(10);
        $result = $this->paymentService->createTransaction(
            $orderId,
            $request->amount,
            auth()->user()
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }

        return response()->json([
            'success' => true,
            'snap_token' => $result['snap_token'],
            'transaction' => $result['transaction']
        ]);
    }

    public function handleCallback(Request $request)
    {
        $notification = json_decode($request->getContent());
        
        $result = $this->paymentService->handleCallback($notification);
        
        if ($result) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 400);
    }
} 