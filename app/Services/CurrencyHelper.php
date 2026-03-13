<?php

namespace App\Services;

class CurrencyHelper
{
    public static function pricing(): array
    {
        return [
            'symbol' => '£',
            'currency' => 'GBP',
            'pro' => 24,
            'enterprise' => 79,
            'domain_addon' => 20,
        ];
    }

    public static function symbol(): string
    {
        return '£';
    }
}
