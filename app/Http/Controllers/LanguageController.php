<?php

namespace App\Http\Controllers;

use App\Services\GeoLocationService;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    protected GeoLocationService $geoService;

    protected array $supportedLocales = [
        'en', 'af', 'zu', 'xh', 'st', 'tn', 'ts', 'ss', 've', 'nr', 'nso', 'pt'
    ];

    public function __construct(GeoLocationService $geoService)
    {
        $this->geoService = $geoService;
    }

    /**
     * Switch the application language
     */
    public function switch(Request $request, string $locale)
    {
        // Validate locale
        if (!in_array($locale, $this->supportedLocales)) {
            return redirect()->back()->with('error', 'Unsupported language.');
        }

        // Get available languages for current country
        $country = session('country', 'ZA');
        $availableLanguages = $this->geoService->getAvailableLanguages($country);

        // Check if locale is available for current country
        if (!array_key_exists($locale, $availableLanguages)) {
            return redirect()->back()->with('error', 'Language not available in your region.');
        }

        // Update session
        session(['locale' => $locale]);

        // Set application locale
        app()->setLocale($locale);

        return redirect()->back();
    }

    /**
     * Get available languages for the current region
     */
    public function getLanguages()
    {
        $country = session('country', 'ZA');
        $languages = $this->geoService->getAvailableLanguages($country);
        $currentLocale = session('locale', 'en');

        return response()->json([
            'languages' => $languages,
            'current' => $currentLocale,
            'country' => $country,
        ]);
    }

    /**
     * Switch the country and currency
     */
    public function switchCountry(Request $request, string $country)
    {
        $supportedCountries = ['ZA', 'MZ'];

        // Validate country
        if (!in_array($country, $supportedCountries)) {
            return redirect()->back()->with('error', 'Unsupported country.');
        }

        // Get country config
        $config = $this->geoService->getCountryConfig($country);

        // Update session with new country, currency, and default locale
        session([
            'country' => $country,
            'currency' => $config['currency'],
            'locale' => $config['locale'],
        ]);

        // Set application locale
        app()->setLocale($config['locale']);

        return redirect()->back();
    }
}
