<div id="cart-panel">
    <h5 class="mb-3">🛒 Your Cart</h5>

    <div id="cart-items"></div>

    {{-- Address Selector --}}
    @auth
    <div class="mb-3">
        <label for="delivery-address" class="form-label fw-bold">Select Delivery Address</label>
        <select id="delivery-address" class="form-select">
            <option value="{{ Auth::user()->home_address }}">Home Address - {{ Auth::user()->home_address }}</option>
            <option value="other">Other Address</option>
        </select>

        <div id="custom-address-wrapper" class="mt-2 d-none">
            <input type="text" id="custom-address" class="form-control" placeholder="Enter custom delivery address">
        </div>

    </div>
    @endauth


    <hr>
    <p><strong>Total Items:</strong> <span id="cart-total-items">0</span></p>
    <p><strong>Total Price:</strong> ₹<span id="cart-total-price">0.00</span></p>

    <button id="checkout-btn" class="btn btn-primary w-100">Checkout</button>
</div>