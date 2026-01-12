@extends('admin.layouts.app')

@section('title', 'Appearance Settings')

@section('content')
    <div class="settings-nav">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline">
            <i class="fas fa-cog"></i> General
        </a>
        <a href="{{ route('admin.settings.seo') }}" class="btn btn-outline">
            <i class="fas fa-search"></i> SEO
        </a>
        <a href="{{ route('admin.settings.appearance') }}" class="btn btn-primary">
            <i class="fas fa-palette"></i> Appearance
        </a>
        <a href="{{ route('admin.settings.content') }}" class="btn btn-outline">
            <i class="fas fa-edit"></i> Content
        </a>
        <a href="{{ route('admin.settings.payment') }}" class="btn btn-outline">
            <i class="fas fa-credit-card"></i> Payment
        </a>
    </div>

    <form action="{{ route('admin.settings.appearance.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Logo Settings -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-image"></i> Logo & Branding</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Site Logo</label>
                            <div class="file-upload-wrapper">
                                <div class="current-image">
                                    @if($settings['site_logo'] ?? false)
                                        <img src="{{ asset($settings['site_logo']) }}" alt="Current Logo" id="logo-preview">
                                    @else
                                        <img src="{{ asset('images/nayon-logo.png') }}" alt="Default Logo" id="logo-preview">
                                    @endif
                                </div>
                                <input type="file" name="site_logo" id="site_logo" accept="image/*" class="file-input">
                                <label for="site_logo" class="file-label">
                                    <i class="fas fa-upload"></i> Choose Logo
                                </label>
                                <p class="form-help">Recommended: PNG with transparent background, max 500x150px</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Favicon</label>
                            <div class="file-upload-wrapper">
                                <div class="current-image favicon-preview">
                                    @if($settings['site_favicon'] ?? false)
                                        <img src="{{ asset($settings['site_favicon']) }}" alt="Current Favicon" id="favicon-preview">
                                    @else
                                        <img src="{{ asset('favicon.ico') }}" alt="Default Favicon" id="favicon-preview">
                                    @endif
                                </div>
                                <input type="file" name="site_favicon" id="site_favicon" accept="image/x-icon,image/png" class="file-input">
                                <label for="site_favicon" class="file-label">
                                    <i class="fas fa-upload"></i> Choose Favicon
                                </label>
                                <p class="form-help">ICO or PNG, 32x32px or 64x64px</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Logo URL (Alternative)</label>
                    <input type="text" name="site_logo_url" class="form-control"
                           value="{{ $settings['site_logo_url'] ?? '' }}"
                           placeholder="https://example.com/logo.png">
                    <p class="form-help">Use this if you want to load the logo from an external URL instead of uploading.</p>
                </div>
            </div>
        </div>

        <!-- Color Scheme -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-paint-brush"></i> Color Scheme</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Primary Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="color_primary" id="color_primary"
                                       value="{{ $settings['color_primary'] ?? '#0079C1' }}">
                                <input type="text" class="form-control color-text"
                                       value="{{ $settings['color_primary'] ?? '#0079C1' }}"
                                       id="color_primary_text">
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Secondary Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="color_secondary" id="color_secondary"
                                       value="{{ $settings['color_secondary'] ?? '#FF6B00' }}">
                                <input type="text" class="form-control color-text"
                                       value="{{ $settings['color_secondary'] ?? '#FF6B00' }}"
                                       id="color_secondary_text">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Header Background</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="color_header_bg" id="color_header_bg"
                                       value="{{ $settings['color_header_bg'] ?? '#FFFFFF' }}">
                                <input type="text" class="form-control color-text"
                                       value="{{ $settings['color_header_bg'] ?? '#FFFFFF' }}"
                                       id="color_header_bg_text">
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Footer Background</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="color_footer_bg" id="color_footer_bg"
                                       value="{{ $settings['color_footer_bg'] ?? '#1a1a1a' }}">
                                <input type="text" class="form-control color-text"
                                       value="{{ $settings['color_footer_bg'] ?? '#1a1a1a' }}"
                                       id="color_footer_bg_text">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="color-preview">
                    <h4>Preview</h4>
                    <div class="preview-box" id="colorPreview">
                        <div class="preview-header" id="previewHeader">
                            <span class="preview-logo">Logo</span>
                            <span class="preview-btn" id="previewPrimary">Button</span>
                        </div>
                        <div class="preview-content">
                            <span class="preview-accent" id="previewSecondary">Accent Color</span>
                        </div>
                        <div class="preview-footer" id="previewFooter">
                            Footer Area
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Bar Settings -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-bars"></i> Top Bar</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="topbar_enabled" value="1"
                               {{ ($settings['topbar_enabled'] ?? true) ? 'checked' : '' }}>
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Show Top Bar</span>
                    </label>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Top Bar Phone</label>
                            <input type="text" name="topbar_phone" class="form-control"
                                   value="{{ $settings['topbar_phone'] ?? '' }}"
                                   placeholder="+27 (0) 11 824 1059">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Top Bar Email</label>
                            <input type="email" name="topbar_email" class="form-control"
                                   value="{{ $settings['topbar_email'] ?? '' }}"
                                   placeholder="info@company.co.za">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Top Bar Background Color</label>
                    <div class="color-input-wrapper">
                        <input type="color" name="topbar_bg_color" id="topbar_bg_color"
                               value="{{ $settings['topbar_bg_color'] ?? '#333333' }}">
                        <input type="text" class="form-control color-text"
                               value="{{ $settings['topbar_bg_color'] ?? '#333333' }}"
                               id="topbar_bg_color_text">
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom CSS -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-code"></i> Custom CSS</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Custom CSS Code</label>
                    <textarea name="custom_css" class="form-control code-input" rows="10"
                              placeholder="/* Add your custom CSS here */
.my-class {
    color: red;
}">{{ $settings['custom_css'] ?? '' }}</textarea>
                    <p class="form-help">This CSS will be added to all frontend pages.</p>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Appearance Settings
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

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .file-upload-wrapper {
            text-align: center;
        }

        .current-image {
            width: 200px;
            height: 80px;
            border: 2px dashed var(--border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .current-image.favicon-preview {
            width: 80px;
            height: 80px;
        }

        .current-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .file-input {
            display: none;
        }

        .file-label {
            display: inline-block;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .file-label:hover {
            background: #005a8c;
        }

        .color-input-wrapper {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .color-input-wrapper input[type="color"] {
            width: 50px;
            height: 40px;
            border: 1px solid var(--border);
            border-radius: 6px;
            cursor: pointer;
            padding: 2px;
        }

        .color-text {
            width: 120px !important;
            text-transform: uppercase;
        }

        .color-preview {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .color-preview h4 {
            margin-bottom: 15px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .preview-box {
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }

        .preview-header {
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .preview-logo {
            font-weight: bold;
        }

        .preview-btn {
            padding: 8px 15px;
            border-radius: 4px;
            color: white;
            font-size: 12px;
        }

        .preview-content {
            padding: 20px;
            background: #f8f9fa;
            text-align: center;
        }

        .preview-accent {
            padding: 5px 15px;
            border-radius: 4px;
            color: white;
            font-size: 12px;
        }

        .preview-footer {
            padding: 15px;
            color: white;
            text-align: center;
            font-size: 12px;
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

        .code-input {
            font-family: monospace;
            font-size: 13px;
            background: #f8f9fa;
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

    <script>
        // Preview image on file select
        document.getElementById('site_logo')?.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logo-preview').src = e.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        document.getElementById('site_favicon')?.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('favicon-preview').src = e.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Sync color inputs
        function setupColorSync(colorId, textId) {
            const colorInput = document.getElementById(colorId);
            const textInput = document.getElementById(textId);

            colorInput?.addEventListener('input', function() {
                textInput.value = this.value.toUpperCase();
                updateColorPreview();
            });

            textInput?.addEventListener('input', function() {
                if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                    colorInput.value = this.value;
                    updateColorPreview();
                }
            });
        }

        setupColorSync('color_primary', 'color_primary_text');
        setupColorSync('color_secondary', 'color_secondary_text');
        setupColorSync('color_header_bg', 'color_header_bg_text');
        setupColorSync('color_footer_bg', 'color_footer_bg_text');

        function updateColorPreview() {
            const primary = document.getElementById('color_primary').value;
            const secondary = document.getElementById('color_secondary').value;
            const headerBg = document.getElementById('color_header_bg').value;
            const footerBg = document.getElementById('color_footer_bg').value;

            document.getElementById('previewHeader').style.background = headerBg;
            document.getElementById('previewPrimary').style.background = primary;
            document.getElementById('previewSecondary').style.background = secondary;
            document.getElementById('previewFooter').style.background = footerBg;
        }

        // Initial preview
        updateColorPreview();
    </script>
@endsection
