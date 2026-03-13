<?php

namespace App\Services;

class SpfRecordGenerator
{
    protected int $lookupCount = 0;

    public function generate(array $options): array
    {
        $this->lookupCount = 0;
        $parts = ['v=spf1'];

        foreach ($options['includes'] ?? [] as $include) {
            $parts[] = 'include:' . $include;
            $this->lookupCount++;
        }

        foreach ($options['ip4'] ?? [] as $ip) {
            $parts[] = 'ip4:' . $ip;
        }

        foreach ($options['ip6'] ?? [] as $ip) {
            $parts[] = 'ip6:' . $ip;
        }

        if (! empty($options['mx'])) {
            $parts[] = 'mx';
            $this->lookupCount++;
        }

        if (! empty($options['a'])) {
            $parts[] = 'a';
            $this->lookupCount++;
        }

        $all = $options['all'] ?? '-all';
        $parts[] = $all;

        $record = implode(' ', $parts);
        $warnings = [];

        if ($this->lookupCount > 10) {
            $warnings[] = "SPF record requires {$this->lookupCount} DNS lookups, exceeding the limit of 10.";
        } elseif ($this->lookupCount > 7) {
            $warnings[] = "SPF record uses {$this->lookupCount}/10 DNS lookups. Getting close to the limit.";
        }

        if (strlen($record) > 255) {
            $warnings[] = 'SPF record exceeds 255 characters. Some DNS providers may require splitting.';
        }

        return [
            'record' => $record,
            'lookup_count' => $this->lookupCount,
            'warnings' => $warnings,
        ];
    }
}
