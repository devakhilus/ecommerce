@extends('layout')

@section('content')
<div class="card shadow">
    <div class="card-body text-center">
        <h3>Welcome, {{ Auth::user()->name }}</h3>
        <p>You are logged in as <strong>{{ Auth::user()->is_admin ? 'Admin' : 'User' }}</strong>.</p>

        @if (Auth::user()->is_admin)
        <a href="/admin" class="btn btn-warning mt-2">Go to Admin Panel</a>
        @else
        <a href="/" class="btn btn-info mt-2">Go to User Dashboard</a>
        @endif

        <form method="POST" action="/logout" class="mt-4">
            @csrf
            <button class="btn btn-danger">Logout</button>
        </form>
    </div>
</div>
@endsection