<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;

class OrderController extends Controller
{
    // Show checkout page
    public function checkout()
    {
        return view('checkout');
    }

    // Store order
    public function store(Request $request)
    {
        $request->validate([
            'cart_data' => 'required',
            'delivery_address' => 'required|string',
            'payment_method' => 'required|in:cod,online',
        ]);

        // Decode cart data safely
        $cart = json_decode($request->cart_data, true);

        // ✅ Ensure cart is valid
        if (!is_array($cart) || empty($cart)) {
            return back()->withErrors(['cart_data' => 'Invalid or empty cart data. Please try again.']);
        }

        $user = Auth::user();

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'total' => collect($cart)->sum(fn($item) => $item['price'] * $item['qty']),
            'status' => 'pending',
            'delivery_address' => $request->delivery_address,
            'payment_method' => $request->payment_method,
        ]);

        // Create order-product entries
        foreach ($cart as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
            ]);
            // ✅ Decrease product stock
            $product = Product::find($item['id']);
            if ($product) {
                $product->stock = max(0, $product->stock - $item['qty']);
                $product->save();
            }
        }

        // You could also dispatch an email or notification here if needed

        return redirect('/')->with('success', '✅ Order placed successfully!');
    }
}
