@extends('layout')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 85vh;">
    <div class="card shadow-sm border-0 p-4 w-100" style="max-width: 550px;">
        <div class="text-center">
            <h2 class="mb-3 text-primary fw-bold">📩 Verify Your Email</h2>
            <p class="text-muted mb-2">
                We've sent a verification link to your email. Please click it to activate your account.
            </p>
            <p class="text-muted">
                Didn’t get it? Check your spam folder or resend the link below.
            </p>
        </div>

        @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success mt-3 text-center">
            ✅ A new verification link has been sent to your email.
        </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-primary w-100">
                🔄 Resend Verification Email
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="mb-1">✅ Already verified?</p>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">
                🔓 Login Here
            </a>
        </div>
    </div>
</div>
@endsection