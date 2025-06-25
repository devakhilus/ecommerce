@extends('admin.master')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <h1>Order #{{ $order->id }}</h1>

    <div class="mb-3"><strong>User:</strong> {{ $order->user->name }} ({{ $order->user->email }})</div>
    <div class="mb-3"><strong>Total:</strong> ₹{{ $order->total }}</div>
    <div class="mb-3"><strong>Status:</strong> {{ $order->status }}</div>
    <div class="mb-3"><strong>Coupon:</strong> {{ $order->coupon?->code ?? 'None' }}</div>
    <div><strong>Placed At:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</div>
</div>
@endsection