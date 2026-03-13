<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Support\Carbon;
use Stripe\StripeClient;

class SubscriptionSync
{
    public static function createFromStripe(Team $team): void
    {
        if (! $team->stripe_id) {
            return;
        }

        $stripe = new StripeClient(config('cashier.secret'));

        $subs = $stripe->subscriptions->all([
            'customer' => $team->stripe_id,
            'status' => 'all',
            'limit' => 5,
        ]);

        foreach ($subs->data as $sub) {
            if (! in_array($sub->status, ['active', 'trialing'])) {
                continue;
            }

            if ($team->subscriptions()->where('stripe_id', $sub->id)->exists()) {
                return;
            }

            $team->subscriptions()->where('type', 'default')->each(function ($old) {
                $old->items()->delete();
                $old->delete();
            });

            $item = $sub->items->data[0];

            $localSub = $team->subscriptions()->create([
                'type' => 'default',
                'stripe_id' => $sub->id,
                'stripe_status' => $sub->status,
                'stripe_price' => $item->price->id,
                'quantity' => $item->quantity ?? 1,
                'trial_ends_at' => $sub->trial_end ? Carbon::createFromTimestamp($sub->trial_end) : null,
            ]);

            $localSub->items()->create([
                'stripe_id' => $item->id,
                'stripe_product' => $item->price->product,
                'stripe_price' => $item->price->id,
                'quantity' => $item->quantity ?? 1,
            ]);

            $team->load('subscriptions');

            return;
        }
    }

    public static function syncState(Team $team): void
    {
        $subscription = $team->subscription('default');

        if (! $subscription || ! $subscription->stripe_id) {
            return;
        }

        $stripe = new StripeClient(config('cashier.secret'));
        $stripeSub = $stripe->subscriptions->retrieve($subscription->stripe_id);

        $subscription->stripe_status = $stripeSub->status;

        $item = $stripeSub->items->data[0] ?? null;
        if ($item) {
            $subscription->stripe_price = $item->price->id;
        }

        if ($stripeSub->cancel_at_period_end && ! $subscription->ends_at) {
            $subscription->ends_at = Carbon::createFromTimestamp($stripeSub->current_period_end);
        } elseif (! $stripeSub->cancel_at_period_end && $subscription->ends_at) {
            $subscription->ends_at = null;
        }

        $subscription->save();
        $team->load('subscriptions');
    }
}
