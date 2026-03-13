<?php

namespace App\Services;

class SpfAnalyzer
{
    protected int $lookupCount = 0;
    protected int $maxLookups = 10;

    public function analyze(string $domain): array
    {
        $this->lookupCount = 0;
        $record = $this->querySpf($domain);

        if ($record === null) {
            return [
                'found' => false,
                'record' => null,
                'mechanisms' => [],
                'lookup_count' => 0,
                'max_lookups' => $this->maxLookups,
                'issues' => ['No SPF record found for ' . $domain],
                'warnings' => [],
                'is_valid' => false,
                'includes' => [],
            ];
        }

        $mechanisms = $this->parseMechanisms($record);
        $issues = [];
        $warnings = [];
        $includes = [];

        if (! str_starts_with(trim($record), 'v=spf1')) {
            $issues[] = 'SPF record must start with v=spf1';
        }

        foreach ($mechanisms as $mech) {
            if ($mech['type'] === 'include') {
                $this->lookupCount++;
                $includes[] = $mech['value'];
            } elseif (in_array($mech['type'], ['a', 'mx', 'ptr', 'exists', 'redirect'])) {
                $this->lookupCount++;
            }
        }

        if ($this->lookupCount > $this->maxLookups) {
            $issues[] = "Too many DNS lookups ({$this->lookupCount}/{$this->maxLookups}). SPF will permerror.";
        } elseif ($this->lookupCount > 7) {
            $warnings[] = "DNS lookup count is {$this->lookupCount}/{$this->maxLookups}. Getting close to the limit.";
        }

        $allMech = collect($mechanisms)->firstWhere('type', 'all');
        if (! $allMech) {
            $warnings[] = 'No "all" mechanism found. Consider adding "-all" or "~all".';
        } elseif ($allMech['qualifier'] === '+') {
            $issues[] = '"all" mechanism with "+" qualifier allows all senders. Use "-all" or "~all" instead.';
        }

        return [
            'found' => true,
            'record' => $record,
            'mechanisms' => $mechanisms,
            'lookup_count' => $this->lookupCount,
            'max_lookups' => $this->maxLookups,
            'issues' => $issues,
            'warnings' => $warnings,
            'is_valid' => empty($issues),
            'includes' => $includes,
        ];
    }

    protected function querySpf(string $domain): ?string
    {
        $records = @dns_get_record(rtrim($domain, '.'), DNS_TXT);

        if (empty($records)) {
            return null;
        }

        foreach ($records as $record) {
            $txt = $record['txt'] ?? '';
            if (str_starts_with(trim($txt), 'v=spf1')) {
                return $txt;
            }
        }

        return null;
    }

    protected function parseMechanisms(string $record): array
    {
        $mechanisms = [];
        $parts = preg_split('/\s+/', trim($record));

        foreach ($parts as $part) {
            if ($part === 'v=spf1') {
                continue;
            }

            $qualifier = '+';
            if (in_array($part[0] ?? '', ['+', '-', '~', '?'])) {
                $qualifier = $part[0];
                $part = substr($part, 1);
            }

            $colonPos = strpos($part, ':');
            $slashPos = strpos($part, '/');

            if ($colonPos !== false) {
                $type = substr($part, 0, $colonPos);
                $value = substr($part, $colonPos + 1);
            } elseif ($slashPos !== false) {
                $type = substr($part, 0, $slashPos);
                $value = substr($part, $slashPos);
            } else {
                $type = $part;
                $value = null;
            }

            $mechanisms[] = [
                'qualifier' => $qualifier,
                'type' => strtolower($type),
                'value' => $value,
                'raw' => ($qualifier !== '+' ? $qualifier : '') . $part,
            ];
        }

        return $mechanisms;
    }
}
