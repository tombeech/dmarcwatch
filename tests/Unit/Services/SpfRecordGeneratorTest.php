<?php

use App\Services\SpfRecordGenerator;

beforeEach(function () {
    $this->generator = new SpfRecordGenerator();
});

test('generating basic SPF record with default all', function () {
    $result = $this->generator->generate([]);

    expect($result['record'])->toBe('v=spf1 -all');
    expect($result['lookup_count'])->toBe(0);
    expect($result['warnings'])->toBeEmpty();
});

test('generating record with includes', function () {
    $result = $this->generator->generate([
        'includes' => ['_spf.google.com', 'sendgrid.net'],
    ]);

    expect($result['record'])->toBe('v=spf1 include:_spf.google.com include:sendgrid.net -all');
    expect($result['lookup_count'])->toBe(2);
});

test('including multiple mechanisms', function () {
    $result = $this->generator->generate([
        'includes' => ['_spf.google.com'],
        'ip4' => ['192.168.1.0/24', '10.0.0.1'],
        'ip6' => ['2001:db8::/32'],
        'mx' => true,
        'a' => true,
        'all' => '~all',
    ]);

    expect($result['record'])
        ->toContain('v=spf1')
        ->toContain('include:_spf.google.com')
        ->toContain('ip4:192.168.1.0/24')
        ->toContain('ip4:10.0.0.1')
        ->toContain('ip6:2001:db8::/32')
        ->toContain('mx')
        ->toContain(' a ')
        ->toContain('~all');

    // 1 include + 1 mx + 1 a = 3 lookups
    expect($result['lookup_count'])->toBe(3);
});

test('custom all qualifier is respected', function () {
    $result = $this->generator->generate([
        'all' => '~all',
    ]);

    expect($result['record'])->toBe('v=spf1 ~all');
});

test('too many lookups generates warning', function () {
    $includes = [];
    for ($i = 1; $i <= 11; $i++) {
        $includes[] = "provider{$i}.example.com";
    }

    $result = $this->generator->generate([
        'includes' => $includes,
    ]);

    expect($result['lookup_count'])->toBe(11);
    expect($result['warnings'])->toContain('SPF record requires 11 DNS lookups, exceeding the limit of 10.');
});

test('close to lookup limit generates warning', function () {
    $includes = [];
    for ($i = 1; $i <= 8; $i++) {
        $includes[] = "provider{$i}.example.com";
    }

    $result = $this->generator->generate([
        'includes' => $includes,
    ]);

    expect($result['lookup_count'])->toBe(8);
    expect($result['warnings'])->toContain('SPF record uses 8/10 DNS lookups. Getting close to the limit.');
});

test('ip4 and ip6 do not count toward lookup limit', function () {
    $result = $this->generator->generate([
        'ip4' => ['1.2.3.4', '5.6.7.8'],
        'ip6' => ['2001:db8::/32'],
    ]);

    expect($result['lookup_count'])->toBe(0);
});
