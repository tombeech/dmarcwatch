<?php

use App\Services\DmarcAnalyzer;

beforeEach(function () {
    $this->analyzer = new DmarcAnalyzer();

    // Helper to call protected parseTags method
    $this->parseTags = function (string $record): array {
        $ref = new ReflectionMethod(DmarcAnalyzer::class, 'parseTags');
        return $ref->invoke($this->analyzer, $record);
    };
});

test('parsing a valid DMARC record string extracts all tags', function () {
    $record = 'v=DMARC1; p=reject; rua=mailto:dmarc@example.com; pct=100';
    $tags = ($this->parseTags)($record);

    expect($tags)
        ->toBeArray()
        ->v->toBe('DMARC1')
        ->p->toBe('reject')
        ->rua->toBe('mailto:dmarc@example.com')
        ->pct->toBe('100');
});

test('parsing extracts tags with various spacing', function () {
    $record = 'v=DMARC1;p=quarantine;  rua=mailto:reports@example.com ; adkim=s; aspf=r';
    $tags = ($this->parseTags)($record);

    expect($tags)
        ->v->toBe('DMARC1')
        ->p->toBe('quarantine')
        ->rua->toBe('mailto:reports@example.com')
        ->adkim->toBe('s')
        ->aspf->toBe('r');
});

test('parsing a record with missing policy tag is detected during analysis', function () {
    $analyzer = Mockery::mock(DmarcAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('queryDmarc')->andReturn('v=DMARC1; rua=mailto:dmarc@example.com');

    $result = $analyzer->analyze('example.com');

    expect($result)
        ->found->toBeTrue()
        ->is_valid->toBeFalse();

    expect($result['issues'])->toContain('Missing policy tag (p=)');
});

test('parsing a record with invalid version is detected during analysis', function () {
    $analyzer = Mockery::mock(DmarcAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('queryDmarc')->andReturn('v=DMARC2; p=reject');

    $result = $analyzer->analyze('example.com');

    expect($result)
        ->found->toBeTrue()
        ->is_valid->toBeFalse();

    expect($result['issues'])->toContain('Missing or invalid version tag (v=DMARC1 required)');
});

test('a valid record with reject policy passes analysis', function () {
    $analyzer = Mockery::mock(DmarcAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('queryDmarc')->andReturn('v=DMARC1; p=reject; rua=mailto:dmarc@example.com; pct=100');

    $result = $analyzer->analyze('example.com');

    expect($result)
        ->found->toBeTrue()
        ->is_valid->toBeTrue();

    expect($result['issues'])->toBeEmpty();
    expect($result['warnings'])->toBeEmpty();
});

test('none policy generates warning', function () {
    $analyzer = Mockery::mock(DmarcAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('queryDmarc')->andReturn('v=DMARC1; p=none; rua=mailto:dmarc@example.com');

    $result = $analyzer->analyze('example.com');

    expect($result)
        ->found->toBeTrue()
        ->is_valid->toBeTrue();

    expect($result['warnings'])->toContain('Policy is set to "none" — no enforcement. Consider quarantine or reject.');
});

test('missing rua generates warning', function () {
    $analyzer = Mockery::mock(DmarcAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('queryDmarc')->andReturn('v=DMARC1; p=reject');

    $result = $analyzer->analyze('example.com');

    expect($result['warnings'])->toContain('No aggregate report URI (rua) specified.');
});

test('no DMARC record returns not found result', function () {
    $analyzer = Mockery::mock(DmarcAnalyzer::class)->makePartial();
    $analyzer->shouldAllowMockingProtectedMethods();
    $analyzer->shouldReceive('queryDmarc')->andReturn(null);

    $result = $analyzer->analyze('example.com');

    expect($result)
        ->found->toBeFalse()
        ->record->toBeNull()
        ->is_valid->toBeFalse();

    expect($result['issues'])->toContain('No DMARC record found at _dmarc.example.com');
});
