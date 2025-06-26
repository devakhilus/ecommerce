function updateCartDisplay() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    document.getElementById('cart-count').textContent = cart.reduce((sum, item) => sum + item.qty, 0);

    document.getElementById('cart-items').innerHTML = cart.map((item, i) => {
        const remaining = item.stock - item.qty;
        const atMax = remaining <= 0;

        const stockMsg = atMax
            ? '<small class="text-danger">No more stock available</small>'
            : `<small class="text-success">${remaining} in stock</small>`;

        return `
            <div class="cart-item">
                <div class="d-flex justify-content-between">
                    <strong>${item.name}</strong>
                    <span class="remove-btn" onclick="removeItem(${i})">&times;</span>
                </div>
                <p>₹${item.price.toFixed(2)} × <span>${item.qty}</span></p>
                <div class="qty-controls d-flex gap-1">
                    <button onclick="changeQty(${i}, -1)">-</button>
                    <button onclick="changeQty(${i}, 1)" ${atMax ? 'disabled' : ''}>+</button>
                </div>
                ${stockMsg}
            </div>`;
    }).join('');

    document.getElementById('cart-total-items').textContent = cart.length;
    document.getElementById('cart-total-price').textContent =
        cart.reduce((sum, item) => sum + item.price * item.qty, 0).toFixed(2);

    markAddedProducts();
}

function removeItem(index) {
    if (confirm('Remove this item?')) {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartDisplay();
    }
}

function changeQty(index, delta) {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    cart[index].qty += delta;
    if (cart[index].qty <= 0) return removeItem(index);
    if (cart[index].qty > cart[index].stock) cart[index].qty = cart[index].stock; // 🔒 enforce max
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
}

function addToCart(product) {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const index = cart.findIndex(item => item.id == product.id);
    const btn = event.target;

    if (index > -1) {
        if (confirm('Remove from cart?')) {
            cart.splice(index, 1);
            btn.textContent = 'Add to Cart';
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-secondary');
        } else return;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: parseFloat(product.price),
            qty: 1,
            stock: parseInt(product.stock) || 0 // ✅ store stock
        });
        btn.textContent = '✅ Added';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-primary');
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
}

function markAddedProducts() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    document.querySelectorAll('button[onclick^="addToCart"]').forEach(btn => {
        const name = btn.closest('.product-card').querySelector('.card-title').textContent;
        const found = cart.find(item => item.name === name);
        if (found) {
            btn.textContent = '✅ Added';
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-primary');
        } else {
            btn.textContent = 'Add to Cart';
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-secondary');
        }
    });
}
