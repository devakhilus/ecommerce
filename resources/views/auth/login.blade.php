@extends('layout')

@section('title', 'Login')

@section('content')
<style>
    .form-control {
        font-size: 1rem;
        padding: 0.5rem 1rem;
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
    }
</style>

<div class="p-4">
    <h3 class="text-center mb-4 fw-bold">Admin Login</h3>

    <div class="alert alert-info text-center small rounded-3 mb-3">
        <strong>Project Hosting:</strong><br>
        🌐 Hosted on <strong>Vercel</strong> (PHP Runtime)<br>
        🛠️ Laravel Backend<br>
        🗄️ MySQL DB via AlwaysData.net <br>
        <strong>Demo Admin:</strong><br>
        Email: <code>admin@example.com</code><br>
        Password: <code>password</code>
    </div>

    @if (session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
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
        Don't have an account?
        <a href="{{ url('/register') }}">Register here</a>
    </p>
</div>
@endsection