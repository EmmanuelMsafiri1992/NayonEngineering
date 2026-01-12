@extends('layouts.app')

@section('title', 'FAQs')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>FAQs</span>
            </div>
            <h1 class="page-title">Frequently Asked Questions</h1>
        </div>
    </div>

    <!-- Content Section -->
    <section class="section">
        <div class="container">
            <div style="max-width: 800px; margin: 0 auto;">
                @php
                $faqs = [
                    [
                        'question' => 'What products does Nayon Engineering supply?',
                        'answer' => 'We supply a wide range of electrical products including switchgear, circuit breakers, cables, lighting solutions, solar equipment, batteries, and various electrical accessories from leading brands.'
                    ],
                    [
                        'question' => 'Do you offer bulk/wholesale pricing?',
                        'answer' => 'Yes, we offer competitive pricing for bulk orders and wholesale customers. Please contact our sales team for a custom quote based on your requirements.'
                    ],
                    [
                        'question' => 'What areas do you deliver to?',
                        'answer' => 'We deliver throughout South Africa. Delivery times vary based on location. Contact us for specific delivery information to your area.'
                    ],
                    [
                        'question' => 'How can I track my order?',
                        'answer' => 'Once your order is dispatched, you will receive a tracking number via email. You can use this to track your delivery through our shipping partner\'s website.'
                    ],
                    [
                        'question' => 'What payment methods do you accept?',
                        'answer' => 'We accept EFT (Electronic Fund Transfer), credit cards, and cash on collection. For large orders, we can arrange payment terms for approved business accounts.'
                    ],
                    [
                        'question' => 'Do you provide installation services?',
                        'answer' => 'Yes, we offer professional installation services for electrical equipment. Our qualified technicians can handle installations of various scales. Contact us for a quote.'
                    ],
                    [
                        'question' => 'What is your return policy?',
                        'answer' => 'Products can be returned within 14 days of purchase if unused and in original packaging. Please refer to our Terms & Conditions for full details on returns and refunds.'
                    ],
                    [
                        'question' => 'Do products come with warranties?',
                        'answer' => 'Yes, all products come with manufacturer warranties. Warranty periods vary by product and brand. Please check individual product pages or contact us for specific warranty information.'
                    ],
                    [
                        'question' => 'Can I visit your showroom?',
                        'answer' => 'Yes, you\'re welcome to visit our premises in Germiston, Johannesburg. Our business hours are Monday to Friday, 8:00 AM to 5:00 PM.'
                    ],
                    [
                        'question' => 'How do I get a quote for a project?',
                        'answer' => 'You can request a quote by contacting us via phone, email, or through our contact form. Please provide as much detail as possible about your project requirements.'
                    ]
                ];
                @endphp

                @foreach($faqs as $index => $faq)
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 25px; margin-bottom: 15px;">
                    <h3 style="margin-bottom: 15px; display: flex; align-items: flex-start; gap: 15px;">
                        <span style="background-color: var(--primary-blue); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">{{ $index + 1 }}</span>
                        {{ $faq['question'] }}
                    </h3>
                    <p style="color: var(--light-gray); padding-left: 45px;">{{ $faq['answer'] }}</p>
                </div>
                @endforeach

                <div style="text-align: center; margin-top: 40px; padding: 30px; background-color: var(--card-bg); border-radius: 8px;">
                    <h3 style="margin-bottom: 15px;">Still have questions?</h3>
                    <p style="color: var(--light-gray); margin-bottom: 20px;">Our team is here to help. Contact us and we'll get back to you as soon as possible.</p>
                    <a href="{{ route('contact') }}" class="btn btn-primary">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
