<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PhonePePaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        // Step 1: Get OAuth Token
        $authResponse = Http::asForm()->post(env('PHONEPE_BASE_URL') . '/v1/oauth/token', [
            'client_id' => env('PHONEPE_CLIENT_ID'),
            'client_version' => '1',
            'client_secret' => env('PHONEPE_CLIENT_SECRET'),
            'grant_type' => 'client_credentials',
        ]);

        $authData = $authResponse->json();

        if (!$authResponse->successful() || !isset($authData['access_token'])) {
            return back()->with('error', 'Failed to authenticate with PhonePe.');
        }

        $accessToken = $authData['access_token'];

        // Step 2: Initiate Payment
        $orderId = 'ORDER_' . time();
        $amount = intval($request->amount * 100); // ₹ to paise

        $paymentResponse = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'O-Bearer ' . $accessToken,
        ])->post(env('PHONEPE_BASE_URL') . '/checkout/v2/pay', [
            'merchantOrderId' => $orderId,
            'amount' => $amount,
            'paymentFlow' => [
                'type' => 'PG_CHECKOUT',
                'message' => 'Order payment',
                'merchantUrls' => [
                    'redirectUrl' => route('phonepe.success', ['id' => $orderId]),
                ],
            ],
        ]);

        $paymentData = $paymentResponse->json();

        if (isset($paymentData['redirectUrl'])) {
            return redirect()->away($paymentData['redirectUrl']);
        }

        return back()->with('error', 'Payment initiation failed.');
    }

    public function success($id)
    {
        return view('payment.status', ['order_id' => $id]);
    }
}
