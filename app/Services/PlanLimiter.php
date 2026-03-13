<?php

namespace App\Services;

use App\Enums\AlertChannelType;
use App\Enums\SubscriptionPlan;
use App\Models\Team;

class PlanLimiter
{
    public function getPlan(Team $team): SubscriptionPlan
    {
        if ($team->subscribed('default')) {
            $price = $team->subscription('default')?->stripe_price;

            $enterprisePrices = $this->collectPriceIds('enterprise_monthly', 'enterprise_yearly');
            $proPrices = $this->collectPriceIds('pro_monthly', 'pro_yearly');

            if (in_array($price, $enterprisePrices, true)) {
                return SubscriptionPlan::ENTERPRISE;
            }

            if (in_array($price, $proPrices, true)) {
                return SubscriptionPlan::PRO;
            }

            return SubscriptionPlan::FREE;
        }

        return SubscriptionPlan::FREE;
    }

    protected function collectPriceIds(string ...$keys): array
    {
        $prices = [];
        $currencies = config('dmarcwatch.stripe.prices', []);

        foreach ($currencies as $currencyPrices) {
            if (! is_array($currencyPrices)) {
                continue;
            }
            foreach ($keys as $key) {
                if (! empty($currencyPrices[$key])) {
                    $prices[] = $currencyPrices[$key];
                }
            }
        }

        return $prices;
    }

    public function limits(Team $team): PlanLimits
    {
        $plan = $this->getPlan($team);
        $limits = PlanLimits::forPlan($plan);

        if ($plan !== SubscriptionPlan::ENTERPRISE) {
            return $limits;
        }

        $addonQuantity = $this->getDomainAddonQuantity($team);

        if ($addonQuantity > 0) {
            return new PlanLimits(
                maxDomains: $limits->maxDomains + ($addonQuantity * 25),
                maxReportsPerMonth: $limits->maxReportsPerMonth,
                retentionDays: $limits->retentionDays,
                maxAlertChannels: $limits->maxAlertChannels,
                maxTeamMembers: $limits->maxTeamMembers,
                apiAccess: $limits->apiAccess,
                apiRateLimit: $limits->apiRateLimit,
                webhooksEnabled: $limits->webhooksEnabled,
                slackEnabled: $limits->slackEnabled,
                pushoverEnabled: $limits->pushoverEnabled,
                weeklyDigests: $limits->weeklyDigests,
                dnsCheckIntervalMinutes: $limits->dnsCheckIntervalMinutes,
            );
        }

        return $limits;
    }

    public function getDomainAddonQuantity(Team $team): int
    {
        $subscription = $team->subscription('default');

        if (! $subscription) {
            return 0;
        }

        $addonPrices = $this->collectAddonPriceIds();

        if (empty($addonPrices)) {
            return 0;
        }

        $addonItem = $subscription->items()
            ->whereIn('stripe_price', $addonPrices)
            ->first();

        return $addonItem ? (int) $addonItem->quantity : 0;
    }

    protected function collectAddonPriceIds(): array
    {
        $prices = [];
        $currencies = config('dmarcwatch.stripe.prices', []);

        foreach ($currencies as $currencyPrices) {
            if (! empty($currencyPrices['domain_addon'])) {
                $prices[] = $currencyPrices['domain_addon'];
            }
        }

        return array_unique($prices);
    }

    public function canAddDomain(Team $team): bool
    {
        $limits = $this->limits($team);

        if ($limits->maxDomains === PHP_INT_MAX) {
            return true;
        }

        $count = $team->domains()->count();

        return $count < $limits->maxDomains;
    }

    public function canAddAlertChannel(Team $team): bool
    {
        $limits = $this->limits($team);

        if ($limits->maxAlertChannels === PHP_INT_MAX) {
            return true;
        }

        $count = $team->alertChannels()->count();

        return $count < $limits->maxAlertChannels;
    }

    public function canUseChannelType(Team $team, AlertChannelType $type): bool
    {
        $limits = $this->limits($team);

        return match ($type) {
            AlertChannelType::EMAIL => true,
            AlertChannelType::SLACK => $limits->slackEnabled,
            AlertChannelType::WEBHOOK => $limits->webhooksEnabled,
            AlertChannelType::PUSHOVER => $limits->pushoverEnabled,
        };
    }

    public function canAccessApi(Team $team): bool
    {
        return $this->limits($team)->apiAccess;
    }

    public function getApiRateLimit(Team $team): int
    {
        return $this->limits($team)->apiRateLimit;
    }

    public function getRetentionDays(Team $team): ?int
    {
        return $this->limits($team)->retentionDays;
    }

    public function enforceDowngrade(Team $team): void
    {
        $limits = $this->limits($team);

        if ($limits->maxDomains < PHP_INT_MAX) {
            $activeDomains = $team->domains()->where('is_active', true)->orderBy('created_at')->get();
            if ($activeDomains->count() > $limits->maxDomains) {
                $toDeactivate = $activeDomains->slice($limits->maxDomains)->pluck('id');
                $team->domains()->whereIn('id', $toDeactivate)->update(['is_active' => false]);
            }
        }

        if ($limits->maxAlertChannels < PHP_INT_MAX) {
            $activeChannels = $team->alertChannels()->where('is_active', true)->orderBy('created_at')->get();
            if ($activeChannels->count() > $limits->maxAlertChannels) {
                $toDeactivate = $activeChannels->slice($limits->maxAlertChannels)->pluck('id');
                $team->alertChannels()->whereIn('id', $toDeactivate)->update(['is_active' => false]);
            }
        }

        if (! $limits->slackEnabled) {
            $team->alertChannels()->where('type', AlertChannelType::SLACK->value)->where('is_active', true)->update(['is_active' => false]);
        }
        if (! $limits->webhooksEnabled) {
            $team->alertChannels()->where('type', AlertChannelType::WEBHOOK->value)->where('is_active', true)->update(['is_active' => false]);
        }
        if (! $limits->pushoverEnabled) {
            $team->alertChannels()->where('type', AlertChannelType::PUSHOVER->value)->where('is_active', true)->update(['is_active' => false]);
        }
    }
}
