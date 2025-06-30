<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;

class PhonePePaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        // ✅ Step 0: Store delivery address and cart in session
        session([
            'cart' => json_decode($request->cart_data, true),
            'address' => $request->delivery_address,
        ]);

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
        $amount = intval((float)$request->amount * 100); // ₹ to paise (float to int)

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
        // Get session data saved before payment
        $cart = session('cart');
        $address = session('address');
        $userId = auth()->id(); // optional: require login

        if (!$cart || !$address || !$userId) {
            return redirect('/')->with('error', 'Missing order data.');
        }

        // Calculate total
        $total = collect($cart)->sum(fn($item) => $item['qty'] * $item['price']);

        // Save everything in transaction
        DB::beginTransaction();

        try {
            // Step 1: Save order
            $order = Order::create([
                'user_id' => $userId,
                'total' => $total,
                'status' => 'paid',
                'payment_method' => 'phonepe',
                'delivery_address' => $address,
            ]);

            // Step 2: Save order_products and reduce stock
            foreach ($cart as $item) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);

                // Reduce stock
                $product = Product::find($item['id']);
                if ($product && $product->stock >= $item['qty']) {
                    $product->stock -= $item['qty'];
                    $product->save();
                }
            }

            DB::commit();

            // Clear session
            session()->forget(['cart', 'address']);

            return view('payment.status', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/')->with('error', 'Payment processed but order failed: ' . $e->getMessage());
        }
    }
}
