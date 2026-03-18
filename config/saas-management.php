<?php

return [
    'app_identifier' => env('SAAS_MANAGEMENT_APP_ID', 'dmarcwatch'),
    'app_name'       => env('SAAS_MANAGEMENT_APP_NAME', 'DMARCWatch'),
    'api_token'      => env('MANAGEMENT_API_TOKEN'),
    'billing_model'  => 'plan',
    'plans'          => ['free', 'pro', 'enterprise'],
    'usage_metric'   => 'domains',
    'usage_label'    => 'Domains',
    'stats_provider' => \App\Services\DmarcwatchStatsProvider::class,
    'usage_provider' => \App\Services\DmarcwatchUsageProvider::class,
];
