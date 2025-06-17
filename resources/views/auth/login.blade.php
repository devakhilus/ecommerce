@extends('layout')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <h3 class="mb-4 text-center">Login</h3>

        {{-- Demo credentials for presentation --}}
        <div class="alert alert-info text-center">
            <strong>Admin Demo Login:</strong><br>
            <span>Email:</span> <code>admin@example.com</code><br>
            <span>Password:</span> <code>password</code>
        </div>

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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

        <form method="POST" action="/login">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">Login</button>
        </form>

        <p class="mt-3 text-center">
            Don't have an account? <a href="/register">Register here</a>
        </p>
    </div>
</div>
@endsection
