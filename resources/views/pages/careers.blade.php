@extends('layouts.app')

@section('title', 'Careers')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Careers</span>
            </div>
            <h1 class="page-title">Careers</h1>
        </div>
    </div>

    <!-- Content Section -->
    <section class="section">
        <div class="container">
            <div style="max-width: 800px; margin: 0 auto;">
                <!-- Hero -->
                <div style="text-align: center; margin-bottom: 50px;">
                    <i class="fas fa-users" style="font-size: 60px; color: var(--primary-blue); margin-bottom: 20px;"></i>
                    <h2 style="margin-bottom: 15px;">Join Our Team</h2>
                    <p style="color: var(--light-gray); max-width: 600px; margin: 0 auto;">
                        At Nayon Engineering, we're always looking for talented individuals who share our passion for excellence in electrical solutions.
                    </p>
                </div>

                <!-- Why Work With Us -->
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 40px; margin-bottom: 30px;">
                    <h3 style="margin-bottom: 25px; text-align: center;">Why Work With Us?</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 25px;">
                        @foreach([
                            ['icon' => 'fa-chart-line', 'title' => 'Growth Opportunities', 'desc' => 'Develop your career with training and advancement'],
                            ['icon' => 'fa-handshake', 'title' => 'Great Team', 'desc' => 'Work alongside experienced professionals'],
                            ['icon' => 'fa-briefcase', 'title' => 'Competitive Benefits', 'desc' => 'Attractive salary and benefits package'],
                            ['icon' => 'fa-lightbulb', 'title' => 'Innovation', 'desc' => 'Work with cutting-edge electrical technology']
                        ] as $benefit)
                        <div style="text-align: center;">
                            <i class="fas {{ $benefit['icon'] }}" style="font-size: 30px; color: var(--primary-blue); margin-bottom: 15px;"></i>
                            <h4 style="margin-bottom: 10px;">{{ $benefit['title'] }}</h4>
                            <p style="color: var(--light-gray); font-size: 14px;">{{ $benefit['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Current Openings -->
                <div style="background-color: var(--card-bg); border-radius: 8px; padding: 40px; margin-bottom: 30px;">
                    <h3 style="margin-bottom: 25px; text-align: center;">Current Openings</h3>
                    <div style="text-align: center; padding: 40px 20px;">
                        <i class="fas fa-clipboard-list" style="font-size: 48px; color: var(--light-gray); margin-bottom: 20px;"></i>
                        <p style="color: var(--light-gray); margin-bottom: 20px;">
                            No open positions at the moment. Check back soon or send us your CV for future opportunities.
                        </p>
                    </div>
                </div>

                <!-- Submit CV -->
                <div style="background-color: var(--primary-blue); border-radius: 8px; padding: 40px; text-align: center; color: white;">
                    <h3 style="margin-bottom: 15px; color: white;">Interested in Joining Us?</h3>
                    <p style="margin-bottom: 25px; opacity: 0.9;">
                        Send your CV to our HR department and we'll keep it on file for future opportunities.
                    </p>
                    <a href="mailto:careers@nayon-engineering.co.za" class="btn" style="background-color: white; color: var(--primary-blue);">
                        <i class="fas fa-envelope"></i> Send Your CV
                    </a>
                    <p style="margin-top: 20px; font-size: 14px; opacity: 0.8;">
                        Email: careers@nayon-engineering.co.za
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
