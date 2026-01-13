@extends('admin.layouts.app')

@section('title', 'SEO Settings')

@section('content')
    <div class="settings-nav">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline">
            <i class="fas fa-cog"></i> General
        </a>
        <a href="{{ route('admin.settings.seo') }}" class="btn btn-primary">
            <i class="fas fa-search"></i> SEO
        </a>
        <a href="{{ route('admin.settings.appearance') }}" class="btn btn-outline">
            <i class="fas fa-palette"></i> Appearance
        </a>
        <a href="{{ route('admin.settings.content') }}" class="btn btn-outline">
            <i class="fas fa-edit"></i> Content
        </a>
        <a href="{{ route('admin.settings.about-us') }}" class="btn btn-outline">
            <i class="fas fa-info-circle"></i> About Us
        </a>
        <a href="{{ route('admin.settings.payment') }}" class="btn btn-outline">
            <i class="fas fa-credit-card"></i> Payment
        </a>
    </div>

    <form action="{{ route('admin.settings.seo.update') }}" method="POST">
        @csrf

        <!-- Global SEO Settings -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-globe"></i> Global SEO Settings</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Site Title</label>
                    <input type="text" name="seo_site_title" class="form-control"
                           value="{{ $settings['seo_site_title'] ?? '' }}"
                           placeholder="Nayon Engineering - Industrial Spares & Supplies">
                    <p class="form-help">This appears in browser tabs and search results.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Site Tagline</label>
                    <input type="text" name="seo_tagline" class="form-control"
                           value="{{ $settings['seo_tagline'] ?? '' }}"
                           placeholder="Your trusted partner for industrial solutions">
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="seo_meta_description" class="form-control" rows="3"
                              placeholder="A brief description of your website (150-160 characters recommended)">{{ $settings['seo_meta_description'] ?? '' }}</textarea>
                    <p class="form-help">
                        <span id="meta-desc-count">{{ strlen($settings['seo_meta_description'] ?? '') }}</span>/160 characters
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="seo_meta_keywords" class="form-control"
                           value="{{ $settings['seo_meta_keywords'] ?? '' }}"
                           placeholder="industrial spares, electrical supplies, switchgear, engineering">
                    <p class="form-help">Comma-separated keywords relevant to your business.</p>
                </div>
            </div>
        </div>

        <!-- Open Graph / Social Media -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fab fa-facebook"></i> Social Media (Open Graph)</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">OG Title</label>
                    <input type="text" name="seo_og_title" class="form-control"
                           value="{{ $settings['seo_og_title'] ?? '' }}"
                           placeholder="Leave empty to use Site Title">
                </div>

                <div class="form-group">
                    <label class="form-label">OG Description</label>
                    <textarea name="seo_og_description" class="form-control" rows="2"
                              placeholder="Leave empty to use Meta Description">{{ $settings['seo_og_description'] ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">OG Image URL</label>
                    <input type="text" name="seo_og_image" class="form-control"
                           value="{{ $settings['seo_og_image'] ?? '' }}"
                           placeholder="https://yoursite.com/images/og-image.jpg">
                    <p class="form-help">Recommended size: 1200x630 pixels. Used when sharing on social media.</p>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Facebook App ID</label>
                            <input type="text" name="seo_fb_app_id" class="form-control"
                                   value="{{ $settings['seo_fb_app_id'] ?? '' }}"
                                   placeholder="Optional">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Twitter Handle</label>
                            <input type="text" name="seo_twitter_handle" class="form-control"
                                   value="{{ $settings['seo_twitter_handle'] ?? '' }}"
                                   placeholder="@yourcompany">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics & Verification -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-chart-line"></i> Analytics & Verification</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Google Analytics ID</label>
                    <input type="text" name="seo_google_analytics" class="form-control"
                           value="{{ $settings['seo_google_analytics'] ?? '' }}"
                           placeholder="G-XXXXXXXXXX or UA-XXXXXXXXX-X">
                </div>

                <div class="form-group">
                    <label class="form-label">Google Search Console Verification</label>
                    <input type="text" name="seo_google_verification" class="form-control"
                           value="{{ $settings['seo_google_verification'] ?? '' }}"
                           placeholder="Verification meta tag content">
                </div>

                <div class="form-group">
                    <label class="form-label">Bing Webmaster Verification</label>
                    <input type="text" name="seo_bing_verification" class="form-control"
                           value="{{ $settings['seo_bing_verification'] ?? '' }}"
                           placeholder="Verification meta tag content">
                </div>

                <div class="form-group">
                    <label class="form-label">Custom Head Scripts</label>
                    <textarea name="seo_head_scripts" class="form-control code-input" rows="4"
                              placeholder="<!-- Add custom scripts here (analytics, chat widgets, etc.) -->">{{ $settings['seo_head_scripts'] ?? '' }}</textarea>
                    <p class="form-help">Scripts added here will be inserted in the &lt;head&gt; section.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Custom Footer Scripts</label>
                    <textarea name="seo_footer_scripts" class="form-control code-input" rows="4"
                              placeholder="<!-- Add custom scripts here -->">{{ $settings['seo_footer_scripts'] ?? '' }}</textarea>
                    <p class="form-help">Scripts added here will be inserted before &lt;/body&gt;.</p>
                </div>
            </div>
        </div>

        <!-- Robots & Sitemap -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-robot"></i> Robots & Indexing</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="seo_index_site" value="1"
                               {{ ($settings['seo_index_site'] ?? true) ? 'checked' : '' }}>
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Allow Search Engine Indexing</span>
                    </label>
                    <p class="form-help">Disable this to prevent search engines from indexing your site (useful for development).</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Robots.txt Content</label>
                    <textarea name="seo_robots_txt" class="form-control code-input" rows="5"
                              placeholder="User-agent: *
Allow: /
Sitemap: {{ url('/sitemap.xml') }}">{{ $settings['seo_robots_txt'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save SEO Settings
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
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

        .code-input {
            font-family: monospace;
            font-size: 13px;
            background: #f8f9fa;
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

            .settings-nav {
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 10px;
            }

            .settings-nav .btn {
                white-space: nowrap;
            }
        }
    </style>

    <script>
        // Character counter for meta description
        document.querySelector('textarea[name="seo_meta_description"]')?.addEventListener('input', function() {
            document.getElementById('meta-desc-count').textContent = this.value.length;
        });
    </script>
@endsection
