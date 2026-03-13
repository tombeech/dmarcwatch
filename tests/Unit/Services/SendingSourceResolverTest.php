<?php

use App\Models\SendingSource;
use App\Services\SendingSourceResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->resolver = new SendingSourceResolver();
    Cache::flush();
});

test('matching IP to known source via exact IP', function () {
    $source = SendingSource::create([
        'name' => 'Google',
        'organization' => 'Google LLC',
        'ip_ranges' => ['209.85.220.41'],
        'is_system' => true,
    ]);

    $result = $this->resolver->resolve('209.85.220.41');

    expect($result)->not->toBeNull();
    expect($result->id)->toBe($source->id);
    expect($result->name)->toBe('Google');
});

test('matching IP to known source via CIDR range', function () {
    $source = SendingSource::create([
        'name' => 'Google',
        'organization' => 'Google LLC',
        'ip_ranges' => ['209.85.220.0/24'],
        'is_system' => true,
    ]);

    $result = $this->resolver->resolve('209.85.220.41');

    expect($result)->not->toBeNull();
    expect($result->id)->toBe($source->id);
});

test('returning null for unknown IP', function () {
    SendingSource::create([
        'name' => 'Google',
        'organization' => 'Google LLC',
        'ip_ranges' => ['209.85.220.0/24'],
        'is_system' => true,
    ]);

    $result = $this->resolver->resolve('10.99.99.99');

    expect($result)->toBeNull();
});

test('IP outside CIDR range does not match', function () {
    SendingSource::create([
        'name' => 'SendGrid',
        'organization' => 'Twilio SendGrid',
        'ip_ranges' => ['167.89.0.0/16'],
        'is_system' => true,
    ]);

    $result = $this->resolver->resolve('168.0.0.1');

    expect($result)->toBeNull();
});

test('matching first source when IP matches multiple', function () {
    SendingSource::create([
        'name' => 'Provider A',
        'organization' => 'A',
        'ip_ranges' => ['10.0.0.0/8'],
        'is_system' => true,
    ]);

    SendingSource::create([
        'name' => 'Provider B',
        'organization' => 'B',
        'ip_ranges' => ['10.0.0.0/24'],
        'is_system' => true,
    ]);

    $result = $this->resolver->resolve('10.0.0.5');

    expect($result)->not->toBeNull();
    expect($result->name)->toBe('Provider A');
});

test('result is cached', function () {
    SendingSource::create([
        'name' => 'Google',
        'organization' => 'Google LLC',
        'ip_ranges' => ['209.85.220.0/24'],
        'is_system' => true,
    ]);

    // First call populates cache
    $this->resolver->resolve('209.85.220.41');

    // Verify cache key exists
    expect(Cache::has('sending_source:209.85.220.41'))->toBeTrue();
});
