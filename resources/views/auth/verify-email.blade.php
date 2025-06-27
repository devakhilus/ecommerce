@extends('layout')

@section('content')
<div class="container my-5">
    <h2 class="mb-3">Verify Your Email Address</h2>

    @if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <p>Before accessing this site, please check your email for a verification link.</p>
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="btn btn-primary mt-3">Resend Verification Email</button>
    </form>
</div>
@endsection