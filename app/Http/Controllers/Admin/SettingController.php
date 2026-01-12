<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();
        return view('admin.settings.index', compact('settings'));
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
            'mzn_exchange_rate' => 'required|numeric|min:0.01',
        ]);

        // Store checkbox values as booleans
        Setting::set('paystack_enabled', $request->has('paystack_enabled'));
        Setting::set('paystack_test_mode', $request->has('paystack_test_mode'));

        // Store other values
        Setting::set('paystack_public_key', $validated['paystack_public_key'] ?? '');
        Setting::set('paystack_secret_key', $validated['paystack_secret_key'] ?? '');
        Setting::set('mzn_exchange_rate', $validated['mzn_exchange_rate']);

        Setting::clearCache();

        return back()->with('success', 'Payment settings updated successfully.');
    }
}
