@extends('layout')

@section('content')
<section class="py-5 bg-light text-dark">
    <div class="container">
        <h1 class="mb-4 display-5 fw-bold text-primary">💸 Cancellation & Refund Policy</h1>

        <p>We aim to offer a smooth and fair refund experience at <strong>{{ config('app.name') }}</strong>.</p>

        <h4 class="mt-4">1. Order Cancellation</h4>
        <p>You may cancel your order within 2 hours of placing it. Once shipped, the order cannot be canceled.</p>

        <h4 class="mt-4">2. Refund Eligibility</h4>
        <ul>
            <li>Refunds are available for items that are defective, damaged, or incorrectly delivered</li>
            <li>No refunds on digital items or services once delivered</li>
        </ul>

        <h4 class="mt-4">3. Refund Process</h4>
        <p>If eligible, refunds will be processed within 5–7 business days to the original payment method.</p>

        <p class="mt-5 text-muted">Last updated: {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
    </div>
</section>
@endsection