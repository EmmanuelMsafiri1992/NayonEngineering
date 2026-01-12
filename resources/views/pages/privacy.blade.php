@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Privacy Policy</span>
            </div>
            <h1 class="page-title">Privacy Policy</h1>
        </div>
    </div>

    <!-- Content Section -->
    <section class="section">
        <div class="container">
            <div style="max-width: 800px; margin: 0 auto;">
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 40px;">
                    <p style="color: var(--light-gray); margin-bottom: 30px;">Last updated: {{ date('F d, Y') }}</p>

                    <h2 style="margin-bottom: 15px;">1. Information We Collect</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support. This may include your name, email address, phone number, and shipping address.
                    </p>

                    <h2 style="margin-bottom: 15px;">2. How We Use Your Information</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        We use the information we collect to process orders, communicate with you, improve our services, and send you updates about products and promotions (with your consent).
                    </p>

                    <h2 style="margin-bottom: 15px;">3. Information Sharing</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        We do not sell or rent your personal information to third parties. We may share your information with service providers who assist us in operating our business, such as payment processors and shipping companies.
                    </p>

                    <h2 style="margin-bottom: 15px;">4. Data Security</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.
                    </p>

                    <h2 style="margin-bottom: 15px;">5. Your Rights</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        You have the right to access, correct, or delete your personal information. You may also opt out of receiving marketing communications at any time.
                    </p>

                    <h2 style="margin-bottom: 15px;">6. Contact Us</h2>
                    <p style="color: var(--light-gray); margin-bottom: 20px;">
                        If you have any questions about this Privacy Policy, please contact us:
                    </p>
                    <ul style="color: var(--light-gray); padding-left: 20px;">
                        <li>Email: info@nayon-engineering.co.za</li>
                        <li>Phone: +27 (0) 11 824 1059</li>
                        <li>Address: Germiston, Johannesburg, South Africa</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
