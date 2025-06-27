@extends('layout')

@section('content')
<div class="container my-5">
    <h3 class="mb-4">🧾 Checkout</h3>

    <div id="checkout-details">
        <form id="order-form" method="POST" action="{{ route('orders.store') }}">
            @csrf

            <!-- Hidden Fields -->
            <input type="hidden" name="delivery_address" id="delivery-address-input">
            <input type="hidden" name="cart_data" id="cart-json">
            <input type="hidden" name="payment_method" id="selected-payment-method">

            <!-- Summary Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th style="width: 90px;">Qty</th>
                            <th style="width: 110px;">Price</th>
                            <th style="width: 120px;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="checkout-cart-items">
                        <!-- JS will populate -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">🛍️ Total</th>
                            <th id="checkout-cart-total">₹0.00</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">📍 Delivery Address</th>
                            <td id="delivery-address" class="text-primary fw-bold"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment Method -->
            <div class="mb-4">
                <h5>💳 Payment Method</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" value="cod" checked>
                    <label class="form-check-label">💵 Cash on Delivery</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" value="online">
                    <label class="form-check-label">💳 Pay Online</label>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-success w-100 btn-lg">
                ✅ Confirm & Place Order
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        try {
            const checkoutData = JSON.parse(localStorage.getItem('checkoutData') || '{}');
            const deliveryAddress = checkoutData.address || 'N/A';
            const cart = checkoutData.cart || [];

            // Fill delivery address
            document.getElementById('delivery-address').textContent = deliveryAddress;
            document.getElementById('delivery-address-input').value = deliveryAddress;

            // Fill cart table
            const tableBody = document.getElementById('checkout-cart-items');
            const totalEl = document.getElementById('checkout-cart-total');
            let grandTotal = 0;

            cart.forEach(item => {
                const subtotal = item.qty * item.price;
                grandTotal += subtotal;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.qty}</td>
                    <td>₹${item.price.toFixed(2)}</td>
                    <td>₹${subtotal.toFixed(2)}</td>
                `;
                tableBody.appendChild(row);
            });

            totalEl.textContent = `₹${grandTotal.toFixed(2)}`;
            document.getElementById('cart-json').value = JSON.stringify(cart);

            // Handle form submit
            document.getElementById('order-form').addEventListener('submit', function() {
                const selected = document.querySelector('input[name="payment_method"]:checked')?.value;
                document.getElementById('selected-payment-method').value = selected || 'cod';

                // ✅ Clear cart after placing order
                localStorage.removeItem('cart');
                localStorage.removeItem('checkoutData');
            });

        } catch (error) {
            alert('Checkout data is missing or invalid. Please go back and retry.');
            console.error(error);
        }
    });
</script>
@endsection