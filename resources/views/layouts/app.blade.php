<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', $siteSettings['seo_meta_description'] ?? 'Industrial Spares, Supplies, Projects & Installations')">
    <meta name="keywords" content="{{ $siteSettings['seo_meta_keywords'] ?? '' }}">
    @if(!($siteSettings['seo_index_site'] ?? true))
    <meta name="robots" content="noindex, nofollow">
    @endif

    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="@yield('og_title', $siteSettings['seo_og_title'] ?? $siteSettings['seo_site_title'] ?? config('app.name'))">
    <meta property="og:description" content="@yield('og_description', $siteSettings['seo_og_description'] ?? $siteSettings['seo_meta_description'] ?? '')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($siteSettings['seo_og_image'] ?? false)
    <meta property="og:image" content="{{ $siteSettings['seo_og_image'] }}">
    @endif
    @if($siteSettings['seo_fb_app_id'] ?? false)
    <meta property="fb:app_id" content="{{ $siteSettings['seo_fb_app_id'] }}">
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    @if($siteSettings['seo_twitter_handle'] ?? false)
    <meta name="twitter:site" content="{{ $siteSettings['seo_twitter_handle'] }}">
    @endif

    <!-- Search Console Verification -->
    @if($siteSettings['seo_google_verification'] ?? false)
    <meta name="google-site-verification" content="{{ $siteSettings['seo_google_verification'] }}">
    @endif
    @if($siteSettings['seo_bing_verification'] ?? false)
    <meta name="msvalidate.01" content="{{ $siteSettings['seo_bing_verification'] }}">
    @endif

    <title>@yield('title', 'Home') - {{ $siteSettings['seo_site_title'] ?? config('app.name', 'Nayon Engineering') }}</title>

    <!-- Favicon -->
    @if($siteSettings['site_favicon'] ?? false)
    <link rel="icon" type="image/x-icon" href="{{ asset($siteSettings['site_favicon']) }}">
    @else
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Dynamic Colors -->
    <style>
        :root {
            --primary: {{ $siteSettings['color_primary'] ?? '#0079C1' }};
            --secondary: {{ $siteSettings['color_secondary'] ?? '#FF6B00' }};
            --header-bg: {{ $siteSettings['color_header_bg'] ?? '#FFFFFF' }};
            --footer-bg: {{ $siteSettings['color_footer_bg'] ?? '#1a1a1a' }};
            --topbar-bg: {{ $siteSettings['topbar_bg_color'] ?? '#333333' }};
        }
    </style>

    <!-- Custom CSS -->
    @if($siteSettings['custom_css'] ?? false)
    <style>
        {!! $siteSettings['custom_css'] !!}
    </style>
    @endif

    <!-- Google Analytics -->
    @if($siteSettings['seo_google_analytics'] ?? false)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings['seo_google_analytics'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $siteSettings['seo_google_analytics'] }}');
    </script>
    @endif

    <!-- Custom Head Scripts -->
    {!! $siteSettings['seo_head_scripts'] ?? '' !!}

    @stack('styles')
</head>
<body>
    <!-- Announcement Bar -->
    @if($siteSettings['header_announcement'] ?? false)
    <div class="announcement-bar" style="background: var(--secondary); color: white; text-align: center; padding: 8px 15px; font-size: 14px;">
        @if($siteSettings['header_announcement_link'] ?? false)
        <a href="{{ $siteSettings['header_announcement_link'] }}" style="color: white; text-decoration: none;">
            {{ $siteSettings['header_announcement'] }}
        </a>
        @else
        {{ $siteSettings['header_announcement'] }}
        @endif
    </div>
    @endif

    <!-- Top Bar -->
    @if($siteSettings['topbar_enabled'] ?? true)
    <div class="top-bar" style="background: var(--topbar-bg);">
        <div class="container">
            <div class="top-bar-left">
                <span><i class="fas fa-phone-alt"></i> {{ $siteSettings['topbar_phone'] ?? $siteSettings['site_phone'] ?? '+27 (0) 11 824 1059' }}</span>
                <span><i class="fas fa-envelope"></i> {{ $siteSettings['topbar_email'] ?? $siteSettings['site_email'] ?? 'info@nayon-engineering.co.za' }}</span>
            </div>
            <div class="top-bar-right">
                @include('partials.language-switcher')
                <a href="{{ route('account') }}">{{ __('messages.account') }}</a>
                <a href="{{ route('track-order') }}">{{ __('messages.track_order') }}</a>
                <a href="{{ route('contact') }}">{{ __('messages.contact') }}</a>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Header -->
    <header class="main-header" style="background: var(--header-bg);">
        <div class="container">
            <!-- Logo -->
            <div class="logo">
                <a href="{{ route('home') }}">
                    @if($siteSettings['site_logo_url'] ?? false)
                        <img src="{{ $siteSettings['site_logo_url'] }}" alt="{{ $siteSettings['seo_site_title'] ?? 'Site Logo' }}" class="logo-img">
                    @elseif($siteSettings['site_logo'] ?? false)
                        <img src="{{ asset($siteSettings['site_logo']) }}" alt="{{ $siteSettings['seo_site_title'] ?? 'Site Logo' }}" class="logo-img">
                    @else
                        <img src="{{ asset('images/nayon-logo.png') }}" alt="Nayon Engineering" class="logo-img">
                    @endif
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Search Bar -->
            <div class="search-bar">
                <form action="{{ route('search') }}" method="GET">
                    <input type="text" name="q" placeholder="{{ $siteSettings['header_search_placeholder'] ?? __('messages.search_placeholder') }}" value="{{ request('q') }}">
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
                    <span class="action-text">{{ __('messages.wishlist') }}</span>
                </a>
                <a href="{{ route('cart.index') }}" class="header-action">
                    <i class="fas fa-shopping-cart"></i>
                    @php
                        $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
                    @endphp
                    @if($cartCount > 0)
                        <span class="count">{{ $cartCount }}</span>
                    @endif
                    <span class="action-text">{{ __('messages.cart') }}</span>
                </a>
                <a href="{{ route('account') }}" class="header-action">
                    <i class="far fa-user"></i>
                    <span class="action-text">{{ __('messages.account') }}</span>
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
                        {{ __('messages.all_categories') }}
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

                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('messages.home') }}</a></li>
                <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">{{ __('messages.products') }}</a></li>
                <li>
                    <a href="{{ route('services') }}">{{ __('messages.services') }} <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('services') }}#switchgear">{{ __('messages.switchgear') }}</a></li>
                        <li><a href="{{ route('services') }}#electrical">{{ __('messages.electrical_supplies') }}</a></li>
                        <li><a href="{{ route('services') }}#projects">{{ __('messages.project_management') }}</a></li>
                        <li><a href="{{ route('services') }}#installations">{{ __('messages.installations') }}</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __('messages.about') }}</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">{{ __('messages.contact') }}</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Newsletter Section -->
    @if($siteSettings['newsletter_enabled'] ?? true)
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-content">
                <h3>{{ $siteSettings['newsletter_title'] ?? __('messages.newsletter') }}</h3>
                <p>{{ $siteSettings['newsletter_description'] ?? 'Get the latest updates on new products and upcoming sales' }}</p>
                <form class="newsletter-form" id="newsletterForm">
                    @csrf
                    <input type="email" name="email" placeholder="Enter your email address" required>
                    <button type="submit">{{ $siteSettings['newsletter_button'] ?? __('messages.subscribe') }}</button>
                </form>
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="footer" style="background: var(--footer-bg);">
        <div class="container">
            <div class="footer-grid">
                <!-- Company Info -->
                <div class="footer-col">
                    <h4>{{ __('messages.about') }} {{ $siteSettings['seo_site_title'] ?? config('app.name') }}</h4>
                    <p>{{ $siteSettings['footer_about'] ?? 'Your trusted partner for industrial spares, electrical supplies, project management, and installations across Africa and globally.' }}</p>
                    @if($siteSettings['social_show_footer'] ?? true)
                    <div class="social-links">
                        @if($siteSettings['social_facebook'] ?? false)
                        <a href="{{ $siteSettings['social_facebook'] }}" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if($siteSettings['social_twitter'] ?? false)
                        <a href="{{ $siteSettings['social_twitter'] }}" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if($siteSettings['social_linkedin'] ?? false)
                        <a href="{{ $siteSettings['social_linkedin'] }}" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                        @if($siteSettings['social_instagram'] ?? false)
                        <a href="{{ $siteSettings['social_instagram'] }}" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if($siteSettings['social_youtube'] ?? false)
                        <a href="{{ $siteSettings['social_youtube'] }}" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        @endif
                        @if($siteSettings['social_whatsapp'] ?? false)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['social_whatsapp']) }}" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Quick Links -->
                <div class="footer-col">
                    <h4>{{ __('messages.quick_links') }}</h4>
                    <ul>
                        <li><a href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                        <li><a href="{{ route('products.index') }}">{{ __('messages.products') }}</a></li>
                        <li><a href="{{ route('services') }}">{{ __('messages.services') }}</a></li>
                        <li><a href="{{ route('contact') }}">{{ __('messages.contact') }}</a></li>
                        <li><a href="{{ route('careers') }}">{{ __('messages.careers') }}</a></li>
                        <li><a href="{{ route('faqs') }}">{{ __('messages.faqs') }}</a></li>
                    </ul>
                </div>

                <!-- Product Categories -->
                <div class="footer-col">
                    <h4>{{ __('messages.categories') }}</h4>
                    <ul>
                        @foreach(\App\Models\Category::active()->ordered()->limit(6)->get() as $category)
                        <li><a href="{{ route('products.index', ['category' => $category->id]) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-col">
                    <h4>{{ __('messages.contact') }}</h4>
                    <ul class="footer-contact">
                        @if($siteSettings['footer_address'] ?? $siteSettings['site_address'] ?? false)
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{!! nl2br(e($siteSettings['footer_address'] ?? $siteSettings['site_address'])) !!}</span>
                        </li>
                        @endif
                        @if($siteSettings['footer_phones'] ?? $siteSettings['site_phone'] ?? false)
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span>{!! nl2br(e($siteSettings['footer_phones'] ?? $siteSettings['site_phone'])) !!}</span>
                        </li>
                        @endif
                        @if($siteSettings['footer_emails'] ?? $siteSettings['site_email'] ?? false)
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>{!! nl2br(e($siteSettings['footer_emails'] ?? $siteSettings['site_email'])) !!}</span>
                        </li>
                        @endif
                        @if($siteSettings['footer_hours'] ?? $siteSettings['business_hours'] ?? false)
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>{!! nl2br(e($siteSettings['footer_hours'] ?? $siteSettings['business_hours'])) !!}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; {{ date('Y') }} {{ $siteSettings['footer_copyright'] ?? ($siteSettings['seo_site_title'] ?? config('app.name')) . '. ' . __('messages.copyright') }} |
                    <a href="{{ route('privacy') }}">{{ __('messages.privacy_policy') }}</a> |
                    <a href="{{ route('terms') }}">{{ __('messages.terms_conditions') }}</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Toast Container -->
    <div id="toastContainer"></div>

    <!-- JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')

    <!-- Custom Footer Scripts -->
    {!! $siteSettings['seo_footer_scripts'] ?? '' !!}
</body>
</html>
