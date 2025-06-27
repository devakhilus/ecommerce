@extends('layout')

@section('content')
<div class="container my-5">
    <h3 class="mb-4">Email Verification Required</h3>

    @if (session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
    @endif

    @if (session('status') == 'verification-link-sent')
    <div class="alert alert-success">
        A new verification link has been sent to your email address.
    </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Resend Verification Email</button>
    </form>

    @if (session('email'))
    <p class="mt-3 text-muted">Email: {{ session('email') }}</p>
    @endif

    <p class="mt-4">
        Already verified? <a href="{{ route('login') }}">Login here</a>
    </p>
</div>
@endsection