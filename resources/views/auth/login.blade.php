@extends('layout')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow rounded-4">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4 fw-bold">Admin Login</h3>

                    {{-- Project Hosting Info --}}
                    <div class="alert alert-secondary rounded-3 text-center small mb-4">
                        <strong>Project Info:</strong><br>
                        🌐 Hosted on <strong>Vercel</strong> (PHP Runtime)<br>
                        🛠️ Backend: <strong>Laravel</strong><br>
                        🗄️ DB: <strong>MySQL @ AlwaysData.net</strong>
                    </div>

                    {{-- Demo Credentials --}}
                    <div class="alert alert-info rounded-3 text-center small mb-4">
                        <strong>Demo Credentials:</strong><br>
                        <span>Email:</span> <code>admin@example.com</code><br>
                        <span>Password:</span> <code>password</code>
                    </div>

                    {{-- Session Success --}}
                    @if (session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Login Form --}}
                    <form method="POST" action="{{ url('/login') }}" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <p class="text-center mt-3 mb-0">
                        Don’t have an account?
                        <a href="{{ url('/register') }}">Register here</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
