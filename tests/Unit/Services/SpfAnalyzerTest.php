<?php

use App\Services\SpfAnalyzer;

beforeEach(function () {
    $this->analyzer = new SpfAnalyzer();

    // Helper to call protected parseMechanisms method
    $this->parseMechanisms = function (string $record): array {
        $ref = new ReflectionMethod(SpfAnalyzer::class, 'parseMechanisms');
        return $ref->invoke($this->analyzer, $record);
    };
});

test('parsing SPF record extracts mechanisms correctly', function () {
    $record = 'v=spf1 include:_spf.google.com include:sendgrid.net ~all';
    $mechanisms = ($this->parseMechanisms)($record);

    expect($mechanisms)->toHaveCount(3);

    expect($mechanisms[0])
        ->type->toBe('include')
        ->value->toBe('_spf.google.com')
        ->qualifier->toBe('+');

    expect($mechanisms[1])
        ->type->toBe('include')
        ->value->toBe('sendgrid.net')
        ->qualifier->toBe('+');

    expect($mechanisms[2])
        ->type->toBe('all')
        ->qualifier->toBe('~');
});

test('counting DNS lookups correctly for includes', function () {
    $analyzer = Mockery::mock(SpfAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('querySpf')->andReturn('v=spf1 include:_spf.google.com include:sendgrid.net mx a ~all');

    $result = $analyzer->analyze('example.com');

    // 2 includes + 1 mx + 1 a = 4 lookups
    expect($result['lookup_count'])->toBe(4);
    expect($result['includes'])->toContain('_spf.google.com', 'sendgrid.net');
});

test('detecting too many lookups raises issue', function () {
    // Build a record with 11 includes to exceed the limit
    $includes = [];
    for ($i = 1; $i <= 11; $i++) {
        $includes[] = "include:provider{$i}.com";
    }
    $record = 'v=spf1 ' . implode(' ', $includes) . ' -all';

    $analyzer = Mockery::mock(SpfAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('querySpf')->andReturn($record);

    $result = $analyzer->analyze('example.com');

    expect($result['lookup_count'])->toBe(11);
    expect($result['is_valid'])->toBeFalse();
    expect($result['issues'])->toContain('Too many DNS lookups (11/10). SPF will permerror.');
});

test('close to lookup limit generates warning', function () {
    $includes = [];
    for ($i = 1; $i <= 8; $i++) {
        $includes[] = "include:provider{$i}.com";
    }
    $record = 'v=spf1 ' . implode(' ', $includes) . ' -all';

    $analyzer = Mockery::mock(SpfAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('querySpf')->andReturn($record);

    $result = $analyzer->analyze('example.com');

    expect($result['lookup_count'])->toBe(8);
    expect($result['is_valid'])->toBeTrue();
    expect($result['warnings'])->toContain('DNS lookup count is 8/10. Getting close to the limit.');
});

test('parsing qualifiers correctly', function () {
    $record = 'v=spf1 +ip4:1.2.3.4 -all';
    $mechanisms = ($this->parseMechanisms)($record);

    expect($mechanisms[0])->qualifier->toBe('+')->type->toBe('ip4');
    expect($mechanisms[1])->qualifier->toBe('-')->type->toBe('all');
});

test('no SPF record returns not found result', function () {
    $analyzer = Mockery::mock(SpfAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('querySpf')->andReturn(null);

    $result = $analyzer->analyze('example.com');

    expect($result)
        ->found->toBeFalse()
        ->record->toBeNull()
        ->is_valid->toBeFalse();

    expect($result['issues'])->toContain('No SPF record found for example.com');
});

test('plus all qualifier raises issue', function () {
    $analyzer = Mockery::mock(SpfAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('querySpf')->andReturn('v=spf1 +all');

    $result = $analyzer->analyze('example.com');

    expect($result['is_valid'])->toBeFalse();
    expect($result['issues'])->toContain('"all" mechanism with "+" qualifier allows all senders. Use "-all" or "~all" instead.');
});

test('ip4 and ip6 mechanisms do not count as DNS lookups', function () {
    $analyzer = Mockery::mock(SpfAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('querySpf')->andReturn('v=spf1 ip4:192.168.1.0/24 ip6:2001:db8::/32 -all');

    $result = $analyzer->analyze('example.com');

    expect($result['lookup_count'])->toBe(0);
});
