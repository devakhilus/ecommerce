<nav class="navbar navbar-expand-lg sticky-top" style="background-color: #232f3e;">
    <div class="container">
        <a class="navbar-brand text-white" href="/">MiniAmazon</a>
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