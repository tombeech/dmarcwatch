<?php

return [
    'stripe' => [
        'prices' => [
            'pro_monthly'        => env('STRIPE_PRO_MONTHLY_PRICE'),
            'enterprise_monthly' => env('STRIPE_ENTERPRISE_MONTHLY_PRICE'),
            'domain_addon'       => env('STRIPE_DOMAIN_ADDON_PRICE'),
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
