<?php

namespace App\Jobs;

use App\Jobs\CheckDomainDns;
use App\Models\Domain;
use Illuminate\Support\Facades\Log;

class ScheduleDnsChecks
{
    public function handle(): void
    {
        $domains = Domain::withoutGlobalScope('team')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('next_dns_check_at')
                    ->orWhere('next_dns_check_at', '<=', now());
            })
            ->get();

        Log::info('[ScheduleDnsChecks] Dispatching DNS checks', ['count' => $domains->count()]);

        foreach ($domains as $domain) {
            CheckDomainDns::dispatch($domain);
        }
    }
}
