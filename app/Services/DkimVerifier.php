<?php

namespace App\Services;

class DkimVerifier
{
    public function verify(string $domain, string $selector): array
    {
        $host = $selector . '._domainkey.' . rtrim($domain, '.');
        $records = @dns_get_record($host, DNS_TXT);

        if (empty($records)) {
            return [
                'found' => false,
                'selector' => $selector,
                'record' => null,
                'tags' => [],
                'issues' => ["No DKIM record found at {$host}"],
                'is_valid' => false,
            ];
        }

        $txt = '';
        foreach ($records as $record) {
            $txt = $record['txt'] ?? '';
            if (str_contains($txt, 'v=DKIM1') || str_contains($txt, 'p=')) {
                break;
            }
        }

        $tags = $this->parseTags($txt);
        $issues = [];

        if (empty($tags['p'])) {
            $issues[] = 'Missing public key (p=) in DKIM record';
        }

        if (isset($tags['v']) && $tags['v'] !== 'DKIM1') {
            $issues[] = 'Invalid version tag: ' . $tags['v'];
        }

        if (isset($tags['k']) && ! in_array($tags['k'], ['rsa', 'ed25519'])) {
            $issues[] = 'Unsupported key type: ' . $tags['k'];
        }

        return [
            'found' => true,
            'selector' => $selector,
            'record' => $txt,
            'tags' => $tags,
            'issues' => $issues,
            'is_valid' => empty($issues),
        ];
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
