<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
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
     * Get exchange rate for MZN (MZN per 1 ZAR)
     */
    public function getExchangeRate(): float
    {
        return Cache::remember('mzn_exchange_rate', now()->addHours(1), function () {
            return (float) Setting::get('mzn_exchange_rate', 3.5);
        });
    }

    /**
     * Convert amount from ZAR to specified currency
     */
    public function convert(float $amount, string $toCurrency, string $fromCurrency = 'ZAR'): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rate = $this->getExchangeRate();

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
