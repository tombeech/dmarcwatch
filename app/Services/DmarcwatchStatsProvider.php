<?php

namespace App\Services;

use PermissionEmail\SaasManagement\Contracts\AppStatsProvider;

class DmarcwatchStatsProvider implements AppStatsProvider
{
    public function getStats(): array
    {
        $stats = [
            'total_domains' => 0,
            'reports_this_month' => 0,
        ];

        // Try to find Domain model
        if (class_exists(\App\Models\Domain::class)) {
            $stats['total_domains'] = \App\Models\Domain::withoutGlobalScopes()->count();
        }

        // Try to find Report/DmarcReport model
        if (class_exists(\App\Models\DmarcReport::class)) {
            $stats['reports_this_month'] = \App\Models\DmarcReport::withoutGlobalScopes()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        } elseif (class_exists(\App\Models\Report::class)) {
            $stats['reports_this_month'] = \App\Models\Report::withoutGlobalScopes()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        return $stats;
    }
}
