@extends('layout')

@section('content')
<div class="container my-5 text-center">
    <h3 class="mb-4 text-success">✅ Payment Successful</h3>
    <p>Your order ID: <strong>{{ $order_id }}</strong></p>
    <p>Thank you! Your cart has been cleared.</p>
    <p class="text-muted mt-3">Redirecting you to the homepage in 3 seconds...</p>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 🧹 Clear localStorage
        localStorage.removeItem('cart');
        localStorage.removeItem('checkoutData');

        // 🛡️ Prevent browser back to resubmit
        history.replaceState({}, document.title, "{{ url()->current() }}");

        // 🕒 Auto redirect to homepage after 3 seconds
        setTimeout(function() {
            window.location.href = "/";
        }, 3000);
    });
</script>
@endsection