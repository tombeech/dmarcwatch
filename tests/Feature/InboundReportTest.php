<?php

use App\Jobs\ProcessInboundReport;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->user = User::factory()->withPersonalTeam()->create();
    $this->team = $this->user->currentTeam;
    $this->domain = Domain::factory()->create([
        'team_id' => $this->team->id,
        'name' => 'example.com',
        'rua_address' => 'abc12345@reports.dmarcwatch.app',
    ]);
});

test('valid webhook with correct Mailgun signature returns 200', function () {
    Bus::fake();

    $secret = 'test-mailgun-secret';
    config(['dmarcwatch.inbound_email.mailgun_secret' => $secret]);

    $timestamp = (string) time();
    $token = 'random-token-123';
    $signature = hash_hmac('sha256', $timestamp . $token, $secret);

    $xmlContent = file_get_contents(__DIR__ . '/../fixtures/aggregate-report-google.xml');
    $file = UploadedFile::fake()->createWithContent('report.xml', $xmlContent);

    $response = $this->post('/webhooks/inbound-report', [
        'recipient' => 'abc12345@reports.dmarcwatch.app',
        'timestamp' => $timestamp,
        'token' => $token,
        'signature' => $signature,
        'attachment-count' => 1,
        'attachment-1' => $file,
    ]);

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Report queued for processing']);
});

test('invalid signature returns 403', function () {
    $secret = 'test-mailgun-secret';
    config(['dmarcwatch.inbound_email.mailgun_secret' => $secret]);

    $response = $this->post('/webhooks/inbound-report', [
        'recipient' => 'abc12345@reports.dmarcwatch.app',
        'timestamp' => (string) time(),
        'token' => 'random-token-123',
        'signature' => 'invalid-signature',
    ]);

    $response->assertStatus(403);
});

test('missing signature parameters returns 403', function () {
    $secret = 'test-mailgun-secret';
    config(['dmarcwatch.inbound_email.mailgun_secret' => $secret]);

    $response = $this->post('/webhooks/inbound-report', [
        'recipient' => 'abc12345@reports.dmarcwatch.app',
    ]);

    $response->assertStatus(403);
});

test('webhook dispatches ProcessInboundReport job', function () {
    Bus::fake([ProcessInboundReport::class]);

    $secret = 'test-mailgun-secret';
    config(['dmarcwatch.inbound_email.mailgun_secret' => $secret]);

    $timestamp = (string) time();
    $token = 'random-token-456';
    $signature = hash_hmac('sha256', $timestamp . $token, $secret);

    $xmlContent = file_get_contents(__DIR__ . '/../fixtures/aggregate-report-google.xml');
    $file = UploadedFile::fake()->createWithContent('report.xml', $xmlContent);

    $this->post('/webhooks/inbound-report', [
        'recipient' => 'abc12345@reports.dmarcwatch.app',
        'timestamp' => $timestamp,
        'token' => $token,
        'signature' => $signature,
        'attachment-count' => 1,
        'attachment-1' => $file,
    ]);

    Bus::assertDispatched(ProcessInboundReport::class, function ($job) {
        return $job->domain->id === $this->domain->id;
    });
});

test('webhook without secret configured skips verification', function () {
    Bus::fake([ProcessInboundReport::class]);

    config(['dmarcwatch.inbound_email.mailgun_secret' => null]);

    $xmlContent = file_get_contents(__DIR__ . '/../fixtures/aggregate-report-google.xml');
    $file = UploadedFile::fake()->createWithContent('report.xml', $xmlContent);

    $response = $this->post('/webhooks/inbound-report', [
        'recipient' => 'abc12345@reports.dmarcwatch.app',
        'attachment-count' => 1,
        'attachment-1' => $file,
    ]);

    $response->assertStatus(200);
    Bus::assertDispatched(ProcessInboundReport::class);
});

test('webhook with unknown recipient returns 404', function () {
    config(['dmarcwatch.inbound_email.mailgun_secret' => null]);

    $response = $this->post('/webhooks/inbound-report', [
        'recipient' => 'unknown@reports.dmarcwatch.app',
        'attachment-count' => 0,
    ]);

    $response->assertStatus(404);
});

test('expired timestamp returns 403', function () {
    $secret = 'test-mailgun-secret';
    config(['dmarcwatch.inbound_email.mailgun_secret' => $secret]);

    $timestamp = (string) (time() - 600); // 10 minutes ago
    $token = 'random-token-789';
    $signature = hash_hmac('sha256', $timestamp . $token, $secret);

    $response = $this->post('/webhooks/inbound-report', [
        'recipient' => 'abc12345@reports.dmarcwatch.app',
        'timestamp' => $timestamp,
        'token' => $token,
        'signature' => $signature,
    ]);

    $response->assertStatus(403);
});
