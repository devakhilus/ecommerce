<nav class="main-header navbar navbar-expand navbar-dark" style="background-color:#232f3e;">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/" class="nav-link">Home</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="{{ auth()->check() ? '/cart' : '/login' }}">
                <i class="fas fa-shopping-cart"></i>
                <span class="badge badge-danger navbar-badge">{{ session('cart_count', 0) }}</span>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="/profile" class="dropdown-item"><i class="fas fa-user"></i> Profile</a>
                <div class="dropdown-divider"></div>
                <a href="/logout" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </li>
    </ul>
</nav>