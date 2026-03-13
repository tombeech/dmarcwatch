<?php

use App\Models\Domain;
use App\Models\User;
use App\Services\PlanLimiter;
use App\Services\PlanLimits;
use Illuminate\Support\Facades\Bus;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->withPersonalTeam()->create();
    $this->team = $this->user->currentTeam;

    // Mock PlanLimiter to allow API access (simulates pro/enterprise plan)
    $this->mock(PlanLimiter::class, function ($mock) {
        $mock->shouldReceive('canAccessApi')->andReturn(true);
        $mock->shouldReceive('getApiRateLimit')->andReturn(1000);
        $mock->shouldReceive('canAddDomain')->andReturn(true);
        $mock->shouldReceive('limits')->andReturn(new PlanLimits(
            maxDomains: 50,
            maxReportsPerMonth: PHP_INT_MAX,
            retentionDays: 365,
            maxAlertChannels: 5,
            maxTeamMembers: 5,
            apiAccess: true,
            apiRateLimit: 1000,
            webhooksEnabled: true,
            slackEnabled: true,
            pushoverEnabled: true,
            weeklyDigests: true,
            dnsCheckIntervalMinutes: 60,
        ));
    });
});

test('listing domains requires authentication', function () {
    $response = $this->getJson('/api/v1/domains');

    $response->assertStatus(401);
});

test('authenticated user can list domains', function () {
    Sanctum::actingAs($this->user);

    Domain::factory()->count(3)->create([
        'team_id' => $this->team->id,
    ]);

    $response = $this->getJson('/api/v1/domains');

    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});

test('creating a domain returns the new domain', function () {
    Bus::fake();
    Sanctum::actingAs($this->user);

    $response = $this->postJson('/api/v1/domains', [
        'name' => 'newdomain.com',
    ]);

    $response->assertStatus(201);
    $response->assertJsonPath('data.name', 'newdomain.com');

    $this->assertDatabaseHas('domains', [
        'team_id' => $this->team->id,
        'name' => 'newdomain.com',
    ]);
});

test('creating a domain with invalid name returns validation error', function () {
    Sanctum::actingAs($this->user);

    $response = $this->postJson('/api/v1/domains', [
        'name' => 'not a valid domain',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name']);
});

test('showing a domain returns domain data', function () {
    Sanctum::actingAs($this->user);

    $domain = Domain::factory()->create([
        'team_id' => $this->team->id,
        'name' => 'show-test.com',
    ]);

    $response = $this->getJson("/api/v1/domains/{$domain->id}");

    $response->assertStatus(200);
    $response->assertJsonPath('data.name', 'show-test.com');
});

test('deleting a domain returns 204', function () {
    Sanctum::actingAs($this->user);

    $domain = Domain::factory()->create([
        'team_id' => $this->team->id,
    ]);

    $response = $this->deleteJson("/api/v1/domains/{$domain->id}");

    $response->assertStatus(204);
    $this->assertSoftDeleted('domains', ['id' => $domain->id]);
});

test('creating a domain without name returns validation error', function () {
    Sanctum::actingAs($this->user);

    $response = $this->postJson('/api/v1/domains', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name']);
});
