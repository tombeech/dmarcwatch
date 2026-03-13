<?php

use App\Services\DmarcRecordGenerator;

beforeEach(function () {
    $this->generator = new DmarcRecordGenerator();
});

test('generating basic record with policy only', function () {
    $record = $this->generator->generate([
        'policy' => 'none',
    ]);

    expect($record)->toBe('v=DMARC1; p=none');
});

test('generating record defaults to none policy when not specified', function () {
    $record = $this->generator->generate([]);

    expect($record)->toBe('v=DMARC1; p=none');
});

test('generating full record with all options', function () {
    $record = $this->generator->generate([
        'policy' => 'reject',
        'subdomain_policy' => 'quarantine',
        'rua' => ['dmarc@example.com', 'reports@example.com'],
        'ruf' => 'forensic@example.com',
        'pct' => 50,
        'adkim' => 's',
        'aspf' => 's',
        'fo' => '1',
        'ri' => 86400,
    ]);

    expect($record)->toContain('v=DMARC1')
        ->toContain('p=reject')
        ->toContain('sp=quarantine')
        ->toContain('rua=mailto:dmarc@example.com,mailto:reports@example.com')
        ->toContain('ruf=mailto:forensic@example.com')
        ->toContain('pct=50')
        ->toContain('adkim=s')
        ->toContain('aspf=s')
        ->toContain('fo=1')
        ->toContain('ri=86400');
});

test('pct=100 is omitted from output', function () {
    $record = $this->generator->generate([
        'policy' => 'reject',
        'pct' => 100,
    ]);

    expect($record)->toBe('v=DMARC1; p=reject');
    expect($record)->not->toContain('pct=');
});

test('single rua email wraps in mailto', function () {
    $record = $this->generator->generate([
        'policy' => 'reject',
        'rua' => 'dmarc@example.com',
    ]);

    expect($record)->toContain('rua=mailto:dmarc@example.com');
});

test('multiple ruf emails are joined with commas', function () {
    $record = $this->generator->generate([
        'policy' => 'reject',
        'ruf' => ['forensic1@example.com', 'forensic2@example.com'],
    ]);

    expect($record)->toContain('ruf=mailto:forensic1@example.com,mailto:forensic2@example.com');
});
