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
            height: auto;
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

        .fade-message {
            position: fixed;
            top: 70px;
            right: 20px;
            z-index: 1050;
            min-width: 250px;
        }

        @media (max-width: 768px) {
            .product-card img {
                height: 180px;
                object-fit: cover;
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
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        👤 {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
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
    <div class="alert alert-success fade-message">
        {{ session('success') }}
    </div>
    @endif

    <!-- Hero with Search -->
    <div class="hero text-center">
        <h1 class="mb-4">Welcome to Mini Amazon</h1>
        <div class="search-box">
            <input type="text" id="searchInput" class="form-control" placeholder="Search for products or categories...">
        </div>
    </div>

    <!-- Product Section -->
    <div class="container my-5">
        <h3 class="mb-4 text-center">Featured Products</h3>
        <div class="row g-4" id="product-list"></div>
        <div class="text-center mt-4">
            <button id="load-more" class="btn btn-outline-primary">Load More</button>
        </div>
    </div>

    <!-- ... keep your HTML head + styles exactly as-is ... -->

<!-- Inside <script> near the bottom -->
<script>
    const htmlEl = document.documentElement;
    const toggleBtn = document.getElementById('theme-toggle');
    const icon = document.getElementById('theme-icon');

    function applyTheme(theme) {
        htmlEl.setAttribute('data-bs-theme', theme);
        localStorage.setItem('theme', theme);
        icon.textContent = theme === 'dark' ? '🌞' : '🌙';
    }

    const savedTheme = localStorage.getItem('theme');
    applyTheme(savedTheme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));

    toggleBtn.addEventListener('click', () => {
        const current = htmlEl.getAttribute('data-bs-theme');
        applyTheme(current === 'dark' ? 'light' : 'dark');
    });

    setTimeout(() => {
        const alert = document.querySelector('.fade-message');
        if (alert) {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000);

    // AJAX product loader
    let offset = 0;
    const limit = 6;
    let currentSearch = '';
    const loadMoreBtn = document.getElementById('load-more');
    const productList = document.getElementById('product-list');
    const searchInput = document.getElementById('searchInput');

    function fetchProducts(reset = false) {
        if (reset) offset = 0;

        const url = `/products-api?limit=${limit}&offset=${offset}&search=${encodeURIComponent(currentSearch)}`;

        fetch(url)
            .then(res => {
                if (!res.ok) {
                    console.error("Fetch failed:", res.status, res.statusText);
                    throw new Error(`Server responded with ${res.status}`);
                }
                return res.json();
            })
            .then(products => {
                if (reset) {
                    productList.innerHTML = '';
                }

                products.forEach(product => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 col-sm-6';
                    col.innerHTML = `
                        <div class="card product-card h-100">
                            <img src="${product.image_url ?? 'https://placehold.co/300x200'}" class="card-img-top" alt="${product.name}">

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text flex-grow-1">${(product.description ?? '').substring(0, 100)}</p>
                                <p class="fw-bold">₹${parseFloat(product.price).toFixed(2)}</p>
                                <a href="/product/${product.id}" class="btn btn-primary mt-auto">Buy Now</a>
                            </div>
                        </div>`;
                    productList.appendChild(col);
                });

                offset += limit;
                loadMoreBtn.style.display = products.length < limit ? 'none' : 'inline-block';
            })
            .catch(err => {
                console.error("Fetch error:", err);
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

    // Initial load
    fetchProducts();
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
