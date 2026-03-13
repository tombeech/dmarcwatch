<?php

use App\Models\Domain;
use App\Services\ComplianceScorer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->scorer = new ComplianceScorer();
});

test('perfect score with reject policy and 100% pass rates and valid DNS', function () {
    $domain = Domain::factory()->create([
        'dmarc_policy' => 'reject',
        'spf_status' => 'valid',
        'dkim_status' => 'valid',
    ]);

    // Create reports with all passing
    $domain->dmarcReports()->create([
        'team_id' => $domain->team_id,
        'report_id' => 'test-report-1',
        'reporter_org' => 'google.com',
        'reporter_email' => 'noreply@google.com',
        'date_begin' => now()->subDay(),
        'date_end' => now(),
        'domain_policy' => 'reject',
        'total_messages' => 1000,
        'pass_count' => 1000,
        'fail_count' => 0,
    ]);

    $result = $this->scorer->score($domain);

    // Policy: 100 * 0.30 = 30
    // SPF pass rate: 100 * 0.25 = 25
    // DKIM pass rate: 100 * 0.25 = 25
    // Alignment: 80 * 0.10 = 8
    // DNS validity: 100 * 0.10 = 10
    // Total = 98
    expect($result['score'])->toBe(98.0);
    expect($result['recommendations'])->toBeEmpty();
});

test('low score with none policy and high failure rates', function () {
    $domain = Domain::factory()->create([
        'dmarc_policy' => 'none',
        'spf_status' => 'invalid',
        'dkim_status' => 'invalid',
    ]);

    $domain->dmarcReports()->create([
        'team_id' => $domain->team_id,
        'report_id' => 'test-report-2',
        'reporter_org' => 'google.com',
        'reporter_email' => 'noreply@google.com',
        'date_begin' => now()->subDay(),
        'date_end' => now(),
        'domain_policy' => 'none',
        'total_messages' => 100,
        'pass_count' => 20,
        'fail_count' => 80,
    ]);

    $result = $this->scorer->score($domain);

    // Policy: 30 * 0.30 = 9
    // SPF pass rate: 20 * 0.25 = 5
    // DKIM pass rate: 20 * 0.25 = 5
    // Alignment: 80 * 0.10 = 8  (dmarc_policy is not null)
    // DNS validity: 0 * 0.10 = 0
    // Total = 27
    expect($result['score'])->toBe(27.0);
    expect($result['recommendations'])->not->toBeEmpty();
});

test('score calculation weights are correct', function () {
    // With quarantine policy, valid SPF only, no reports
    $domain = Domain::factory()->create([
        'dmarc_policy' => 'quarantine',
        'spf_status' => 'valid',
        'dkim_status' => 'invalid',
    ]);

    $result = $this->scorer->score($domain);

    // Policy: 70 * 0.30 = 21
    // SPF pass rate: 0 * 0.25 = 0 (no reports)
    // DKIM pass rate: 0 * 0.25 = 0
    // Alignment: 80 * 0.10 = 8
    // DNS validity: 50 * 0.10 = 5 (only SPF valid)
    // Total = 34
    expect($result['score'])->toBe(34.0);
    expect($result['breakdown'])->toHaveKeys(['policy', 'spf_pass_rate', 'dkim_pass_rate', 'alignment', 'dns_validity']);
    expect($result['breakdown']['policy'])->toBe(21.0);
});

test('score generates recommendations for missing DKIM', function () {
    $domain = Domain::factory()->create([
        'dmarc_policy' => 'reject',
        'spf_status' => 'valid',
        'dkim_status' => 'invalid',
    ]);

    $result = $this->scorer->score($domain);

    expect($result['recommendations'])->toContain('Set up DKIM signing to improve authentication coverage.');
});

test('score generates recommendations for missing SPF', function () {
    $domain = Domain::factory()->create([
        'dmarc_policy' => 'reject',
        'spf_status' => 'invalid',
        'dkim_status' => 'valid',
    ]);

    $result = $this->scorer->score($domain);

    expect($result['recommendations'])->toContain('Fix SPF record issues to improve DNS validity score.');
});
