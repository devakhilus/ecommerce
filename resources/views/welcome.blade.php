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
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.7);
        }

        .product-card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
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
                {{-- Cart Button (Always visible) --}}
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
    <div class="alert alert-success fade-message">{{ session('success') }}</div>
    @endif
    <div class="hero text-center">
        <h1>Welcome to Mini Amazon</h1>
    </div>

    <div class="container my-5">
        <h3 class="mb-4">Featured Products</h3>
        <div class="row g-4">
            @for ($i = 0; $i < 6; $i++)
                <div class="col-md-4">
                <div class="card product-card">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Product {{ $i+1 }}</h5>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href="#" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
        </div>
        @endfor
    </div>
    </div>


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
        if (savedTheme) {
            applyTheme(savedTheme);
        } else {
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            applyTheme(systemDark ? 'dark' : 'light');
        }

        toggleBtn.addEventListener('click', () => {
            const currentTheme = htmlEl.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
        });
        setTimeout(function() {
            const alert = document.querySelector('.fade-message');
            if (alert) {
                alert.classList.add('fade');
                alert.classList.add('show');
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
