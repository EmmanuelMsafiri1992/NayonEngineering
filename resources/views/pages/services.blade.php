@extends('layouts.app')

@section('title', 'Services')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Services</span>
            </div>
            <h1 class="page-title">Our Services</h1>
        </div>
    </div>

    <!-- Services Section -->
    <section class="section">
        <div class="container">
            <p style="text-align: center; color: var(--light-gray); max-width: 800px; margin: 0 auto 50px; font-size: 18px; line-height: 1.8;">
                At Nayon Engineering, we offer comprehensive industrial and electrical services tailored to meet the unique needs of various industries across Africa and globally.
            </p>

            <!-- Service Cards -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; margin-bottom: 60px;">
                @foreach([
                    ['id' => 'switchgear', 'title' => 'Switchgear Solutions', 'color' => 'var(--primary-blue)', 'icon' => 'fa-bolt',
                     'desc' => 'We provide comprehensive switchgear solutions including design, installation, commissioning, and maintenance.',
                     'items' => ['LV & MV Switchgear Installation', 'Panel Board Assembly', 'Motor Control Centers', 'Protection & Metering Systems']],
                    ['id' => 'electrical', 'title' => 'Electrical Supplies', 'color' => 'var(--secondary-yellow)', 'icon' => 'fa-plug',
                     'desc' => 'We stock a comprehensive range of electrical products from leading manufacturers.',
                     'items' => ['Circuit Breakers & Protection', 'Cables & Wiring Accessories', 'Lighting Solutions', 'Automation Products']],
                    ['id' => 'projects', 'title' => 'Project Management', 'color' => '#28a745', 'icon' => 'fa-project-diagram',
                     'desc' => 'Our experienced project managers oversee electrical and industrial projects from conception to completion.',
                     'items' => ['Project Planning & Design', 'Resource Coordination', 'Quality Assurance', 'Documentation & Reporting']],
                    ['id' => 'installations', 'title' => 'Installation Services', 'color' => '#6f42c1', 'icon' => 'fa-tools',
                     'desc' => 'Our certified technicians provide professional installation services for all types of electrical equipment.',
                     'items' => ['Equipment Installation', 'Testing & Commissioning', 'System Integration', 'Training & Handover']]
                ] as $service)
                <div id="{{ $service['id'] }}" style="background-color: var(--card-bg); border-radius: 8px; overflow: hidden;">
                    <div style="height: 200px; background: linear-gradient(135deg, {{ $service['color'] }} 0%, {{ $service['color'] }}dd 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="fas {{ $service['icon'] }}" style="font-size: 80px; opacity: 0.5;"></i>
                    </div>
                    <div style="padding: 30px;">
                        <h3 style="font-size: 24px; margin-bottom: 15px;">{{ $service['title'] }}</h3>
                        <p style="color: var(--light-gray); line-height: 1.8; margin-bottom: 20px;">{{ $service['desc'] }}</p>
                        <ul style="color: var(--light-gray); margin-bottom: 20px; padding-left: 20px;">
                            @foreach($service['items'] as $item)
                            <li style="margin-bottom: 10px;">{{ $item }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('contact') }}" class="btn btn-primary">Get a Quote</a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Additional Services -->
            <h2 style="text-align: center; margin-bottom: 40px;">Additional <span style="color: var(--primary-blue);">Services</span></h2>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                @foreach(['Maintenance' => 'fa-wrench', 'Solar Solutions' => 'fa-solar-panel', 'Automation' => 'fa-cogs', 'Training' => 'fa-chalkboard-teacher'] as $name => $icon)
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 30px; text-align: center;">
                    <div style="width: 70px; height: 70px; background-color: var(--primary-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas {{ $icon }}" style="font-size: 30px;"></i>
                    </div>
                    <h4 style="margin-bottom: 10px;">{{ $name }}</h4>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%); padding: 60px 0;">
        <div class="container" style="text-align: center;">
            <h2 style="font-size: 32px; margin-bottom: 20px;">Ready to Start Your Project?</h2>
            <p style="max-width: 600px; margin: 0 auto 30px; opacity: 0.9;">
                Contact our team today to discuss your requirements and get a customized solution.
            </p>
            <a href="{{ route('contact') }}" class="btn btn-secondary">Contact Us Today</a>
        </div>
    </section>
@endsection
