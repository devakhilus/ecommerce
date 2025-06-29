<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            position: relative;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: var(--bs-card-bg);
            padding: 2rem;
        }

        @media (min-width: 992px) {
            .auth-card {
                max-width: 500px;
            }
        }

        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <button id="theme-toggle" class="btn btn-sm btn-outline-secondary theme-toggle">
            <span id="theme-icon">🌙</span>
        </button>
        <div class="auth-card">
            @yield('content')
            @if(session('success'))
            <div class="alert alert-success text-center my-3 container">
                {{ session('success') }}
            </div>
            @endif

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
    </script>
</body>

</html>