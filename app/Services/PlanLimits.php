<?php

namespace App\Services;

use App\Enums\SubscriptionPlan;

class PlanLimits
{
    public function __construct(
        public readonly int $maxDomains,
        public readonly int $maxReportsPerMonth,
        public readonly ?int $retentionDays,
        public readonly int $maxAlertChannels,
        public readonly ?int $maxTeamMembers,
        public readonly bool $apiAccess,
        public readonly int $apiRateLimit,
        public readonly bool $webhooksEnabled,
        public readonly bool $slackEnabled,
        public readonly bool $pushoverEnabled,
        public readonly bool $weeklyDigests,
        public readonly int $dnsCheckIntervalMinutes,
    ) {}

    public static function forPlan(SubscriptionPlan $plan): self
    {
        return match ($plan) {
            SubscriptionPlan::FREE => new self(
                maxDomains: 3,
                maxReportsPerMonth: 100,
                retentionDays: 30,
                maxAlertChannels: 1,
                maxTeamMembers: 1,
                apiAccess: false,
                apiRateLimit: 0,
                webhooksEnabled: false,
                slackEnabled: false,
                pushoverEnabled: false,
                weeklyDigests: false,
                dnsCheckIntervalMinutes: 1440,
            ),
            SubscriptionPlan::PRO => new self(
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
            ),
            SubscriptionPlan::ENTERPRISE => new self(
                maxDomains: 100,
                maxReportsPerMonth: PHP_INT_MAX,
                retentionDays: null,
                maxAlertChannels: PHP_INT_MAX,
                maxTeamMembers: null,
                apiAccess: true,
                apiRateLimit: 50000,
                webhooksEnabled: true,
                slackEnabled: true,
                pushoverEnabled: true,
                weeklyDigests: true,
                dnsCheckIntervalMinutes: 15,
            ),
        };
    }
}
