@extends('layout')

@section('title', 'Login')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">

                    <h3 class="mb-4 text-center">Login</h3>

                    {{-- Project Hosting Info --}}
                    <div class="alert alert-secondary text-center">
                        <strong>Project Hosting Info:</strong><br>
                        🌐 Hosted on <strong>Vercel</strong> using <strong>PHP Runtime</strong><br>
                        🛠️ Backend: <strong>Laravel</strong><br>
                        🗄️ Database: <strong>MySQL (AlwaysData.net)</strong>
                    </div>

                    {{-- Demo credentials for presentation --}}
                    <div class="alert alert-info text-center">
                        <strong>Admin Demo Login</strong><br>
                        <span>Email:</span> <code>admin@example.com</code><br>
                        <span>Password:</span> <code>password</code>
                    </div>

                    {{-- Session Success --}}
                    @if (session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
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
                    <form method="POST" action="{{ url('/login') }}">
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
                        Don’t have an account? <a href="{{ url('/register') }}">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
