<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        // For now, just return test data
        return response()->json([
            'status' => 'PhonePe payment simulation',
            'amount' => $request->amount,
        ]);
    }
}
