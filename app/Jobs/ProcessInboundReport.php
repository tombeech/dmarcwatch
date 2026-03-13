<?php

namespace App\Jobs;

use App\Models\DmarcReport;
use App\Models\Domain;
use App\Models\ReportRecord;
use App\Services\ComplianceScorer;
use App\Services\ReportParser;
use App\Services\SendingSourceResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessInboundReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly Domain $domain,
        public readonly string $xml,
    ) {
        $this->queue = 'report-processing';
    }

    public function handle(ReportParser $parser, SendingSourceResolver $sourceResolver, ComplianceScorer $scorer): void
    {
        Log::info('[ProcessInboundReport] Processing report for domain', ['domain' => $this->domain->name]);

        try {
            $parsed = $parser->parse($this->xml);
        } catch (\Throwable $e) {
            Log::error('[ProcessInboundReport] Failed to parse XML', [
                'domain' => $this->domain->name,
                'error' => $e->getMessage(),
            ]);
            return;
        }

        $metadata = $parsed['metadata'];

        $existing = DmarcReport::withoutGlobalScope('team')
            ->where('domain_id', $this->domain->id)
            ->where('report_id', $metadata['report_id'])
            ->where('reporter_org', $metadata['org_name'])
            ->first();

        if ($existing) {
            Log::info('[ProcessInboundReport] Duplicate report, skipping', [
                'report_id' => $metadata['report_id'],
            ]);
            return;
        }

        $report = DmarcReport::create([
            'team_id' => $this->domain->team_id,
            'domain_id' => $this->domain->id,
            'report_id' => $metadata['report_id'],
            'reporter_org' => $metadata['org_name'],
            'reporter_email' => $metadata['email'],
            'date_begin' => Carbon::createFromTimestamp($metadata['date_begin']),
            'date_end' => Carbon::createFromTimestamp($metadata['date_end']),
            'domain_policy' => $parsed['policy']['p'],
            'subdomain_policy' => $parsed['policy']['sp'],
            'pct' => $parsed['policy']['pct'] ? (int) $parsed['policy']['pct'] : null,
            'total_messages' => $parsed['total_messages'],
            'pass_count' => $parsed['pass_count'],
            'fail_count' => $parsed['fail_count'],
            'raw_xml' => $this->xml,
            'received_at' => now(),
            'processed_at' => now(),
        ]);

        foreach ($parsed['records'] as $record) {
            $source = $sourceResolver->resolve($record['source_ip']);

            ReportRecord::create([
                'dmarc_report_id' => $report->id,
                'source_ip' => $record['source_ip'],
                'count' => $record['count'],
                'disposition' => $record['disposition'],
                'dkim_result' => $record['dkim_result'],
                'spf_result' => $record['spf_result'],
                'dkim_domain' => $record['dkim_domain'],
                'spf_domain' => $record['spf_domain'],
                'dkim_aligned' => $record['dkim_auth_result'] === 'pass',
                'spf_aligned' => $record['spf_auth_result'] === 'pass',
                'header_from' => $record['header_from'],
                'envelope_from' => $record['envelope_from'],
                'sending_source_id' => $source?->id,
            ]);
        }

        $scorer->score($this->domain->fresh());

        Log::info('[ProcessInboundReport] Report processed successfully', [
            'report_id' => $report->id,
            'total_messages' => $parsed['total_messages'],
        ]);
    }
}
