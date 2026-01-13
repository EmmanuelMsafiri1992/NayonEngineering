<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\CurrencyService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Show SEO settings page
     */
    public function seo()
    {
        $settings = Setting::getAll();
        return view('admin.settings.seo', compact('settings'));
    }

    /**
     * Update SEO settings
     */
    public function updateSeo(Request $request)
    {
        $fields = [
            'seo_site_title', 'seo_tagline', 'seo_meta_description', 'seo_meta_keywords',
            'seo_og_title', 'seo_og_description', 'seo_og_image', 'seo_fb_app_id', 'seo_twitter_handle',
            'seo_google_analytics', 'seo_google_verification', 'seo_bing_verification',
            'seo_head_scripts', 'seo_footer_scripts', 'seo_robots_txt'
        ];

        foreach ($fields as $field) {
            Setting::set($field, $request->input($field, ''));
        }

        Setting::set('seo_index_site', $request->has('seo_index_site'));
        Setting::clearCache();

        return back()->with('success', 'SEO settings updated successfully.');
    }

    /**
     * Show appearance settings page
     */
    public function appearance()
    {
        $settings = Setting::getAll();
        return view('admin.settings.appearance', compact('settings'));
    }

    /**
     * Update appearance settings
     */
    public function updateAppearance(Request $request)
    {
        // Handle file uploads
        if ($request->hasFile('site_logo')) {
            $logo = $request->file('site_logo');
            $logoPath = 'uploads/logos/' . time() . '_logo.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/logos'), basename($logoPath));
            Setting::set('site_logo', $logoPath);
        }

        if ($request->hasFile('site_favicon')) {
            $favicon = $request->file('site_favicon');
            $faviconPath = 'uploads/logos/' . time() . '_favicon.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('uploads/logos'), basename($faviconPath));
            Setting::set('site_favicon', $faviconPath);
        }

        // Text and color fields
        $fields = [
            'site_logo_url', 'color_primary', 'color_secondary', 'color_header_bg', 'color_footer_bg',
            'topbar_phone', 'topbar_email', 'topbar_bg_color', 'custom_css'
        ];

        foreach ($fields as $field) {
            Setting::set($field, $request->input($field, ''));
        }

        Setting::set('topbar_enabled', $request->has('topbar_enabled'));
        Setting::clearCache();

        return back()->with('success', 'Appearance settings updated successfully.');
    }

    /**
     * Show content settings page
     */
    public function content()
    {
        $settings = Setting::getAll();
        return view('admin.settings.content', compact('settings'));
    }

    /**
     * Update content settings
     */
    public function updateContent(Request $request)
    {
        $fields = [
            'header_search_placeholder', 'header_announcement', 'header_announcement_link',
            'footer_about', 'footer_copyright', 'footer_address', 'footer_phones', 'footer_emails', 'footer_hours',
            'social_facebook', 'social_twitter', 'social_instagram', 'social_linkedin', 'social_youtube', 'social_whatsapp',
            'newsletter_title', 'newsletter_description', 'newsletter_button',
            'hero_title', 'hero_subtitle', 'hero_button_text', 'hero_button_link'
        ];

        foreach ($fields as $field) {
            Setting::set($field, $request->input($field, ''));
        }

        Setting::set('social_show_footer', $request->has('social_show_footer'));
        Setting::set('newsletter_enabled', $request->has('newsletter_enabled'));
        Setting::clearCache();

        return back()->with('success', 'Content settings updated successfully.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'site_phone' => 'required|string|max:50',
            'site_address' => 'required|string|max:500',
            'business_hours' => 'required|string|max:255',
            'facebook_url' => 'nullable|url|max:500',
            'twitter_url' => 'nullable|url|max:500',
            'linkedin_url' => 'nullable|url|max:500',
            'instagram_url' => 'nullable|url|max:500',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        Setting::clearCache();

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Show payment settings page
     */
    public function payment()
    {
        $settings = Setting::getAll();
        return view('admin.settings.payment', compact('settings'));
    }

    /**
     * Update payment settings
     */
    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'paystack_enabled' => 'nullable',
            'paystack_public_key' => 'nullable|string|max:255',
            'paystack_secret_key' => 'nullable|string|max:255',
            'paystack_test_mode' => 'nullable',
            'currency_auto_update' => 'nullable',
            'mzn_exchange_rate' => 'nullable|numeric|min:0.01',
        ]);

        // Store checkbox values as booleans
        Setting::set('paystack_enabled', $request->has('paystack_enabled'));
        Setting::set('paystack_test_mode', $request->has('paystack_test_mode'));
        Setting::set('currency_auto_update', $request->has('currency_auto_update'));

        // Store other values
        Setting::set('paystack_public_key', $validated['paystack_public_key'] ?? '');
        Setting::set('paystack_secret_key', $validated['paystack_secret_key'] ?? '');

        // Only save manual rate if auto-update is disabled
        if (!$request->has('currency_auto_update') && isset($validated['mzn_exchange_rate'])) {
            Setting::set('mzn_exchange_rate', $validated['mzn_exchange_rate']);
        }

        Setting::clearCache();

        return back()->with('success', 'Payment settings updated successfully.');
    }

    /**
     * Refresh currency exchange rate from API
     */
    public function refreshCurrency(CurrencyService $currencyService)
    {
        try {
            $rate = $currencyService->refreshExchangeRate();

            return response()->json([
                'success' => true,
                'rate' => $rate,
                'message' => 'Exchange rate refreshed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh exchange rate: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show About Us page settings
     */
    public function aboutUs()
    {
        $settings = Setting::getAll();
        return view('admin.settings.about-us', compact('settings'));
    }

    /**
     * Update About Us page settings
     */
    public function updateAboutUs(Request $request)
    {
        $fields = [
            'about_intro_title',
            'about_intro_text_1',
            'about_intro_text_2',
            'about_intro_text_3',
            'about_mission_title',
            'about_mission_text',
            'about_vision_title',
            'about_vision_text',
            'about_values_title',
            'about_values_text',
            'about_industries',
        ];

        foreach ($fields as $field) {
            Setting::set($field, $request->input($field, ''));
        }

        Setting::clearCache();

        return back()->with('success', 'About Us page updated successfully.');
    }
}
