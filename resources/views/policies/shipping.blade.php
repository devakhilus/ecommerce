@extends('layout')

@section('content')
<section class="py-5 bg-light text-dark">
    <div class="container">
        <h1 class="mb-4 display-5 fw-bold text-primary">🚚 Shipping & Delivery Policy</h1>

        <p>At <strong>{{ config('app.name') }}</strong>, we ensure fast and secure delivery for all orders placed on <a href="{{ url('/') }}">{{ url('/') }}</a>.</p>

        <h4 class="mt-4">1. Dispatch & Processing</h4>
        <p>Orders are usually processed within 1–3 working days. Processing time may vary during holidays or sales.</p>

        <h4 class="mt-4">2. Delivery Timeline</h4>
        <ul>
            <li>Standard delivery: 4–7 business days</li>
            <li>Remote areas may take longer</li>
        </ul>

        <h4 class="mt-4">3. Tracking</h4>
        <p>You will receive a tracking ID via email or SMS once your order is shipped.</p>

        <h4 class="mt-4">4. Shipping Charges</h4>
        <p>Shipping charges (if any) will be displayed at checkout before payment.</p>

        <p class="mt-5 text-muted">Last updated: {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
    </div>
</section>
@endsection