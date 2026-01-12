<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Industrial Spares, Supplies, Projects & Installations')">

    <title>@yield('title', 'Home') - {{ config('app.name', 'Nayon Engineering') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-left">
                <span><i class="fas fa-phone-alt"></i> +27 (0) 11 824 1059</span>
                <span><i class="fas fa-envelope"></i> info@nayon-engineering.co.za</span>
            </div>
            <div class="top-bar-right">
                <a href="{{ route('account') }}">My Account</a>
                <a href="{{ route('track-order') }}">Track Order</a>
                <a href="{{ route('contact') }}">Contact Us</a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="container">
            <!-- Logo -->
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/nayon-logo.png') }}" alt="Nayon Engineering" class="logo-img">
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Search Bar -->
            <div class="search-bar">
                <form action="{{ route('search') }}" method="GET">
                    <input type="text" name="q" placeholder="Search for products, brands, categories..." value="{{ request('q') }}">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <!-- Header Actions -->
            <div class="header-actions">
                <a href="{{ route('wishlist.index') }}" class="header-action">
                    <i class="far fa-heart"></i>
                    @if(count(session('wishlist', [])) > 0)
                        <span class="count">{{ count(session('wishlist', [])) }}</span>
                    @endif
                    <span class="action-text">Wishlist</span>
                </a>
                <a href="{{ route('cart.index') }}" class="header-action">
                    <i class="fas fa-shopping-cart"></i>
                    @php
                        $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
                    @endphp
                    @if($cartCount > 0)
                        <span class="count">{{ $cartCount }}</span>
                    @endif
                    <span class="action-text">Cart</span>
                </a>
                <a href="{{ route('account') }}" class="header-action">
                    <i class="far fa-user"></i>
                    <span class="action-text">Account</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Navigation -->
    <nav class="main-nav">
        <div class="container">
            <button class="nav-toggle" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="nav-menu">
                <!-- Categories Dropdown -->
                <li>
                    <a href="{{ route('products.index') }}" class="categories-btn">
                        <i class="fas fa-th-large"></i>
                        All Categories
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach(\App\Models\Category::active()->ordered()->get() as $category)
                        <li>
                            <a href="{{ route('products.index', ['category' => $category->id]) }}">
                                {{ $category->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>

                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a></li>
                <li>
                    <a href="{{ route('services') }}">Services <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('services') }}#switchgear">Switchgear</a></li>
                        <li><a href="{{ route('services') }}#electrical">Electrical Supplies</a></li>
                        <li><a href="{{ route('services') }}#projects">Project Management</a></li>
                        <li><a href="{{ route('services') }}#installations">Installations</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-content">
                <h3>Subscribe to Our Newsletter</h3>
                <p>Get the latest updates on new products and upcoming sales</p>
                <form class="newsletter-form" id="newsletterForm">
                    @csrf
                    <input type="email" name="email" placeholder="Enter your email address" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Company Info -->
                <div class="footer-col">
                    <h4>About Nayon Engineering</h4>
                    <p>Your trusted partner for industrial spares, electrical supplies, project management, and installations across Africa and globally.</p>
                    <p>We provide comprehensive solutions for power, agriculture, construction, and oil & gas industries.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('products.index') }}">Our Products</a></li>
                        <li><a href="{{ route('services') }}">Services</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li><a href="{{ route('careers') }}">Careers</a></li>
                        <li><a href="{{ route('faqs') }}">FAQs</a></li>
                    </ul>
                </div>

                <!-- Product Categories -->
                <div class="footer-col">
                    <h4>Product Categories</h4>
                    <ul>
                        @foreach(\App\Models\Category::active()->ordered()->limit(6)->get() as $category)
                        <li><a href="{{ route('products.index', ['category' => $category->id]) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-col">
                    <h4>Contact Us</h4>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Germiston, Johannesburg, South Africa</span>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span>+27 (0) 11 824 1059</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>info@nayon-engineering.co.za</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Mon - Fri: 8:00 AM - 5:00 PM</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; {{ date('Y') }} Nayon Engineering. All Rights Reserved. |
                    <a href="{{ route('privacy') }}">Privacy Policy</a> |
                    <a href="{{ route('terms') }}">Terms & Conditions</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Toast Container -->
    <div id="toastContainer"></div>

    <!-- JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
