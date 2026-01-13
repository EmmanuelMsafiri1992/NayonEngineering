@extends('admin.layouts.app')

@section('title', 'About Us Page')

@section('content')
    <div class="settings-nav">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline">
            <i class="fas fa-cog"></i> General
        </a>
        <a href="{{ route('admin.settings.seo') }}" class="btn btn-outline">
            <i class="fas fa-search"></i> SEO
        </a>
        <a href="{{ route('admin.settings.appearance') }}" class="btn btn-outline">
            <i class="fas fa-palette"></i> Appearance
        </a>
        <a href="{{ route('admin.settings.content') }}" class="btn btn-outline">
            <i class="fas fa-edit"></i> Content
        </a>
        <a href="{{ route('admin.settings.about-us') }}" class="btn btn-primary">
            <i class="fas fa-info-circle"></i> About Us
        </a>
        <a href="{{ route('admin.settings.payment') }}" class="btn btn-outline">
            <i class="fas fa-credit-card"></i> Payment
        </a>
    </div>

    <form action="{{ route('admin.settings.about-us.update') }}" method="POST">
        @csrf

        <!-- Introduction Section -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-building"></i> Introduction Section</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Welcome Title</label>
                    <input type="text" name="about_intro_title" class="form-control"
                           value="{{ $settings['about_intro_title'] ?? 'Nayon Engineering' }}"
                           placeholder="Company Name">
                    <p class="form-help">This appears as "Welcome to [Your Title]"</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Introduction Paragraph 1</label>
                    <textarea name="about_intro_text_1" class="form-control" rows="3"
                              placeholder="Main introduction text about your company...">{{ $settings['about_intro_text_1'] ?? 'Nayon Engineering is your trusted partner for industrial spares, electrical supplies, project management, and installations across Africa and globally.' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Introduction Paragraph 2</label>
                    <textarea name="about_intro_text_2" class="form-control" rows="3"
                              placeholder="Additional details about products/services...">{{ $settings['about_intro_text_2'] ?? 'Our comprehensive range of products includes everything from basic electrical components to advanced automation systems, ensuring that we can meet all your industrial and electrical needs under one roof.' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Introduction Paragraph 3</label>
                    <textarea name="about_intro_text_3" class="form-control" rows="3"
                              placeholder="What makes you stand out...">{{ $settings['about_intro_text_3'] ?? 'We pride ourselves on offering competitive prices, quality products from trusted brands, and exceptional customer service.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Mission, Vision, Values -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-bullseye"></i> Mission, Vision & Values</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-bullseye" style="color: var(--primary);"></i> Mission Title</label>
                            <input type="text" name="about_mission_title" class="form-control"
                                   value="{{ $settings['about_mission_title'] ?? 'Our Mission' }}"
                                   placeholder="Our Mission">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Mission Text</label>
                            <textarea name="about_mission_text" class="form-control" rows="3"
                                      placeholder="Your company's mission statement...">{{ $settings['about_mission_text'] ?? 'To provide quality electrical products and services that empower industries across Africa and beyond.' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-eye" style="color: var(--primary);"></i> Vision Title</label>
                            <input type="text" name="about_vision_title" class="form-control"
                                   value="{{ $settings['about_vision_title'] ?? 'Our Vision' }}"
                                   placeholder="Our Vision">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Vision Text</label>
                            <textarea name="about_vision_text" class="form-control" rows="3"
                                      placeholder="Your company's vision statement...">{{ $settings['about_vision_text'] ?? 'To be the leading supplier of industrial electrical products and services in Africa.' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-heart" style="color: var(--primary);"></i> Values Title</label>
                            <input type="text" name="about_values_title" class="form-control"
                                   value="{{ $settings['about_values_title'] ?? 'Our Values' }}"
                                   placeholder="Our Values">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Values Text</label>
                            <textarea name="about_values_text" class="form-control" rows="3"
                                      placeholder="Your company's core values...">{{ $settings['about_values_text'] ?? 'Integrity, quality, innovation, and customer focus drive everything we do.' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Industries We Serve -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-industry"></i> Industries We Serve</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Industries (one per line)</label>
                    <textarea name="about_industries" class="form-control" rows="8"
                              placeholder="Power & Energy|fa-bolt
Agriculture|fa-tractor
Construction|fa-hard-hat">{{ $settings['about_industries'] ?? "Power & Energy|fa-bolt\nAgriculture|fa-tractor\nConstruction|fa-hard-hat\nOil & Gas|fa-oil-can\nManufacturing|fa-industry\nMining|fa-gem\nWater & Utilities|fa-water\nTransportation|fa-truck" }}</textarea>
                    <p class="form-help">Format: Industry Name|icon-class (e.g., "Power & Energy|fa-bolt"). One industry per line.</p>
                    <p class="form-help">Find icons at <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a></p>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save About Us Page
            </button>
            <a href="{{ route('about') }}" target="_blank" class="btn btn-outline">
                <i class="fas fa-external-link-alt"></i> View Page
            </a>
        </div>
    </form>

    <style>
        .settings-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .card {
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
        }

        .card-header h3 {
            margin: 0;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h3 i {
            color: var(--primary);
        }

        .card-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-label i {
            margin-right: 5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        .form-help {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 5px;
        }

        .form-help a {
            color: var(--primary);
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
