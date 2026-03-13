<?php

use App\Services\DmarcAnalyzer;
use App\Services\SpfAnalyzer;

test('DMARC lookup endpoint returns analysis results', function () {
    $this->mock(DmarcAnalyzer::class, function ($mock) {
        $mock->shouldReceive('analyze')
            ->with('example.com')
            ->once()
            ->andReturn([
                'found' => true,
                'record' => 'v=DMARC1; p=reject; rua=mailto:dmarc@example.com',
                'tags' => ['v' => 'DMARC1', 'p' => 'reject', 'rua' => 'mailto:dmarc@example.com'],
                'issues' => [],
                'warnings' => [],
                'is_valid' => true,
            ]);
    });

    $response = $this->postJson('/api/v1/lookup/dmarc', [
        'domain' => 'example.com',
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('found', true);
    $response->assertJsonPath('is_valid', true);
    $response->assertJsonPath('tags.p', 'reject');
});

test('SPF lookup endpoint returns analysis results', function () {
    $this->mock(SpfAnalyzer::class, function ($mock) {
        $mock->shouldReceive('analyze')
            ->with('example.com')
            ->once()
            ->andReturn([
                'found' => true,
                'record' => 'v=spf1 include:_spf.google.com -all',
                'mechanisms' => [
                    ['qualifier' => '+', 'type' => 'include', 'value' => '_spf.google.com', 'raw' => 'include:_spf.google.com'],
                    ['qualifier' => '-', 'type' => 'all', 'value' => null, 'raw' => '-all'],
                ],
                'lookup_count' => 1,
                'max_lookups' => 10,
                'issues' => [],
                'warnings' => [],
                'is_valid' => true,
                'includes' => ['_spf.google.com'],
            ]);
    });

    $response = $this->postJson('/api/v1/lookup/spf', [
        'domain' => 'example.com',
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('found', true);
    $response->assertJsonPath('is_valid', true);
    $response->assertJsonPath('lookup_count', 1);
});

test('DMARC lookup requires domain parameter', function () {
    $response = $this->postJson('/api/v1/lookup/dmarc', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['domain']);
});

test('SPF lookup requires domain parameter', function () {
    $response = $this->postJson('/api/v1/lookup/spf', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['domain']);
});

test('lookup endpoints are rate limited', function () {
    $this->mock(DmarcAnalyzer::class, function ($mock) {
        $mock->shouldReceive('analyze')->andReturn([
            'found' => false,
            'record' => null,
            'tags' => [],
            'issues' => ['No DMARC record found'],
            'warnings' => [],
            'is_valid' => false,
        ]);
    });

    // The route has throttle:30,1 middleware
    // Send 31 requests to trigger rate limiting
    for ($i = 0; $i < 30; $i++) {
        $this->postJson('/api/v1/lookup/dmarc', ['domain' => 'example.com']);
    }

    $response = $this->postJson('/api/v1/lookup/dmarc', ['domain' => 'example.com']);
    $response->assertStatus(429);
});

test('DMARC lookup with domain not found returns valid response', function () {
    $this->mock(DmarcAnalyzer::class, function ($mock) {
        $mock->shouldReceive('analyze')
            ->with('nonexistent.com')
            ->once()
            ->andReturn([
                'found' => false,
                'record' => null,
                'tags' => [],
                'issues' => ['No DMARC record found at _dmarc.nonexistent.com'],
                'warnings' => [],
                'is_valid' => false,
            ]);
    });

    $response = $this->postJson('/api/v1/lookup/dmarc', [
        'domain' => 'nonexistent.com',
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('found', false);
    $response->assertJsonPath('is_valid', false);
});
