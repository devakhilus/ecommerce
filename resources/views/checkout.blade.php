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
                        <!-- Populated by JS -->
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
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="payment_method" value="phonepe">
                    <label class="form-check-label">📱 Pay Online (PhonePe)</label>
                </div>
            </div>

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

            document.getElementById('delivery-address').textContent = deliveryAddress;
            document.getElementById('delivery-address-input').value = deliveryAddress;

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

            const form = document.getElementById('order-form');
            form.addEventListener('submit', function(e) {
                const selected = document.querySelector('input[name="payment_method"]:checked')?.value;
                document.getElementById('selected-payment-method').value = selected || 'cod';

                if (selected === 'phonepe') {
                    e.preventDefault();

                    // Dynamic form for PhonePe
                    const phonepeRoute = "{{ route('phonepe.pay') }}";
                    const tempForm = document.createElement('form');
                    tempForm.method = 'POST';
                    tempForm.action = phonepeRoute;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const amt = document.createElement('input');
                    amt.type = 'hidden';
                    amt.name = 'amount';
                    amt.value = grandTotal.toFixed(2);

                    const addressInput = document.createElement('input');
                    addressInput.type = 'hidden';
                    addressInput.name = 'delivery_address';
                    addressInput.value = deliveryAddress;

                    const cartInput = document.createElement('input');
                    cartInput.type = 'hidden';
                    cartInput.name = 'cart_data';
                    cartInput.value = JSON.stringify(cart);

                    tempForm.appendChild(csrf);
                    tempForm.appendChild(amt);
                    tempForm.appendChild(addressInput);
                    tempForm.appendChild(cartInput);
                    document.body.appendChild(tempForm);
                    tempForm.submit();
                }
            });

        } catch (error) {
            alert('Checkout data is missing or corrupted. Please re-check.');
            console.error(error);
        }
    });
</script>
@endsection