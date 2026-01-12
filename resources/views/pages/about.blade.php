@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>About Us</span>
            </div>
            <h1 class="page-title">About Us</h1>
        </div>
    </div>

    <!-- About Section -->
    <section class="section">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; margin-bottom: 60px;">
                <div>
                    <h2 style="font-size: 32px; margin-bottom: 20px;">Welcome to <span style="color: var(--primary-blue);">Nayon Engineering</span></h2>
                    <p style="color: var(--light-gray); line-height: 1.8; margin-bottom: 20px;">
                        Nayon Engineering is your trusted partner for industrial spares, electrical supplies, project management, and installations across Africa and globally.
                    </p>
                    <p style="color: var(--light-gray); line-height: 1.8; margin-bottom: 20px;">
                        Our comprehensive range of products includes everything from basic electrical components to advanced automation systems, ensuring that we can meet all your industrial and electrical needs under one roof.
                    </p>
                    <p style="color: var(--light-gray); line-height: 1.8;">
                        We pride ourselves on offering competitive prices, quality products from trusted brands, and exceptional customer service.
                    </p>
                </div>
                <div style="background-color: var(--card-bg); border-radius: 8px; height: 400px; display: flex; align-items: center; justify-content: center;">
                    <div style="text-align: center; color: var(--light-gray);">
                        <i class="fas fa-building" style="font-size: 80px; margin-bottom: 20px; color: var(--primary-blue);"></i>
                        <p>Company Image</p>
                    </div>
                </div>
            </div>

            <!-- Mission, Vision, Values -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 60px;">
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 40px 30px; text-align: center;">
                    <div style="width: 80px; height: 80px; background-color: var(--primary-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-bullseye" style="font-size: 36px;"></i>
                    </div>
                    <h3 style="margin-bottom: 15px;">Our Mission</h3>
                    <p style="color: var(--light-gray); line-height: 1.7;">
                        To provide quality electrical products and services that empower industries across Africa and beyond.
                    </p>
                </div>
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 40px 30px; text-align: center;">
                    <div style="width: 80px; height: 80px; background-color: var(--primary-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-eye" style="font-size: 36px;"></i>
                    </div>
                    <h3 style="margin-bottom: 15px;">Our Vision</h3>
                    <p style="color: var(--light-gray); line-height: 1.7;">
                        To be the leading supplier of industrial electrical products and services in Africa.
                    </p>
                </div>
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 40px 30px; text-align: center;">
                    <div style="width: 80px; height: 80px; background-color: var(--primary-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-heart" style="font-size: 36px;"></i>
                    </div>
                    <h3 style="margin-bottom: 15px;">Our Values</h3>
                    <p style="color: var(--light-gray); line-height: 1.7;">
                        Integrity, quality, innovation, and customer focus drive everything we do.
                    </p>
                </div>
            </div>

            <!-- Industries We Serve -->
            <div style="margin-bottom: 60px;">
                <h2 style="font-size: 28px; margin-bottom: 30px; text-align: center;">Industries We <span style="color: var(--primary-blue);">Serve</span></h2>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                    @foreach(['Power & Energy' => 'fa-bolt', 'Agriculture' => 'fa-tractor', 'Construction' => 'fa-hard-hat', 'Oil & Gas' => 'fa-oil-can', 'Manufacturing' => 'fa-industry', 'Mining' => 'fa-gem', 'Water & Utilities' => 'fa-water', 'Transportation' => 'fa-truck'] as $industry => $icon)
                    <div style="background: linear-gradient(135deg, var(--dark-bg) 0%, var(--card-bg) 100%); border-radius: 8px; padding: 30px; text-align: center; border: 1px solid var(--border-color);">
                        <i class="fas {{ $icon }}" style="font-size: 40px; color: var(--primary-blue); margin-bottom: 15px;"></i>
                        <h4>{{ $industry }}</h4>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
