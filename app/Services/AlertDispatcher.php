<?php

namespace App\Services;

use App\Enums\DmarcEventType;
use App\Jobs\SendAlertNotification;
use App\Models\AlertRule;
use Illuminate\Support\Facades\Log;

class AlertDispatcher
{
    public function dispatch(int $teamId, DmarcEventType $eventType, array $eventData): void
    {
        $rules = AlertRule::query()
            ->withoutGlobalScope('team')
            ->where('team_id', $teamId)
            ->where('is_active', true)
            ->with('alertChannel')
            ->get();

        Log::info('[AlertDispatcher] Matching rules for DMARC event', [
            'team_id' => $teamId,
            'event_type' => $eventType->value,
            'rules_found' => $rules->count(),
        ]);

        foreach ($rules as $rule) {
            if (! $rule->alertChannel?->is_active || ! $rule->alertChannel?->is_verified) {
                continue;
            }

            if (! $this->matchesRule($rule, $eventType, $eventData)) {
                continue;
            }

            Log::info('[AlertDispatcher] Dispatching alert', [
                'rule_id' => $rule->id,
                'channel_type' => $rule->alertChannel->type->value,
                'event_type' => $eventType->value,
            ]);

            SendAlertNotification::dispatch($rule, $eventType, $eventData);
        }
    }

    protected function matchesRule(AlertRule $rule, DmarcEventType $eventType, array $eventData): bool
    {
        if (! empty($rule->domain_id) && isset($eventData['domain_id']) && $rule->domain_id !== $eventData['domain_id']) {
            return false;
        }

        if (! empty($rule->event_types) && ! in_array($eventType->value, $rule->event_types, true)) {
            return false;
        }

        return true;
    }
}
