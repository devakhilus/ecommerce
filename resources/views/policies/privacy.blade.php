@extends('layout')

@section('content')
<section class="py-5 bg-light text-dark">
    <div class="container">
        <h1 class="mb-4 display-5 fw-bold text-primary">🔒 Privacy Policy</h1>

        <p>At <strong>{{ config('app.name') }}</strong>, we value your privacy. This policy outlines how we collect, use, and protect your data when you visit <a href="{{ url('/') }}">{{ url('/') }}</a>.</p>

        <h4 class="mt-4">1. Information We Collect</h4>
        <p>We may collect personal information like your name, email, address, and phone number when you register or place an order.</p>

        <h4 class="mt-4">2. How We Use Your Data</h4>
        <ul>
            <li>To process and ship orders</li>
            <li>To send order updates and promotional emails</li>
            <li>To improve our website experience</li>
        </ul>

        <h4 class="mt-4">3. Third-party Services</h4>
        <p>We do not sell or trade your personal information. Data may be shared with trusted third parties for order fulfillment (e.g., delivery partners or payment gateways).</p>

        <h4 class="mt-4">4. Cookies</h4>
        <p>Our site uses cookies to improve usability. You can disable cookies in your browser settings.</p>

        <h4 class="mt-4">5. Contact Us</h4>
        <p>For any questions regarding this policy, please contact us at <a href="mailto:myproject123@alwaysdata.net">myproject123@alwaysdata.net</a>.</p>

        <p class="mt-5 text-muted">Last updated: {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
    </div>
</section>
@endsection