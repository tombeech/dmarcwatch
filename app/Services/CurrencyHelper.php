<?php

namespace App\Services;

class CurrencyHelper
{
    protected static array $pricing = [
        'GBP' => ['symbol' => '£', 'pro' => 19, 'enterprise' => 59, 'domain_addon' => 15],
        'EUR' => ['symbol' => '€', 'pro' => 22, 'enterprise' => 69, 'domain_addon' => 18],
        'USD' => ['symbol' => '$', 'pro' => 24, 'enterprise' => 79, 'domain_addon' => 20],
    ];

    protected static array $countryToCurrency = [
        'GB' => 'GBP',
        'AT' => 'EUR', 'BE' => 'EUR', 'CY' => 'EUR', 'DE' => 'EUR',
        'EE' => 'EUR', 'ES' => 'EUR', 'FI' => 'EUR', 'FR' => 'EUR',
        'GR' => 'EUR', 'HR' => 'EUR', 'IE' => 'EUR', 'IT' => 'EUR',
        'LT' => 'EUR', 'LU' => 'EUR', 'LV' => 'EUR', 'MT' => 'EUR',
        'NL' => 'EUR', 'PT' => 'EUR', 'SI' => 'EUR', 'SK' => 'EUR',
    ];

    public static function detect(): string
    {
        if ($cached = session('currency')) {
            return $cached;
        }

        $country = request()->header('CF-IPCountry')
            ?? request()->header('X-Country-Code')
            ?? request()->server('HTTP_CF_IPCOUNTRY');

        if (! $country) {
            $country = static::countryFromAcceptLanguage();
        }

        $country = strtoupper((string) $country);
        $currency = static::$countryToCurrency[$country] ?? 'USD';

        session(['currency' => $currency]);

        return $currency;
    }

    public static function symbol(?string $currency = null): string
    {
        $currency ??= static::detect();
        return static::$pricing[$currency]['symbol'] ?? '$';
    }

    public static function pricing(?string $currency = null): array
    {
        $currency ??= static::detect();
        $data = static::$pricing[$currency] ?? static::$pricing['USD'];
        return array_merge($data, ['currency' => $currency]);
    }

    public static function stripeCurrencySuffix(?string $currency = null): string
    {
        $currency ??= static::detect();
        return strtolower($currency);
    }

    protected static function countryFromAcceptLanguage(): ?string
    {
        $header = request()->header('Accept-Language', '');

        if (preg_match('/[a-z]{2}-([A-Z]{2})/', $header, $matches)) {
            return $matches[1];
        }

        $langMap = [
            'en' => 'US', 'de' => 'DE', 'fr' => 'FR', 'es' => 'ES',
            'it' => 'IT', 'nl' => 'NL', 'pt' => 'PT', 'el' => 'GR',
            'fi' => 'FI', 'et' => 'EE', 'lv' => 'LV', 'lt' => 'LT',
            'sk' => 'SK', 'sl' => 'SI', 'mt' => 'MT', 'ga' => 'IE',
        ];

        if (preg_match('/^([a-z]{2})/', $header, $matches)) {
            return $langMap[$matches[1]] ?? null;
        }

        return null;
    }
}
