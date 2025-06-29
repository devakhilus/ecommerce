<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <!-- Policy Links -->
        <div class="mb-2">
            <a href="{{ route('privacy') }}" class="text-white text-decoration-none me-3">Privacy Policy</a>
            <a href="{{ route('terms') }}" class="text-white text-decoration-none me-3">Terms & Conditions</a>
            <a href="{{ route('refund') }}" class="text-white text-decoration-none me-3">Refund Policy</a>
            <a href="{{ route('shipping') }}" class="text-white text-decoration-none me-3">Shipping Info</a>
            <a href="{{ route('contact.show') }}" class="text-white text-decoration-none">Contact Us</a>
        </div>

        <!-- Footer Bottom Line -->
        <div class="text-white small mt-2">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</footer>