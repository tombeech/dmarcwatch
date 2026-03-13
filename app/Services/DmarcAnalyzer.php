<?php

namespace App\Services;

class DmarcAnalyzer
{
    public function analyze(string $domain): array
    {
        $record = $this->queryDmarc($domain);

        if ($record === null) {
            return [
                'found' => false,
                'record' => null,
                'tags' => [],
                'issues' => ['No DMARC record found at _dmarc.' . $domain],
                'warnings' => [],
                'is_valid' => false,
            ];
        }

        $tags = $this->parseTags($record);
        $issues = [];
        $warnings = [];

        if (empty($tags['v']) || $tags['v'] !== 'DMARC1') {
            $issues[] = 'Missing or invalid version tag (v=DMARC1 required)';
        }

        if (empty($tags['p'])) {
            $issues[] = 'Missing policy tag (p=)';
        } elseif (! in_array($tags['p'], ['none', 'quarantine', 'reject'])) {
            $issues[] = 'Invalid policy: ' . $tags['p'];
        }

        if (($tags['p'] ?? '') === 'none') {
            $warnings[] = 'Policy is set to "none" — no enforcement. Consider quarantine or reject.';
        }

        if (empty($tags['rua'])) {
            $warnings[] = 'No aggregate report URI (rua) specified.';
        }

        if (isset($tags['pct']) && ((int) $tags['pct'] < 100)) {
            $warnings[] = 'Percentage (pct) is less than 100% — not all messages are covered.';
        }

        if (isset($tags['adkim']) && ! in_array($tags['adkim'], ['r', 's'])) {
            $issues[] = 'Invalid DKIM alignment mode: ' . $tags['adkim'];
        }

        if (isset($tags['aspf']) && ! in_array($tags['aspf'], ['r', 's'])) {
            $issues[] = 'Invalid SPF alignment mode: ' . $tags['aspf'];
        }

        return [
            'found' => true,
            'record' => $record,
            'tags' => $tags,
            'issues' => $issues,
            'warnings' => $warnings,
            'is_valid' => empty($issues),
        ];
    }

    protected function queryDmarc(string $domain): ?string
    {
        $host = '_dmarc.' . rtrim($domain, '.');
        $records = @dns_get_record($host, DNS_TXT);

        if (empty($records)) {
            return null;
        }

        foreach ($records as $record) {
            $txt = $record['txt'] ?? '';
            if (str_starts_with(trim($txt), 'v=DMARC1')) {
                return $txt;
            }
        }

        return null;
    }

    protected function parseTags(string $record): array
    {
        $tags = [];
        $parts = explode(';', $record);

        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }

            $eq = strpos($part, '=');
            if ($eq === false) {
                continue;
            }

            $key = trim(substr($part, 0, $eq));
            $value = trim(substr($part, $eq + 1));
            $tags[$key] = $value;
        }

        return $tags;
    }
}
