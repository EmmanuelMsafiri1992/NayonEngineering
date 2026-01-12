@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-slider">
                <div class="hero-slide">
                    <div class="hero-content">
                        <h1>Industrial <span>Electrical</span> Solutions</h1>
                        <p>Your trusted partner for quality electrical products, industrial spares, and comprehensive project solutions across Africa and globally.</p>
                        <div class="hero-buttons">
                            <a href="{{ route('products.index') }}" class="btn btn-primary">Shop Now</a>
                            <a href="{{ route('contact') }}" class="btn btn-outline">Get a Quote</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Fast Delivery</h4>
                        <p>Quick nationwide delivery on all orders</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Quality Guaranteed</h4>
                        <p>All products backed by warranty</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Expert Support</h4>
                        <p>Technical assistance available</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Best Prices</h4>
                        <p>Competitive pricing on all products</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Shop by <span>Category</span></h2>
                <a href="{{ route('products.index') }}" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="category-grid">
                @php
                    $categoryIcons = [
                        1 => 'fas fa-bell',
                        2 => 'fas fa-cogs',
                        3 => 'fas fa-bolt',
                        4 => 'fas fa-box',
                        5 => 'fas fa-lightbulb',
                        6 => 'fas fa-car-battery',
                        7 => 'fas fa-solar-panel',
                        8 => 'fas fa-plug',
                        9 => 'fas fa-tools',
                        10 => 'fas fa-water'
                    ];
                @endphp

                @foreach($categories->take(10) as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-card">
                    <div class="category-icon">
                        <i class="{{ $categoryIcons[$category->id] ?? 'fas fa-folder' }}"></i>
                    </div>
                    <h3 class="category-name">{{ $category->name }}</h3>
                    <span class="category-count">{{ $category->products()->count() }} Products</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Top <span>Products</span></h2>
                <a href="{{ route('products.index') }}" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- Product Tabs -->
            <div class="product-tabs">
                <button class="product-tab active" data-tab="specials">Specials</button>
                <button class="product-tab" data-tab="bestsellers">Best Sellers</button>
                <button class="product-tab" data-tab="new">New Products</button>
            </div>

            <!-- Specials Tab -->
            <div class="tab-content active" id="specials">
                <div class="product-grid">
                    @foreach($featuredProducts as $product)
                        @include('partials.product-card', ['product' => $product, 'showBadge' => true])
                    @endforeach
                </div>
            </div>

            <!-- Best Sellers Tab -->
            <div class="tab-content" id="bestsellers" style="display: none;">
                <div class="product-grid">
                    @foreach($bestSellers as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>

            <!-- New Products Tab -->
            <div class="tab-content" id="new" style="display: none;">
                <div class="product-grid">
                    @foreach($newArrivals as $product)
                        @include('partials.product-card', ['product' => $product, 'isNew' => true])
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <section class="brands-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our <span>Brands</span></h2>
            </div>
            <div class="brands-slider">
                <div class="brand-item">
                    <img src="https://via.placeholder.com/150x50?text=3M" alt="3M">
                </div>
                <div class="brand-item">
                    <img src="https://via.placeholder.com/150x50?text=ACDC" alt="ACDC">
                </div>
                <div class="brand-item">
                    <img src="https://via.placeholder.com/150x50?text=Energizer" alt="Energizer">
                </div>
                <div class="brand-item">
                    <img src="https://via.placeholder.com/150x50?text=Citiq" alt="Citiq">
                </div>
                <div class="brand-item">
                    <img src="https://via.placeholder.com/150x50?text=Victron" alt="Victron">
                </div>
                <div class="brand-item">
                    <img src="https://via.placeholder.com/150x50?text=Legrand" alt="Legrand">
                </div>
            </div>
        </div>
    </section>
@endsection
