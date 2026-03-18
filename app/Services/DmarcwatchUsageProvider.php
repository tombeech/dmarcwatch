<?php

namespace App\Services;

use PermissionEmail\SaasManagement\Contracts\UsageProvider;

class DmarcwatchUsageProvider implements UsageProvider
{
    public function getUsage(int $teamId): array
    {
        $usage = [];

        if (class_exists(\App\Models\Domain::class)) {
            $usage['domains'] = \App\Models\Domain::withoutGlobalScopes()->where('team_id', $teamId)->count();
        }

        return $usage;
    }
}
