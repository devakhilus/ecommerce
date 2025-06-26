@extends('layouts.app')

@section('title', 'Welcome to Mini Amazon')

@push('styles')
<style>
    @include('partials.inline-style')
</style>
@endpush

@section('content')

@include('partials.cart')

<div class="hero text-center">
    <h1 class="mb-4">Welcome to Mini Amazon</h1>
    <div class="search-box">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for products or categories...">
    </div>
</div>

<div class="container my-5">
    <h3 class="mb-4 text-center">Featured Products</h3>

    {{-- Spinner shown during all fetch calls --}}
    <div class="text-center my-3" id="loading-spinner" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="row g-4" id="product-list"></div>

    <div class="text-center mt-4">
        <button id="load-more" class="btn btn-outline-primary">Load More</button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const BACKEND_URL = "{{ url('') }}";
</script>
<script src="{{ asset('js/cart.js') }}"></script>
<script src="{{ asset('js/product.js') }}"></script>
@endpush