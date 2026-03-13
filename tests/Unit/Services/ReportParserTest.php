<?php

use App\Services\ReportParser;

beforeEach(function () {
    $this->parser = new ReportParser();
    $this->googleXml = file_get_contents(__DIR__ . '/../../fixtures/aggregate-report-google.xml');
    $this->microsoftXml = file_get_contents(__DIR__ . '/../../fixtures/aggregate-report-microsoft.xml');
});

test('parsing valid XML returns correct structure', function () {
    $result = $this->parser->parse($this->googleXml);

    expect($result)
        ->toBeArray()
        ->toHaveKeys(['metadata', 'policy', 'records', 'total_messages', 'pass_count', 'fail_count']);

    expect($result['metadata'])
        ->toHaveKeys(['org_name', 'email', 'report_id', 'date_begin', 'date_end']);

    expect($result['metadata']['org_name'])->toBe('google.com');
    expect($result['metadata']['report_id'])->toBe('12345678901234567890');
    expect($result['policy']['domain'])->toBe('example.com');
    expect($result['records'])->toHaveCount(2);
});

test('parsing extracts correct record data', function () {
    $result = $this->parser->parse($this->googleXml);
    $firstRecord = $result['records'][0];

    expect($firstRecord)
        ->source_ip->toBe('209.85.220.41')
        ->count->toBe(1500)
        ->dkim_result->toBe('pass')
        ->spf_result->toBe('pass')
        ->disposition->toBe('none')
        ->header_from->toBe('example.com')
        ->envelope_from->toBe('example.com')
        ->dkim_domain->toBe('example.com')
        ->dkim_auth_result->toBe('pass')
        ->spf_domain->toBe('example.com')
        ->spf_auth_result->toBe('pass');
});

test('parsing handles multiple records', function () {
    $result = $this->parser->parse($this->googleXml);

    expect($result['records'])->toHaveCount(2);

    $secondRecord = $result['records'][1];
    expect($secondRecord)
        ->source_ip->toBe('192.168.1.100')
        ->count->toBe(5)
        ->dkim_result->toBe('fail')
        ->spf_result->toBe('fail')
        ->disposition->toBe('reject');
});

test('parsing calculates total messages correctly', function () {
    $result = $this->parser->parse($this->googleXml);

    expect($result['total_messages'])->toBe(1505);
    expect($result['pass_count'])->toBe(1500);
    expect($result['fail_count'])->toBe(5);
});

test('parsing Microsoft report returns correct org name', function () {
    $result = $this->parser->parse($this->microsoftXml);

    expect($result['metadata']['org_name'])->toBe('Microsoft Corporation');
    expect($result['metadata']['report_id'])->toBe('98765432109876543210');
    expect($result['records'])->toHaveCount(2);
    expect($result['records'][0]['source_ip'])->toBe('40.92.10.50');
});

test('invalid XML throws exception', function () {
    $this->parser->parse('not valid xml at all');
})->throws(RuntimeException::class, 'Failed to parse DMARC report XML');

test('parsing extracts policy published data', function () {
    $result = $this->parser->parse($this->googleXml);

    expect($result['policy'])
        ->domain->toBe('example.com')
        ->adkim->toBe('r')
        ->aspf->toBe('r')
        ->p->toBe('reject')
        ->sp->toBe('reject')
        ->pct->toBe('100');
});

test('parsing extracts date range as integers', function () {
    $result = $this->parser->parse($this->googleXml);

    expect($result['metadata']['date_begin'])->toBe(1710288000)->toBeInt();
    expect($result['metadata']['date_end'])->toBe(1710374400)->toBeInt();
});
