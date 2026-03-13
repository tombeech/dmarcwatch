<?php

namespace App\Services;

use App\Models\DnsCheck;
use App\Models\Domain;

class DnsChecker
{
    public function __construct(
        protected DmarcAnalyzer $dmarcAnalyzer,
        protected SpfAnalyzer $spfAnalyzer,
        protected DkimVerifier $dkimVerifier,
    ) {}

    public function check(Domain $domain): array
    {
        $results = [];

        $dmarc = $this->dmarcAnalyzer->analyze($domain->name);
        $results['dmarc'] = $dmarc;
        DnsCheck::create([
            'domain_id' => $domain->id,
            'record_type' => 'dmarc',
            'record_value' => $dmarc['record'],
            'is_valid' => $dmarc['is_valid'],
            'issues' => array_merge($dmarc['issues'], $dmarc['warnings'] ?? []),
            'checked_at' => now(),
        ]);

        $spf = $this->spfAnalyzer->analyze($domain->name);
        $results['spf'] = $spf;
        DnsCheck::create([
            'domain_id' => $domain->id,
            'record_type' => 'spf',
            'record_value' => $spf['record'],
            'is_valid' => $spf['is_valid'],
            'issues' => array_merge($spf['issues'], $spf['warnings'] ?? []),
            'checked_at' => now(),
        ]);

        $selectors = $domain->dkim_selectors ?? ['default', 'google', 'selector1', 'selector2'];
        foreach ($selectors as $selector) {
            $dkim = $this->dkimVerifier->verify($domain->name, $selector);
            if ($dkim['found']) {
                $results['dkim'][$selector] = $dkim;
                DnsCheck::create([
                    'domain_id' => $domain->id,
                    'record_type' => 'dkim',
                    'record_value' => $dkim['record'],
                    'is_valid' => $dkim['is_valid'],
                    'issues' => $dkim['issues'],
                    'checked_at' => now(),
                ]);
            }
        }

        $domain->update([
            'dmarc_record' => $dmarc['record'],
            'spf_record' => $spf['record'],
            'dmarc_policy' => $dmarc['tags']['p'] ?? null,
            'spf_status' => $spf['is_valid'] ? 'valid' : 'invalid',
            'dkim_status' => ! empty($results['dkim']) ? 'valid' : 'missing',
            'last_dns_check_at' => now(),
            'next_dns_check_at' => now()->addMinutes($domain->dns_check_interval_minutes),
        ]);

        return $results;
    }
}
