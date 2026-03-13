<?php

namespace App\Services;

class DmarcRecordGenerator
{
    public function generate(array $options): string
    {
        $parts = ['v=DMARC1'];

        $parts[] = 'p=' . ($options['policy'] ?? 'none');

        if (! empty($options['subdomain_policy'])) {
            $parts[] = 'sp=' . $options['subdomain_policy'];
        }

        if (! empty($options['rua'])) {
            $rua = is_array($options['rua']) ? $options['rua'] : [$options['rua']];
            $parts[] = 'rua=' . implode(',', array_map(fn ($r) => 'mailto:' . $r, $rua));
        }

        if (! empty($options['ruf'])) {
            $ruf = is_array($options['ruf']) ? $options['ruf'] : [$options['ruf']];
            $parts[] = 'ruf=' . implode(',', array_map(fn ($r) => 'mailto:' . $r, $ruf));
        }

        if (isset($options['pct']) && (int) $options['pct'] < 100) {
            $parts[] = 'pct=' . (int) $options['pct'];
        }

        if (! empty($options['adkim'])) {
            $parts[] = 'adkim=' . $options['adkim'];
        }

        if (! empty($options['aspf'])) {
            $parts[] = 'aspf=' . $options['aspf'];
        }

        if (! empty($options['fo'])) {
            $parts[] = 'fo=' . $options['fo'];
        }

        if (! empty($options['ri'])) {
            $parts[] = 'ri=' . (int) $options['ri'];
        }

        return implode('; ', $parts);
    }
}
