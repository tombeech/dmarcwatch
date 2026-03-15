<?php

return [
    'stripe' => [
        'prices' => [
            'usd' => [
                'pro_monthly'        => env('STRIPE_PRO_MONTHLY_PRICE_USD', 'price_pro_monthly_usd'),
                'pro_yearly'         => env('STRIPE_PRO_YEARLY_PRICE_USD', 'price_pro_yearly_usd'),
                'enterprise_monthly' => env('STRIPE_ENTERPRISE_MONTHLY_PRICE_USD', 'price_enterprise_monthly_usd'),
                'enterprise_yearly'  => env('STRIPE_ENTERPRISE_YEARLY_PRICE_USD', 'price_enterprise_yearly_usd'),
                'domain_addon'       => env('STRIPE_DOMAIN_ADDON_PRICE_USD', 'price_domain_addon_usd'),
            ],
            'gbp' => [
                'pro_monthly'        => env('STRIPE_PRO_MONTHLY_PRICE_GBP', 'price_pro_monthly_gbp'),
                'pro_yearly'         => env('STRIPE_PRO_YEARLY_PRICE_GBP', 'price_pro_yearly_gbp'),
                'enterprise_monthly' => env('STRIPE_ENTERPRISE_MONTHLY_PRICE_GBP', 'price_enterprise_monthly_gbp'),
                'enterprise_yearly'  => env('STRIPE_ENTERPRISE_YEARLY_PRICE_GBP', 'price_enterprise_yearly_gbp'),
                'domain_addon'       => env('STRIPE_DOMAIN_ADDON_PRICE_GBP', 'price_domain_addon_gbp'),
            ],
            'eur' => [
                'pro_monthly'        => env('STRIPE_PRO_MONTHLY_PRICE_EUR', 'price_pro_monthly_eur'),
                'pro_yearly'         => env('STRIPE_PRO_YEARLY_PRICE_EUR', 'price_pro_yearly_eur'),
                'enterprise_monthly' => env('STRIPE_ENTERPRISE_MONTHLY_PRICE_EUR', 'price_enterprise_monthly_eur'),
                'enterprise_yearly'  => env('STRIPE_ENTERPRISE_YEARLY_PRICE_EUR', 'price_enterprise_yearly_eur'),
                'domain_addon'       => env('STRIPE_DOMAIN_ADDON_PRICE_EUR', 'price_domain_addon_eur'),
            ],
        ],
    ],

    'trial_days' => (int) env('DMARCWATCH_TRIAL_DAYS', 14),

    'dns' => [
        'resolvers' => ['8.8.8.8', '1.1.1.1'],
        'timeout'   => 5,
    ],

    'inbound_email' => [
        'domain'         => env('DMARCWATCH_INBOUND_DOMAIN', 'reports.dmarcwatch.app'),
        'mailgun_secret' => env('MAILGUN_INBOUND_SIGNING_KEY'),
    ],
];
