@extends('layouts.app')

@section('title', 'Terms & Conditions')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Terms & Conditions</span>
            </div>
            <h1 class="page-title">Terms & Conditions</h1>
        </div>
    </div>

    <!-- Content Section -->
    <section class="section">
        <div class="container">
            <div style="max-width: 800px; margin: 0 auto;">
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 40px;">
                    <p style="color: var(--light-gray); margin-bottom: 30px;">Last updated: {{ date('F d, Y') }}</p>

                    <h2 style="margin-bottom: 15px;">1. Acceptance of Terms</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        By accessing and using this website, you accept and agree to be bound by the terms and conditions of this agreement. If you do not agree to these terms, please do not use this website.
                    </p>

                    <h2 style="margin-bottom: 15px;">2. Products and Services</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        All products and services displayed on this website are subject to availability. We reserve the right to limit quantities and discontinue products without notice. Prices are subject to change without prior notification.
                    </p>

                    <h2 style="margin-bottom: 15px;">3. Orders and Payment</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        All orders are subject to acceptance and availability. We reserve the right to refuse any order. Payment must be received in full before goods are dispatched. We accept various payment methods as indicated during checkout.
                    </p>

                    <h2 style="margin-bottom: 15px;">4. Shipping and Delivery</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        Delivery times are estimates only and are not guaranteed. We are not responsible for delays caused by shipping carriers or circumstances beyond our control. Risk of loss passes to you upon delivery.
                    </p>

                    <h2 style="margin-bottom: 15px;">5. Returns and Refunds</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        Products may be returned within 14 days of receipt if unused and in original packaging. Refunds will be processed within 7-10 business days after receipt of returned items. Shipping costs are non-refundable.
                    </p>

                    <h2 style="margin-bottom: 15px;">6. Warranty</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        Products are covered by manufacturer warranties where applicable. We do not provide additional warranties beyond those offered by manufacturers. Warranty claims should be directed to the relevant manufacturer.
                    </p>

                    <h2 style="margin-bottom: 15px;">7. Limitation of Liability</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        Nayon Engineering shall not be liable for any indirect, incidental, special, or consequential damages arising from the use of our products or services.
                    </p>

                    <h2 style="margin-bottom: 15px;">8. Contact Information</h2>
                    <p style="color: var(--light-gray); margin-bottom: 20px;">
                        For questions regarding these terms, please contact us:
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
