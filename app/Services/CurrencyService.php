<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    /**
     * Free exchange rate API endpoints
     */
    protected array $apiEndpoints = [
        'exchangerate' => 'https://open.er-api.com/v6/latest/',
        'frankfurter' => 'https://api.frankfurter.app/latest?from=',
    ];

    /**
     * Currency configurations
     */
    protected array $currencies = [
        'ZAR' => [
            'symbol' => 'R',
            'name' => 'South African Rand',
            'decimal_places' => 2,
            'thousand_separator' => ' ',
            'decimal_separator' => '.',
        ],
        'MZN' => [
            'symbol' => 'MT',
            'name' => 'Mozambican Metical',
            'decimal_places' => 2,
            'thousand_separator' => ' ',
            'decimal_separator' => ',',
        ],
    ];

    /**
     * Get current currency code from session
     */
    public function getCurrentCurrency(): string
    {
        return session('currency', 'ZAR');
    }

    /**
     * Get base exchange rate for MZN (MZN per 1 ZAR)
     * Automatically fetches from API if auto mode is enabled
     */
    public function getExchangeRate(): float
    {
        $autoMode = Setting::get('currency_auto_update', true);

        if ($autoMode) {
            return $this->getAutoExchangeRate();
        }

        return (float) Setting::get('mzn_exchange_rate', 3.5);
    }

    /**
     * Get the markup percentage applied to exchange rate
     */
    public function getMarkupPercentage(): float
    {
        return (float) Setting::get('exchange_rate_markup', 0);
    }

    /**
     * Get effective exchange rate with markup applied
     */
    public function getEffectiveExchangeRate(): float
    {
        $baseRate = $this->getExchangeRate();
        $markup = $this->getMarkupPercentage();

        return $baseRate * (1 + $markup / 100);
    }

    /**
     * Fetch exchange rate automatically from free API
     * Cached for 6 hours to avoid hitting rate limits
     */
    public function getAutoExchangeRate(): float
    {
        return Cache::remember('auto_mzn_exchange_rate', now()->addHours(6), function () {
            $rate = $this->fetchExchangeRateFromApi('ZAR', 'MZN');

            if ($rate) {
                // Also update the stored rate as backup
                Setting::set('mzn_exchange_rate', $rate);
                return $rate;
            }

            // Fallback to stored rate if API fails
            return (float) Setting::get('mzn_exchange_rate', 3.5);
        });
    }

    /**
     * Fetch exchange rate from free API
     */
    protected function fetchExchangeRateFromApi(string $from, string $to): ?float
    {
        // Try primary API (exchangerate-api - more currencies)
        $rate = $this->fetchFromExchangeRateApi($from, $to);

        if ($rate) {
            return $rate;
        }

        // Fallback to frankfurter API
        $rate = $this->fetchFromFrankfurterApi($from, $to);

        if ($rate) {
            return $rate;
        }

        Log::warning("Failed to fetch exchange rate from all APIs for {$from} to {$to}");
        return null;
    }

    /**
     * Fetch from open.er-api.com (free, no key required)
     */
    protected function fetchFromExchangeRateApi(string $from, string $to): ?float
    {
        try {
            $response = Http::timeout(5)
                ->get($this->apiEndpoints['exchangerate'] . $from);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['rates'][$to])) {
                    $rate = (float) $data['rates'][$to];
                    Log::info("Exchange rate fetched from ExchangeRate API: 1 {$from} = {$rate} {$to}");
                    return $rate;
                }
            }
        } catch (\Exception $e) {
            Log::warning("ExchangeRate API error: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Fetch from frankfurter.app (free, no key required)
     */
    protected function fetchFromFrankfurterApi(string $from, string $to): ?float
    {
        try {
            $response = Http::timeout(5)
                ->get($this->apiEndpoints['frankfurter'] . $from . '&to=' . $to);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['rates'][$to])) {
                    $rate = (float) $data['rates'][$to];
                    Log::info("Exchange rate fetched from Frankfurter API: 1 {$from} = {$rate} {$to}");
                    return $rate;
                }
            }
        } catch (\Exception $e) {
            Log::warning("Frankfurter API error: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Force refresh exchange rate from API
     */
    public function refreshExchangeRate(): float
    {
        Cache::forget('auto_mzn_exchange_rate');
        return $this->getAutoExchangeRate();
    }

    /**
     * Get all exchange rates for supported currencies
     */
    public function getAllExchangeRates(string $baseCurrency = 'ZAR'): array
    {
        return Cache::remember("all_exchange_rates_{$baseCurrency}", now()->addHours(6), function () use ($baseCurrency) {
            try {
                $response = Http::timeout(5)
                    ->get($this->apiEndpoints['exchangerate'] . $baseCurrency);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['rates'] ?? [];
                }
            } catch (\Exception $e) {
                Log::warning("Failed to fetch all exchange rates: " . $e->getMessage());
            }

            return [];
        });
    }

    /**
     * Get last update time for exchange rate
     */
    public function getLastUpdateTime(): ?string
    {
        return Cache::get('exchange_rate_last_update');
    }

    /**
     * Convert amount from ZAR to specified currency
     * Uses the effective exchange rate with markup applied
     */
    public function convert(float $amount, string $toCurrency, string $fromCurrency = 'ZAR'): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rate = $this->getEffectiveExchangeRate();

        if ($fromCurrency === 'ZAR' && $toCurrency === 'MZN') {
            return $amount * $rate;
        }

        if ($fromCurrency === 'MZN' && $toCurrency === 'ZAR') {
            return $amount / $rate;
        }

        return $amount;
    }

    /**
     * Format price with currency symbol
     */
    public function format(float $amount, ?string $currency = null): string
    {
        $currency = $currency ?? $this->getCurrentCurrency();
        $config = $this->currencies[$currency] ?? $this->currencies['ZAR'];

        $formatted = number_format(
            $amount,
            $config['decimal_places'],
            $config['decimal_separator'],
            $config['thousand_separator']
        );

        return $config['symbol'] . ' ' . $formatted;
    }

    /**
     * Format and convert price from ZAR to current currency
     */
    public function formatPrice(float $amountInZar): string
    {
        $currency = $this->getCurrentCurrency();
        $converted = $this->convert($amountInZar, $currency);

        return $this->format($converted, $currency);
    }

    /**
     * Get currency configuration
     */
    public function getCurrencyConfig(?string $currency = null): array
    {
        $currency = $currency ?? $this->getCurrentCurrency();
        return $this->currencies[$currency] ?? $this->currencies['ZAR'];
    }

    /**
     * Get all supported currencies
     */
    public function getSupportedCurrencies(): array
    {
        return $this->currencies;
    }
}
