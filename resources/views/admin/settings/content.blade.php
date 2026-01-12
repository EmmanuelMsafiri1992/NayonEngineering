@extends('admin.layouts.app')

@section('title', 'Content Settings')

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
        <a href="{{ route('admin.settings.content') }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Content
        </a>
        <a href="{{ route('admin.settings.payment') }}" class="btn btn-outline">
            <i class="fas fa-credit-card"></i> Payment
        </a>
    </div>

    <form action="{{ route('admin.settings.content.update') }}" method="POST">
        @csrf

        <!-- Header Content -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-window-maximize"></i> Header Content</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Search Placeholder Text</label>
                    <input type="text" name="header_search_placeholder" class="form-control"
                           value="{{ $settings['header_search_placeholder'] ?? '' }}"
                           placeholder="Search for products, brands, categories...">
                </div>

                <div class="form-group">
                    <label class="form-label">Announcement Bar Text</label>
                    <input type="text" name="header_announcement" class="form-control"
                           value="{{ $settings['header_announcement'] ?? '' }}"
                           placeholder="Free shipping on orders over R500!">
                    <p class="form-help">Leave empty to hide the announcement bar.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Announcement Bar Link</label>
                    <input type="text" name="header_announcement_link" class="form-control"
                           value="{{ $settings['header_announcement_link'] ?? '' }}"
                           placeholder="/products?promo=free-shipping">
                </div>
            </div>
        </div>

        <!-- Footer Content -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-window-minimize"></i> Footer Content</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Footer About Text</label>
                    <textarea name="footer_about" class="form-control" rows="4"
                              placeholder="Your trusted partner for industrial spares, electrical supplies, project management, and installations across Africa and globally.">{{ $settings['footer_about'] ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Copyright Text</label>
                    <input type="text" name="footer_copyright" class="form-control"
                           value="{{ $settings['footer_copyright'] ?? '' }}"
                           placeholder="Nayon Engineering. All Rights Reserved.">
                    <p class="form-help">The year will be automatically added.</p>
                </div>

                <h4 class="section-title">Contact Information</h4>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> Address</label>
                            <textarea name="footer_address" class="form-control" rows="2"
                                      placeholder="123 Street Name, City, Country">{{ $settings['footer_address'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-phone"></i> Phone Numbers</label>
                            <textarea name="footer_phones" class="form-control" rows="2"
                                      placeholder="+27 (0) 11 824 1059
+27 (0) 11 824 1060">{{ $settings['footer_phones'] ?? '' }}</textarea>
                            <p class="form-help">One number per line</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-envelope"></i> Email Addresses</label>
                            <textarea name="footer_emails" class="form-control" rows="2"
                                      placeholder="info@company.co.za
sales@company.co.za">{{ $settings['footer_emails'] ?? '' }}</textarea>
                            <p class="form-help">One email per line</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-clock"></i> Business Hours</label>
                            <textarea name="footer_hours" class="form-control" rows="2"
                                      placeholder="Mon - Fri: 8:00 AM - 5:00 PM
Sat: 9:00 AM - 1:00 PM">{{ $settings['footer_hours'] ?? '' }}</textarea>
                            <p class="form-help">One line per entry</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media Links -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-share-alt"></i> Social Media Links</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-facebook" style="color: #1877f2;"></i> Facebook</label>
                            <input type="url" name="social_facebook" class="form-control"
                                   value="{{ $settings['social_facebook'] ?? '' }}"
                                   placeholder="https://facebook.com/yourpage">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-twitter" style="color: #1da1f2;"></i> Twitter / X</label>
                            <input type="url" name="social_twitter" class="form-control"
                                   value="{{ $settings['social_twitter'] ?? '' }}"
                                   placeholder="https://twitter.com/yourpage">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-instagram" style="color: #e4405f;"></i> Instagram</label>
                            <input type="url" name="social_instagram" class="form-control"
                                   value="{{ $settings['social_instagram'] ?? '' }}"
                                   placeholder="https://instagram.com/yourpage">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-linkedin" style="color: #0077b5;"></i> LinkedIn</label>
                            <input type="url" name="social_linkedin" class="form-control"
                                   value="{{ $settings['social_linkedin'] ?? '' }}"
                                   placeholder="https://linkedin.com/company/yourpage">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-youtube" style="color: #ff0000;"></i> YouTube</label>
                            <input type="url" name="social_youtube" class="form-control"
                                   value="{{ $settings['social_youtube'] ?? '' }}"
                                   placeholder="https://youtube.com/channel/yourpage">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-whatsapp" style="color: #25d366;"></i> WhatsApp</label>
                            <input type="text" name="social_whatsapp" class="form-control"
                                   value="{{ $settings['social_whatsapp'] ?? '' }}"
                                   placeholder="+27123456789">
                            <p class="form-help">Phone number with country code, no spaces</p>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="social_show_footer" value="1"
                               {{ ($settings['social_show_footer'] ?? true) ? 'checked' : '' }}>
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Show Social Links in Footer</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Newsletter Section -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-envelope-open-text"></i> Newsletter Section</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="newsletter_enabled" value="1"
                               {{ ($settings['newsletter_enabled'] ?? true) ? 'checked' : '' }}>
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Show Newsletter Section</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Newsletter Title</label>
                    <input type="text" name="newsletter_title" class="form-control"
                           value="{{ $settings['newsletter_title'] ?? '' }}"
                           placeholder="Subscribe to Our Newsletter">
                </div>

                <div class="form-group">
                    <label class="form-label">Newsletter Description</label>
                    <input type="text" name="newsletter_description" class="form-control"
                           value="{{ $settings['newsletter_description'] ?? '' }}"
                           placeholder="Get the latest updates on new products and upcoming sales">
                </div>

                <div class="form-group">
                    <label class="form-label">Button Text</label>
                    <input type="text" name="newsletter_button" class="form-control"
                           value="{{ $settings['newsletter_button'] ?? '' }}"
                           placeholder="Subscribe">
                </div>
            </div>
        </div>

        <!-- Homepage Content -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-home"></i> Homepage Content</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Hero Title</label>
                    <input type="text" name="hero_title" class="form-control"
                           value="{{ $settings['hero_title'] ?? '' }}"
                           placeholder="Industrial Spares & Electrical Supplies">
                </div>

                <div class="form-group">
                    <label class="form-label">Hero Subtitle</label>
                    <textarea name="hero_subtitle" class="form-control" rows="2"
                              placeholder="Your trusted partner for quality industrial equipment and solutions">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Hero Button Text</label>
                    <input type="text" name="hero_button_text" class="form-control"
                           value="{{ $settings['hero_button_text'] ?? '' }}"
                           placeholder="Shop Now">
                </div>

                <div class="form-group">
                    <label class="form-label">Hero Button Link</label>
                    <input type="text" name="hero_button_link" class="form-control"
                           value="{{ $settings['hero_button_link'] ?? '' }}"
                           placeholder="/products">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Content Settings
            </button>
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

        .section-title {
            font-size: 16px;
            margin: 25px 0 15px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            color: var(--text);
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

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .toggle-label {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .toggle-label input {
            display: none;
        }

        .toggle-switch {
            width: 50px;
            height: 26px;
            background: #ddd;
            border-radius: 13px;
            position: relative;
            transition: background 0.3s;
        }

        .toggle-switch::after {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            background: #fff;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: transform 0.3s;
        }

        .toggle-label input:checked + .toggle-switch {
            background: var(--primary);
        }

        .toggle-label input:checked + .toggle-switch::after {
            transform: translateX(24px);
        }

        .form-actions {
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
