<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeoLocationService
{
    /**
     * South African country code
     */
    const COUNTRY_ZA = 'ZA';

    /**
     * Mozambique country code
     */
    const COUNTRY_MZ = 'MZ';

    /**
     * Supported countries with their default locales and currencies
     */
    protected array $countries = [
        'ZA' => [
            'locale' => 'en',
            'currency' => 'ZAR',
            'name' => 'South Africa',
        ],
        'MZ' => [
            'locale' => 'pt',
            'currency' => 'MZN',
            'name' => 'Mozambique',
        ],
    ];

    /**
     * South African official languages
     */
    protected array $saLanguages = [
        'en' => 'English',
        'af' => 'Afrikaans',
        'zu' => 'isiZulu',
        'xh' => 'isiXhosa',
        'st' => 'Sesotho',
        'tn' => 'Setswana',
        'ts' => 'Xitsonga',
        'ss' => 'siSwati',
        've' => 'Tshivenḓa',
        'nr' => 'isiNdebele',
        'nso' => 'Sepedi',
    ];

    /**
     * Detect country from IP address
     */
    public function detectCountry(?string $ip = null): string
    {
        $ip = $ip ?? request()->ip();

        // Skip detection for localhost
        if ($this->isLocalhost($ip)) {
            return self::COUNTRY_ZA; // Default to South Africa for local development
        }

        $cacheKey = "geo_country_{$ip}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($ip) {
            return $this->fetchCountryFromApi($ip);
        });
    }

    /**
     * Get country configuration
     */
    public function getCountryConfig(?string $countryCode = null): array
    {
        $countryCode = $countryCode ?? $this->detectCountry();

        return $this->countries[$countryCode] ?? $this->countries[self::COUNTRY_ZA];
    }

    /**
     * Get default locale for country
     */
    public function getDefaultLocale(?string $countryCode = null): string
    {
        return $this->getCountryConfig($countryCode)['locale'];
    }

    /**
     * Get currency for country
     */
    public function getCurrency(?string $countryCode = null): string
    {
        return $this->getCountryConfig($countryCode)['currency'];
    }

    /**
     * Get available languages for country
     */
    public function getAvailableLanguages(?string $countryCode = null): array
    {
        $countryCode = $countryCode ?? $this->detectCountry();

        if ($countryCode === self::COUNTRY_ZA) {
            return $this->saLanguages;
        }

        // Mozambique only has Portuguese
        return ['pt' => 'Português'];
    }

    /**
     * Check if country is South Africa
     */
    public function isSouthAfrica(?string $countryCode = null): bool
    {
        $countryCode = $countryCode ?? $this->detectCountry();
        return $countryCode === self::COUNTRY_ZA;
    }

    /**
     * Check if country is Mozambique
     */
    public function isMozambique(?string $countryCode = null): bool
    {
        $countryCode = $countryCode ?? $this->detectCountry();
        return $countryCode === self::COUNTRY_MZ;
    }

    /**
     * Fetch country from external API
     */
    protected function fetchCountryFromApi(string $ip): string
    {
        try {
            // Using ip-api.com (free, no API key required)
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}", [
                'fields' => 'countryCode',
            ]);

            if ($response->successful()) {
                $countryCode = $response->json('countryCode');

                // Only return if it's a supported country
                if (array_key_exists($countryCode, $this->countries)) {
                    return $countryCode;
                }
            }
        } catch (\Exception $e) {
            Log::warning("GeoLocation API failed: " . $e->getMessage());
        }

        // Default to South Africa if detection fails or country not supported
        return self::COUNTRY_ZA;
    }

    /**
     * Check if IP is localhost
     */
    protected function isLocalhost(string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1', 'localhost']) ||
               str_starts_with($ip, '192.168.') ||
               str_starts_with($ip, '10.') ||
               str_starts_with($ip, '172.');
    }
}
