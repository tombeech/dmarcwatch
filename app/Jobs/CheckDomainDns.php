<?php

namespace App\Jobs;

use App\Enums\DmarcEventType;
use App\Models\Domain;
use App\Services\AlertDispatcher;
use App\Services\DnsChecker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckDomainDns implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly Domain $domain,
    ) {
        $this->queue = 'dns-checking';
    }

    public function handle(DnsChecker $checker, AlertDispatcher $alertDispatcher): void
    {
        Log::info('[CheckDomainDns] Checking DNS for domain', ['domain' => $this->domain->name]);

        $oldDmarc = $this->domain->dmarc_record;
        $oldSpf = $this->domain->spf_record;

        $results = $checker->check($this->domain);

        $domain = $this->domain->fresh();

        if ($oldDmarc !== null && $oldDmarc !== $domain->dmarc_record) {
            $alertDispatcher->dispatch($domain->team_id, DmarcEventType::DNS_RECORD_CHANGE, [
                'domain_id' => $domain->id,
                'domain_name' => $domain->name,
                'record_type' => 'DMARC',
                'old_value' => $oldDmarc,
                'new_value' => $domain->dmarc_record,
            ]);
        }

        if ($oldSpf !== null && $oldSpf !== $domain->spf_record) {
            $alertDispatcher->dispatch($domain->team_id, DmarcEventType::DNS_RECORD_CHANGE, [
                'domain_id' => $domain->id,
                'domain_name' => $domain->name,
                'record_type' => 'SPF',
                'old_value' => $oldSpf,
                'new_value' => $domain->spf_record,
            ]);
        }

        Log::info('[CheckDomainDns] DNS check complete', [
            'domain' => $this->domain->name,
            'dmarc_valid' => $results['dmarc']['is_valid'] ?? false,
            'spf_valid' => $results['spf']['is_valid'] ?? false,
        ]);
    }
}
