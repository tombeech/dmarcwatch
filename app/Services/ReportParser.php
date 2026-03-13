<?php

namespace App\Services;

class ReportParser
{
    public function parse(string $xml): array
    {
        $dom = new \DOMDocument();
        if (! @$dom->loadXML($xml)) {
            throw new \RuntimeException('Failed to parse DMARC report XML');
        }

        $xpath = new \DOMXPath($dom);

        $metadata = [
            'org_name' => $this->xpathValue($xpath, '//report_metadata/org_name'),
            'email' => $this->xpathValue($xpath, '//report_metadata/email'),
            'report_id' => $this->xpathValue($xpath, '//report_metadata/report_id'),
            'date_begin' => (int) $this->xpathValue($xpath, '//report_metadata/date_range/begin'),
            'date_end' => (int) $this->xpathValue($xpath, '//report_metadata/date_range/end'),
        ];

        $policy = [
            'domain' => $this->xpathValue($xpath, '//policy_published/domain'),
            'adkim' => $this->xpathValue($xpath, '//policy_published/adkim'),
            'aspf' => $this->xpathValue($xpath, '//policy_published/aspf'),
            'p' => $this->xpathValue($xpath, '//policy_published/p'),
            'sp' => $this->xpathValue($xpath, '//policy_published/sp'),
            'pct' => $this->xpathValue($xpath, '//policy_published/pct'),
        ];

        $records = [];
        $recordNodes = $xpath->query('//record');

        foreach ($recordNodes as $node) {
            $record = [
                'source_ip' => $this->nodeValue($node, 'row/source_ip'),
                'count' => (int) $this->nodeValue($node, 'row/count'),
                'disposition' => $this->nodeValue($node, 'row/policy_evaluated/disposition'),
                'dkim_result' => $this->nodeValue($node, 'row/policy_evaluated/dkim'),
                'spf_result' => $this->nodeValue($node, 'row/policy_evaluated/spf'),
                'header_from' => $this->nodeValue($node, 'identifiers/header_from'),
                'envelope_from' => $this->nodeValue($node, 'identifiers/envelope_from'),
                'dkim_domain' => $this->nodeValue($node, 'auth_results/dkim/domain'),
                'dkim_auth_result' => $this->nodeValue($node, 'auth_results/dkim/result'),
                'spf_domain' => $this->nodeValue($node, 'auth_results/spf/domain'),
                'spf_auth_result' => $this->nodeValue($node, 'auth_results/spf/result'),
            ];

            $records[] = $record;
        }

        $totalMessages = array_sum(array_column($records, 'count'));
        $passCount = 0;
        $failCount = 0;

        foreach ($records as $r) {
            if ($r['disposition'] === 'none' && ($r['dkim_result'] === 'pass' || $r['spf_result'] === 'pass')) {
                $passCount += $r['count'];
            } else {
                $failCount += $r['count'];
            }
        }

        return [
            'metadata' => $metadata,
            'policy' => $policy,
            'records' => $records,
            'total_messages' => $totalMessages,
            'pass_count' => $passCount,
            'fail_count' => $failCount,
        ];
    }

    public function decompress(string $content, string $filename): string
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if ($ext === 'gz') {
            $xml = @gzdecode($content);
            if ($xml === false) {
                throw new \RuntimeException('Failed to decompress gzip file');
            }
            return $xml;
        }

        if ($ext === 'zip') {
            $tmpFile = tempnam(sys_get_temp_dir(), 'dmarc_');
            file_put_contents($tmpFile, $content);
            $zip = new \ZipArchive();
            if ($zip->open($tmpFile) !== true) {
                unlink($tmpFile);
                throw new \RuntimeException('Failed to open zip file');
            }
            $xml = $zip->getFromIndex(0);
            $zip->close();
            unlink($tmpFile);
            if ($xml === false) {
                throw new \RuntimeException('Failed to extract XML from zip');
            }
            return $xml;
        }

        if ($ext === 'xml' || str_contains($content, '<?xml')) {
            return $content;
        }

        throw new \RuntimeException('Unsupported file format: ' . $ext);
    }

    protected function xpathValue(\DOMXPath $xpath, string $query): ?string
    {
        $nodes = $xpath->query($query);
        if ($nodes && $nodes->length > 0) {
            return $nodes->item(0)->textContent;
        }
        return null;
    }

    protected function nodeValue(\DOMNode $node, string $path): ?string
    {
        $parts = explode('/', $path);
        $current = $node;

        foreach ($parts as $part) {
            $found = false;
            foreach ($current->childNodes as $child) {
                if ($child->nodeName === $part) {
                    $current = $child;
                    $found = true;
                    break;
                }
            }
            if (! $found) {
                return null;
            }
        }

        return $current->textContent;
    }
}
