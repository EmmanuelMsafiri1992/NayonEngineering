@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="settings-nav" style="display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap;">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-primary">
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
        <a href="{{ route('admin.settings.about-us') }}" class="btn btn-outline">
            <i class="fas fa-info-circle"></i> About Us
        </a>
        <a href="{{ route('admin.settings.payment') }}" class="btn btn-outline">
            <i class="fas fa-credit-card"></i> Payment
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Site Settings</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <h4 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--border);">
                    <i class="fas fa-building"></i> Company Information
                </h4>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Site Name *</label>
                            <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Contact Email *</label>
                            <input type="email" name="site_email" class="form-control" value="{{ $settings['site_email'] ?? '' }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Phone Number *</label>
                            <input type="text" name="site_phone" class="form-control" value="{{ $settings['site_phone'] ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Business Hours *</label>
                            <input type="text" name="business_hours" class="form-control" value="{{ $settings['business_hours'] ?? '' }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Address *</label>
                    <input type="text" name="site_address" class="form-control" value="{{ $settings['site_address'] ?? '' }}" required>
                </div>

                <h4 style="margin: 30px 0 20px; padding-bottom: 10px; border-bottom: 1px solid var(--border);">
                    <i class="fas fa-share-alt"></i> Social Media Links
                </h4>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-facebook" style="color: #1877f2;"></i> Facebook URL</label>
                            <input type="url" name="facebook_url" class="form-control" value="{{ $settings['facebook_url'] ?? '' }}" placeholder="https://facebook.com/yourpage">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-twitter" style="color: #1da1f2;"></i> Twitter URL</label>
                            <input type="url" name="twitter_url" class="form-control" value="{{ $settings['twitter_url'] ?? '' }}" placeholder="https://twitter.com/yourpage">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-linkedin" style="color: #0077b5;"></i> LinkedIn URL</label>
                            <input type="url" name="linkedin_url" class="form-control" value="{{ $settings['linkedin_url'] ?? '' }}" placeholder="https://linkedin.com/company/yourpage">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-instagram" style="color: #e4405f;"></i> Instagram URL</label>
                            <input type="url" name="instagram_url" class="form-control" value="{{ $settings['instagram_url'] ?? '' }}" placeholder="https://instagram.com/yourpage">
                        </div>
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
