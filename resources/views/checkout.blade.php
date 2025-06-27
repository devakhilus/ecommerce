@extends('layout')

@section('content')
<div class="container my-5">
    <h3 class="mb-4">🧾 Checkout</h3>

    <div id="checkout-details">
        <p><strong>Delivery Address:</strong> <span id="delivery-address" class="text-primary"></span></p>

        <div class="mb-3">
            <label class="form-label">Select Payment Method</label><br>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="cod" checked>
                <label class="form-check-label">💵 Cash on Delivery</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="online">
                <label class="form-check-label">💳 Pay Online</label>
            </div>
        </div>

        <form id="order-form" method="POST" action="{{ route('orders.store') }}">
            @csrf
            <input type="hidden" name="delivery_address" id="delivery-address-input">
            <input type="hidden" name="cart_data" id="cart-json">
            <input type="hidden" name="payment_method" id="selected-payment-method">

            <button type="submit" class="btn btn-success w-100">Place Order</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        try {
            const checkoutData = JSON.parse(localStorage.getItem('checkoutData') || '{}');
            const deliveryAddress = checkoutData.address || 'N/A';
            const cart = checkoutData.cart || [];

            // Fill in the delivery address and cart data
            document.getElementById('delivery-address').textContent = deliveryAddress;
            document.getElementById('delivery-address-input').value = deliveryAddress;
            document.getElementById('cart-json').value = JSON.stringify(cart);

            // Handle form submit
            document.getElementById('order-form').addEventListener('submit', function(e) {
                const selected = document.querySelector('input[name="payment_method"]:checked')?.value;
                document.getElementById('selected-payment-method').value = selected || 'cod';

                // ✅ Clear cart after placing order
                localStorage.removeItem('cart');
                localStorage.removeItem('checkoutData');
            });
        } catch (error) {
            alert('Checkout data is invalid or missing. Please go back and try again.');
            console.error('Checkout data error:', error);
        }
    });
</script>
@endsection