@extends('layouts.app')

@section('title', 'Track Order')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Track Order</span>
            </div>
            <h1 class="page-title">Track Your Order</h1>
        </div>
    </div>

    <!-- Content Section -->
    <section class="section">
        <div class="container">
            <div style="max-width: 600px; margin: 0 auto;">
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 40px; text-align: center;">
                    <i class="fas fa-shipping-fast" style="font-size: 60px; color: var(--primary-blue); margin-bottom: 25px;"></i>
                    <h2 style="margin-bottom: 15px;">Order Tracking</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">
                        Enter your order number and email address to track your order status.
                    </p>

                    <form style="text-align: left;">
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Order Number *</label>
                            <input type="text" placeholder="e.g., ORD-2024-001234" style="width: 100%; padding: 12px 15px; background-color: var(--darker-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--text-light); font-size: 14px;">
                        </div>
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email Address *</label>
                            <input type="email" placeholder="Email used for the order" style="width: 100%; padding: 12px 15px; background-color: var(--darker-bg); border: 1px solid var(--border-color); border-radius: 4px; color: var(--text-light); font-size: 14px;">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;" disabled>
                            <i class="fas fa-search"></i> Track Order
                        </button>
                    </form>

                    <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--border-color);">
                        <p style="color: var(--light-gray); font-size: 14px; margin-bottom: 15px;">
                            <i class="fas fa-info-circle"></i> Order tracking is coming soon. For now, please contact us directly for order updates.
                        </p>
                        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                            <a href="tel:+27118241059" class="btn btn-outline" style="font-size: 14px;">
                                <i class="fas fa-phone"></i> Call Us
                            </a>
                            <a href="{{ route('contact') }}" class="btn btn-outline" style="font-size: 14px;">
                                <i class="fas fa-envelope"></i> Email Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
