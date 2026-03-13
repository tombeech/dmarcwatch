<?php

namespace App\Livewire\Billing;

use App\Services\CurrencyHelper;
use App\Services\PlanLimiter;
use App\Services\SubscriptionSync;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class BillingPage extends Component
{
    public function subscribe(string $plan): void
    {
        $team = auth()->user()->currentTeam;
        $suffix = CurrencyHelper::stripeCurrencySuffix();

        $priceIds = [
            'pro' => config("dmarcwatch.stripe.prices.{$suffix}.pro_monthly"),
            'enterprise' => config("dmarcwatch.stripe.prices.{$suffix}.enterprise_monthly"),
        ];

        if (! isset($priceIds[$plan])) {
            return;
        }

        try {
            $checkout = $team->newSubscription('default', $priceIds[$plan])
                ->checkout([
                    'success_url' => route('billing') . '?subscribed=1',
                    'cancel_url' => route('billing') . '?canceled=1',
                ]);

            $this->redirect($checkout->url);
        } catch (\Exception $e) {
            session()->flash('error', 'Unable to create checkout: ' . $e->getMessage());
        }
    }

    public function manageBilling(): void
    {
        $team = auth()->user()->currentTeam;

        if ($team->stripe_id) {
            $this->redirect($team->billingPortalUrl(route('billing')));
        }
    }

    public function cancelSubscription(): void
    {
        $team = auth()->user()->currentTeam;
        $subscription = $team->subscription('default');

        if ($subscription) {
            $subscription->cancel();
            SubscriptionSync::syncState($team);
        }
    }

    public function resumeSubscription(): void
    {
        $team = auth()->user()->currentTeam;
        $subscription = $team->subscription('default');

        if ($subscription && $subscription->onGracePeriod()) {
            $subscription->resume();
            SubscriptionSync::syncState($team);
        }
    }

    public function mount(): void
    {
        if (request('subscribed')) {
            $team = auth()->user()->currentTeam;
            if ($team->stripe_id) {
                try {
                    SubscriptionSync::createFromStripe($team);
                } catch (\Throwable $e) {
                    // Silent fail
                }
            }
        }
    }

    public function render()
    {
        $team = auth()->user()->currentTeam;
        $currency = CurrencyHelper::pricing();
        $plan = app(PlanLimiter::class)->getPlan($team);
        $limits = app(PlanLimiter::class)->limits($team);
        $subscription = $team->subscription('default');

        return view('livewire.billing.billing-page', [
            'currency' => $currency,
            'currentPlan' => $plan,
            'limits' => $limits,
            'subscription' => $subscription,
        ]);
    }
}
