<?php

use App\Enums\SubscriptionPlan;
use App\Services\PlanLimits;

test('free plan has correct limits', function () {
    $limits = PlanLimits::forPlan(SubscriptionPlan::FREE);

    expect($limits)
        ->maxDomains->toBe(3)
        ->maxReportsPerMonth->toBe(100)
        ->retentionDays->toBe(30)
        ->maxAlertChannels->toBe(1)
        ->maxTeamMembers->toBe(1)
        ->apiAccess->toBeFalse()
        ->apiRateLimit->toBe(0)
        ->webhooksEnabled->toBeFalse()
        ->slackEnabled->toBeFalse()
        ->pushoverEnabled->toBeFalse()
        ->weeklyDigests->toBeFalse()
        ->dnsCheckIntervalMinutes->toBe(1440);
});

test('pro plan has higher limits', function () {
    $limits = PlanLimits::forPlan(SubscriptionPlan::PRO);

    expect($limits)
        ->maxDomains->toBe(50)
        ->maxReportsPerMonth->toBe(PHP_INT_MAX)
        ->retentionDays->toBe(365)
        ->maxAlertChannels->toBe(5)
        ->maxTeamMembers->toBe(5)
        ->apiAccess->toBeTrue()
        ->apiRateLimit->toBe(1000)
        ->webhooksEnabled->toBeTrue()
        ->slackEnabled->toBeTrue()
        ->pushoverEnabled->toBeTrue()
        ->weeklyDigests->toBeTrue()
        ->dnsCheckIntervalMinutes->toBe(60);
});

test('enterprise plan has unlimited domains and alert channels', function () {
    $limits = PlanLimits::forPlan(SubscriptionPlan::ENTERPRISE);

    expect($limits)
        ->maxDomains->toBe(100)
        ->maxReportsPerMonth->toBe(PHP_INT_MAX)
        ->retentionDays->toBeNull()
        ->maxAlertChannels->toBe(PHP_INT_MAX)
        ->maxTeamMembers->toBeNull()
        ->apiAccess->toBeTrue()
        ->apiRateLimit->toBe(50000)
        ->webhooksEnabled->toBeTrue()
        ->slackEnabled->toBeTrue()
        ->pushoverEnabled->toBeTrue()
        ->weeklyDigests->toBeTrue()
        ->dnsCheckIntervalMinutes->toBe(15);
});

test('pro plan allows more domains than free', function () {
    $free = PlanLimits::forPlan(SubscriptionPlan::FREE);
    $pro = PlanLimits::forPlan(SubscriptionPlan::PRO);

    expect($pro->maxDomains)->toBeGreaterThan($free->maxDomains);
});

test('enterprise plan has higher API rate limit than pro', function () {
    $pro = PlanLimits::forPlan(SubscriptionPlan::PRO);
    $enterprise = PlanLimits::forPlan(SubscriptionPlan::ENTERPRISE);

    expect($enterprise->apiRateLimit)->toBeGreaterThan($pro->apiRateLimit);
});

test('free plan does not have API access', function () {
    $limits = PlanLimits::forPlan(SubscriptionPlan::FREE);

    expect($limits->apiAccess)->toBeFalse();
    expect($limits->webhooksEnabled)->toBeFalse();
    expect($limits->slackEnabled)->toBeFalse();
});
