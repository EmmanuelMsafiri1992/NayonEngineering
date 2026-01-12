@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('cart.index') }}">Cart</a>
                <span>/</span>
                <span>Checkout</span>
            </div>
            <h1 class="page-title">Checkout</h1>
        </div>
    </div>

    <!-- Checkout Section -->
    <section class="section">
        <div class="container">
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-hard-hat" style="font-size: 80px; color: var(--primary-blue); margin-bottom: 30px;"></i>
                <h2 style="margin-bottom: 20px;">Checkout Coming Soon</h2>
                <p style="color: var(--light-gray); margin-bottom: 30px; max-width: 500px; margin-left: auto; margin-right: auto;">
                    We're working on implementing a secure checkout experience. In the meantime, please contact us directly to complete your order.
                </p>

                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 30px; max-width: 500px; margin: 0 auto 30px; text-align: left;">
                    <h3 style="margin-bottom: 20px; text-align: center;">Contact Us to Order</h3>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <i class="fas fa-phone-alt" style="color: var(--primary-blue); font-size: 20px; width: 24px;"></i>
                        <span>+27 (0) 11 824 1059</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <i class="fas fa-envelope" style="color: var(--primary-blue); font-size: 20px; width: 24px;"></i>
                        <span>info@nayon-engineering.co.za</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary-blue); font-size: 20px; width: 24px;"></i>
                        <span>Germiston, Johannesburg, South Africa</span>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to Cart
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-primary">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
