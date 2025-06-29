@extends('layout')

@section('content')
<section class="py-5 bg-light text-dark">
    <div class="container">
        <h1 class="mb-4 display-5 fw-bold text-primary">📜 Terms & Conditions</h1>

        <p>Welcome to <strong>{{ config('app.name') }}</strong>! By using our website <a href="{{ url('/') }}">{{ url('/') }}</a>, you agree to the following terms and conditions:</p>

        <h4 class="mt-4">1. General</h4>
        <p>These terms govern your use of our platform, including browsing, ordering, and accessing services.</p>

        <h4 class="mt-4">2. User Responsibilities</h4>
        <ul>
            <li>Provide accurate account and order information</li>
            <li>Do not misuse the website or engage in fraudulent activities</li>
            <li>Respect intellectual property rights and copyrights</li>
        </ul>

        <h4 class="mt-4">3. Order Acceptance</h4>
        <p>We reserve the right to accept, cancel, or modify any order at our discretion, with or without notice.</p>

        <h4 class="mt-4">4. Changes to Terms</h4>
        <p>We may update these terms periodically. Continued use constitutes acceptance of those changes.</p>

        <p class="mt-5 text-muted">Last updated: {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
    </div>
</section>
@endsection