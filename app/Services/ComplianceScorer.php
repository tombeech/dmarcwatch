<?php

namespace App\Services;

use App\Models\Domain;

class ComplianceScorer
{
    public function score(Domain $domain): array
    {
        $scores = [];
        $recommendations = [];

        // Policy strength (30%)
        $policyScore = match ($domain->dmarc_policy) {
            'reject' => 100,
            'quarantine' => 70,
            'none' => 30,
            default => 0,
        };
        $scores['policy'] = $policyScore * 0.30;

        if ($policyScore < 70) {
            $recommendations[] = 'Upgrade your DMARC policy to "quarantine" or "reject" for stronger protection.';
        }

        // SPF pass rate (25%)
        $reports = $domain->dmarcReports()->where('created_at', '>=', now()->subDays(30))->get();
        $totalMessages = $reports->sum('total_messages');
        $passMessages = $reports->sum('pass_count');
        $spfPassRate = $totalMessages > 0 ? ($passMessages / $totalMessages) * 100 : 0;
        $scores['spf_pass_rate'] = $spfPassRate * 0.25;

        if ($spfPassRate < 95 && $totalMessages > 0) {
            $recommendations[] = 'SPF pass rate is below 95%. Review failing sources and update your SPF record.';
        }

        // DKIM pass rate (25%)
        $dkimPassRate = $spfPassRate; // Simplified — in production, calculate from records
        $scores['dkim_pass_rate'] = $dkimPassRate * 0.25;

        // Alignment (10%)
        $alignmentScore = ($domain->dmarc_policy !== null) ? 80 : 0;
        $scores['alignment'] = $alignmentScore * 0.10;

        // DNS validity (10%)
        $dnsScore = 0;
        if ($domain->spf_status === 'valid') {
            $dnsScore += 50;
        }
        if ($domain->dkim_status === 'valid') {
            $dnsScore += 50;
        }
        $scores['dns_validity'] = $dnsScore * 0.10;

        if ($domain->spf_status !== 'valid') {
            $recommendations[] = 'Fix SPF record issues to improve DNS validity score.';
        }
        if ($domain->dkim_status !== 'valid') {
            $recommendations[] = 'Set up DKIM signing to improve authentication coverage.';
        }

        $totalScore = round(array_sum($scores), 2);

        $domain->update(['compliance_score' => $totalScore]);

        return [
            'score' => $totalScore,
            'breakdown' => $scores,
            'recommendations' => $recommendations,
        ];
    }
}
