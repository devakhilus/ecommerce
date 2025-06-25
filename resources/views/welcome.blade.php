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

        @media (max-width: 768px) {
            .product-card img {
                height: 150px;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">MiniAmazon</a>
            <div class="d-flex align-items-center ms-auto gap-2">
                <button id="theme-toggle" class="btn btn-outline-light btn-sm">
                    <span id="theme-icon">🌙</span>
                </button>

                @auth
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

                <a href="{{ auth()->check() ? '/cart' : '/login' }}" class="btn btn-outline-light btn-sm position-relative">
                    🛒 Cart
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ session('cart_count', 0) }}
                    </span>
                </a>
            </div>
        </div>
    </nav>

    @if(session('success'))
    <div class="alert alert-success fade-message position-fixed top-0 end-0 m-3">
        {{ session('success') }}
    </div>
    @endif

    <!-- Hero -->
    <div class="hero text-center">
        <h1 class="mb-4">Welcome to Mini Amazon</h1>
        <div class="search-box">
            <input type="text" id="searchInput" class="form-control" placeholder="Search for products or categories...">
        </div>
    </div>

    <!-- Products -->
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
    </script>

    <!-- Theme + Products + Cart Script -->
    <script>
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

        let offset = 0;
        const limit = 6;
        let currentSearch = '';
        const loadMoreBtn = document.getElementById('load-more');
        const productList = document.getElementById('product-list');
        const searchInput = document.getElementById('searchInput');
        const spinner = document.getElementById('loading-spinner');

        function fetchProducts(reset = false) {
            if (reset) offset = 0;
            spinner.style.display = 'block';

            fetch(`${BACKEND_URL}/api/products-api?limit=${limit}&offset=${offset}&search=${encodeURIComponent(currentSearch)}`)
                .then(res => res.json())
                .then(products => {
                    if (reset) productList.innerHTML = '';
                    products.forEach(product => {
                        const image = product.picture ?
                            `${BACKEND_URL}/images/products/${product.picture}` :
                            'https://via.placeholder.com/300x200';

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
                                        <button class="btn btn-outline-secondary w-50 add-to-cart"
                                            data-id="${product.id}"
                                            data-name="${product.name}"
                                            data-price="${product.price}"
                                            data-image="${image}">
                                            🛒 Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>`;
                        productList.appendChild(col);
                    });

                    offset += limit;
                    loadMoreBtn.style.display = products.length < limit ? 'none' : 'inline-block';
                    markAddedProducts(); // update cart button states
                })
                .finally(() => {
                    spinner.style.display = 'none';
                });
        }

        loadMoreBtn.addEventListener('click', () => fetchProducts());

        let typingTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                currentSearch = searchInput.value.trim();
                fetchProducts(true);
            }, 300);
        });

        function updateCartBadge() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const count = cart.reduce((sum, item) => sum + item.qty, 0);
            const badge = document.querySelector('.badge.bg-danger');
            if (badge) badge.textContent = count;
        }

        function markAddedProducts() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            document.querySelectorAll('.add-to-cart').forEach(btn => {
                const id = btn.getAttribute('data-id');
                const exists = cart.find(item => item.id == id);
                btn.textContent = exists ? '✅ Added' : '🛒 Add to Cart';
                btn.classList.toggle('active', !!exists);
            });
        }

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-to-cart')) {
                const btn = e.target;

                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                const price = parseFloat(btn.getAttribute('data-price'));
                const image = btn.getAttribute('data-image');

                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                const index = cart.findIndex(item => item.id == id);

                if (index > -1) {
                    cart.splice(index, 1); // Remove
                    btn.textContent = '🛒 Add to Cart';
                    btn.classList.remove('active');
                } else {
                    cart.push({
                        id,
                        name,
                        price,
                        image,
                        qty: 1
                    });
                    btn.textContent = '✅ Added';
                    btn.classList.add('active');
                }

                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartBadge();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            updateCartBadge();
            fetchProducts();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>