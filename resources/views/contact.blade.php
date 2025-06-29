@extends('layout')

@section('content')
<section class="py-5 bg-light text-dark">
    <div class="container">
        <h1 class="mb-4 display-5 fw-bold text-primary">📬 Contact Us</h1>

        <p>If you have any questions, feedback, or need support, feel free to reach out using the form below or email us directly at <a href="mailto:myproject123@alwaysdata.net">myproject123@alwaysdata.net</a>.</p>

        <form method="POST" action="{{ route('contact.submit') }}" class="mt-4 bg-white p-4 rounded shadow-sm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Your Name</label>
                <input type="text" name="name" class="form-control" required placeholder="John Doe">
            </div>

            <div class="mb-3">
                <label class="form-label">Your Email</label>
                <input type="email" name="email" class="form-control" required placeholder="you@example.com">
            </div>

            <div class="mb-3">
                <label class="form-label">Your Message</label>
                <textarea name="message" rows="5" class="form-control" required placeholder="Type your message here..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">📤 Send Message</button>
        </form>
    </div>
</section>
@endsection