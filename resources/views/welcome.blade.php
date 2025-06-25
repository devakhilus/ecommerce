<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Amazon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        .navbar {
            background-color: #232f3e;
        }

        .navbar-brand,
        .nav-link,
        .navbar-text {
            color: #fff !important;
        }

        .hero {
            background: url('https://images.unsplash.com/photo-1523275335684-37898b6baf30?fit=crop&w=1350&q=80') center/cover no-repeat;
            padding: 60px 15px 40px;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.7);
        }

        .search-box {
            max-width: 600px;
            margin: 0 auto;
        }

        .product-card {
            border: none;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        #cart-panel {
            position: fixed;
            right: 0;
            top: 60px;
            width: 350px;
            height: calc(100% - 60px);
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            padding: 1rem;
            overflow-y: auto;
            display: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .cart-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .qty-controls button {
            padding: 2px 8px;
        }

        .remove-btn {
            color: red;
            cursor: pointer;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .product-card img {
                height: 150px;
            }

            #cart-panel {
                width: 100%;
                top: 56px;
                height: calc(100% - 56px);
            }
        }
    </style>
</head>


<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">MiniAmazon</a>
            <div class="d-flex align-items-center ms-auto gap-2">
                <button id="theme-toggle" class="btn btn-outline-light btn-sm">
                    <span id="theme-icon">🌙</span>
                </button>
                @auth
                <a href="/admin" class="btn btn-warning btn-sm">Admin</a>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        👤 {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/profile">Profile</a></li>
                        <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                        <li><a class="dropdown-item" href="/logout">Logout</a></li>
                    </ul>
                </div>
                @else
                <a class="btn btn-outline-light btn-sm" href="/login">Login</a>
                <a class="btn btn-light btn-sm" href="/register">Register</a>
                @endauth
                <button id="cart-toggle" class="btn btn-outline-light btn-sm position-relative">
                    🛒 Cart
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">0</span>
                </button>
            </div>
        </div>
    </nav>

    <div id="cart-panel">
        <h5 class="mb-3">🛒 Your Cart</h5>
        <div id="cart-items"></div>
        <hr>
        <p><strong>Total Items:</strong> <span id="cart-total-items">0</span></p>
        <p><strong>Total Price:</strong> ₹<span id="cart-total-price">0.00</span></p>
        <button id="checkout-btn" class="btn btn-primary w-100">Checkout</button>
    </div>

    <div class="hero text-center">
        <h1 class="mb-4">Welcome to Mini Amazon</h1>
        <div class="search-box">
            <input type="text" id="searchInput" class="form-control" placeholder="Search for products or categories...">
        </div>
    </div>

    <div class="container my-5">
        <h3 class="mb-4 text-center">Featured Products</h3>
        <div class="text-center my-3" id="loading-spinner" style="display:none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="row g-4" id="product-list"></div>
        <div class="text-center mt-4">
            <button id="load-more" class="btn btn-outline-primary">Load More</button>
        </div>
    </div>

    <script>
        const BACKEND_URL = "{{ url('') }}";
        let offset = 0,
            limit = 6,
            currentSearch = '';

        function updateCartDisplay() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            document.getElementById('cart-count').textContent = cart.reduce((sum, item) => sum + item.qty, 0);
            document.getElementById('cart-items').innerHTML = cart.map((item, i) => `
                <div class="cart-item">
                    <div class="d-flex justify-content-between">
                        <strong>${item.name}</strong>
                        <span class="remove-btn" onclick="removeItem(${i})">&times;</span>
                    </div>
                    <p>₹${item.price.toFixed(2)} × <span>${item.qty}</span></p>
                    <div class="qty-controls">
                        <button onclick="changeQty(${i}, -1)">-</button>
                        <button onclick="changeQty(${i}, 1)">+</button>
                    </div>
                </div>`).join('');
            document.getElementById('cart-total-items').textContent = cart.length;
            document.getElementById('cart-total-price').textContent = cart.reduce((sum, item) => sum + item.price * item.qty, 0).toFixed(2);
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
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartDisplay();
        }

        function fetchProducts(reset = false) {
            if (reset) offset = 0;
            document.getElementById('loading-spinner').style.display = 'block';
            fetch(`${BACKEND_URL}/api/products-api?limit=${limit}&offset=${offset}&search=${encodeURIComponent(currentSearch)}`)
                .then(res => res.json())
                .then(products => {
                    if (reset) document.getElementById('product-list').innerHTML = '';
                    products.forEach(product => {
                        const image = product.picture ? `${BACKEND_URL}/images/products/${product.picture}` : 'https://via.placeholder.com/300x200';
                        const col = document.createElement('div');
                        col.className = 'col-md-4 col-sm-6';
                        col.innerHTML = `
                            <div class="card product-card h-100">
                                <img src="${image}" class="card-img-top" alt="${product.name}">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text flex-grow-1">${(product.description ?? '').substring(0, 100)}</p>
                                    <p class="fw-bold">₹${parseFloat(product.price).toFixed(2)}</p>
                                    <div class="d-flex justify-content-between mt-auto gap-2">
                                        <a href="/product/${product.id}" class="btn btn-primary w-50">Buy Now</a>
                                        <button class="btn btn-outline-secondary w-50" onclick='addToCart(${JSON.stringify(product)})'>Add to Cart</button>
                                    </div>
                                </div>
                            </div>`;
                        document.getElementById('product-list').appendChild(col);
                    });
                    offset += limit;
                    markAddedProducts();
                })
                .finally(() => document.getElementById('loading-spinner').style.display = 'none');
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
                    qty: 1
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

        document.getElementById('cart-toggle').onclick = () => {
            const panel = document.getElementById('cart-panel');
            panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
            updateCartDisplay();
        };

        document.getElementById('checkout-btn').onclick = () => {
            if (confirm('Proceed to checkout?')) alert('Checkout logic here');
        };

        document.getElementById('searchInput').addEventListener('input', () => {
            clearTimeout(window.searchTimer);
            window.searchTimer = setTimeout(() => {
                currentSearch = document.getElementById('searchInput').value.trim();
                fetchProducts(true);
            }, 300);
        });

        document.getElementById('load-more').onclick = () => fetchProducts();

        document.addEventListener('DOMContentLoaded', () => {
            updateCartDisplay();
            fetchProducts();
            const htmlEl = document.documentElement;
            const toggleBtn = document.getElementById('theme-toggle');
            const icon = document.getElementById('theme-icon');

            function applyTheme(theme) {
                htmlEl.setAttribute('data-bs-theme', theme);
                localStorage.setItem('theme', theme);
                icon.textContent = theme === 'dark' ? '🌞' : '🌙';
            }

            applyTheme(localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
            toggleBtn.addEventListener('click', () => {
                const current = htmlEl.getAttribute('data-bs-theme');
                applyTheme(current === 'dark' ? 'light' : 'dark');
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>