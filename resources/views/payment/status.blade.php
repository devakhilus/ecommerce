@extends('layout')

@section('content')
<div class="container my-5">
    <h3 class="mb-4 text-success">✅ Payment Successful</h3>
    <p>Your order ID: <strong>{{ $order_id }}</strong></p>
    <a href="/" class="btn btn-primary mt-3">Back to Home</a>
</div>
@endsection