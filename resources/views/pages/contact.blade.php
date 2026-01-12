@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Contact Us</span>
            </div>
            <h1 class="page-title">Contact Us</h1>
        </div>
    </div>

    <!-- Contact Section -->
    <section class="section">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px;">
                <!-- Contact Form -->
                <div>
                    <h2 style="margin-bottom: 20px;">Get In Touch</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        Have a question or need assistance? Fill out the form below and our team will get back to you as soon as possible.
                    </p>

                    @if(session('success'))
                        <div style="background-color: var(--success-green); color: white; padding: 15px 20px; border-radius: 4px; margin-bottom: 20px;">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div style="background-color: var(--danger-red); color: white; padding: 15px 20px; border-radius: 4px; margin-bottom: 20px;">
                            @foreach($errors->all() as $error)
                                <p><i class="fas fa-exclamation-circle"></i> {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 12px 15px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--text-light); font-size: 14px;">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email Address *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 12px 15px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--text-light); font-size: 14px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" style="width: 100%; padding: 12px 15px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--text-light); font-size: 14px;">
                            </div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Subject *</label>
                            <select name="subject" required style="width: 100%; padding: 12px 15px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--text-light); font-size: 14px;">
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Product Question">Product Question</option>
                                <option value="Quote Request">Quote Request</option>
                                <option value="Technical Support">Technical Support</option>
                                <option value="Order Status">Order Status</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Message *</label>
                            <textarea name="message" rows="6" required style="width: 100%; padding: 12px 15px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--text-light); font-size: 14px; resize: vertical;">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div>
                    <h2 style="margin-bottom: 20px;">Contact Information</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        You can also reach us through the following channels:
                    </p>

                    <div style="background-color: var(--card-bg); border-radius: 8px; padding: 30px; margin-bottom: 30px;">
                        @foreach([
                            ['icon' => 'fa-map-marker-alt', 'title' => 'Address', 'value' => 'Germiston, Johannesburg, South Africa'],
                            ['icon' => 'fa-phone-alt', 'title' => 'Phone', 'value' => '+27 (0) 11 824 1059'],
                            ['icon' => 'fa-envelope', 'title' => 'Email', 'value' => 'info@nayon-engineering.co.za'],
                            ['icon' => 'fa-clock', 'title' => 'Business Hours', 'value' => 'Mon - Fri: 8:00 AM - 5:00 PM']
                        ] as $contact)
                        <div style="display: flex; align-items: flex-start; gap: 20px; margin-bottom: 25px;">
                            <div style="width: 50px; height: 50px; background-color: var(--primary-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas {{ $contact['icon'] }}" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 5px;">{{ $contact['title'] }}</h4>
                                <p style="color: var(--light-gray);">{{ $contact['value'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Map Placeholder -->
                    <div style="background-color: var(--card-bg); border-radius: 8px; height: 300px; display: flex; align-items: center; justify-content: center;">
                        <div style="text-align: center; color: var(--light-gray);">
                            <i class="fas fa-map-marked-alt" style="font-size: 48px; margin-bottom: 15px;"></i>
                            <p>Map Location</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
