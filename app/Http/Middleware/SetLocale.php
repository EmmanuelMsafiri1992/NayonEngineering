<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\GeoLocationService;

class SetLocale
{
    protected GeoLocationService $geoService;

    public function __construct(GeoLocationService $geoService)
    {
        $this->geoService = $geoService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if locale is already set in session
        if (!session()->has('locale') || !session()->has('currency') || !session()->has('country')) {
            $this->initializeLocaleFromGeo();
        }

        // Set application locale
        $locale = session('locale', 'en');
        app()->setLocale($locale);

        // Share locale data with all views
        view()->share('currentLocale', $locale);
        view()->share('currentCurrency', session('currency', 'ZAR'));
        view()->share('currentCountry', session('country', 'ZA'));
        view()->share('availableLanguages', $this->geoService->getAvailableLanguages(session('country')));
        view()->share('isSouthAfrica', $this->geoService->isSouthAfrica(session('country')));
        view()->share('isMozambique', $this->geoService->isMozambique(session('country')));

        return $next($request);
    }

    /**
     * Initialize locale settings from geo-location
     */
    protected function initializeLocaleFromGeo(): void
    {
        $country = $this->geoService->detectCountry();
        $config = $this->geoService->getCountryConfig($country);

        session([
            'country' => $country,
            'locale' => $config['locale'],
            'currency' => $config['currency'],
        ]);
    }
}
