<?php

namespace App\Livewire;

use App\Enums\AlertChannelType;
use App\Enums\DmarcEventType;
use App\Jobs\CheckDomainDns;
use App\Models\AlertChannel;
use App\Models\AlertRule;
use App\Models\Domain;
use App\Services\CurrencyHelper;
use App\Services\DmarcAnalyzer;
use App\Services\SpfAnalyzer;
use App\Services\SubscriptionSync;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Onboarding extends Component
{
    public int $step = 1;

    // Step 2: Domain
    public string $domainName = '';
    public ?array $dmarcCheck = null;
    public ?array $spfCheck = null;
    public string $generatedRua = '';
    public ?string $lookupError = null;
    public ?int $createdDomainId = null;

    // Step 3: Alert channel
    public string $channelName = '';
    public string $channelType = 'email';
    public string $emailAddress = '';
    public string $slackWebhook = '';
    public string $webhookUrl = '';
    public string $webhookSecret = '';
    public string $pushoverUserKey = '';
    public string $pushoverAppToken = '';
    public ?int $createdChannelId = null;

    // Step 4: Alert rule
    public ?int $ruleChannelId = null;
    public ?int $ruleDomainId = null;
    public array $selectedEvents = [];
    public ?int $createdRuleId = null;

    public function mount(): void
    {
        $team = auth()->user()->currentTeam;

        if ($team->onboarded_at) {
            $this->redirect(route('dashboard'), navigate: true);
        }

        $this->emailAddress = auth()->user()->email;
        $this->channelName = 'Email Alerts';
        $this->selectedEvents = array_column(DmarcEventType::cases(), 'value');

        if (request('step')) {
            $this->step = (int) request('step');
        }

        if (request('subscribed') && $team->stripe_id) {
            try {
                SubscriptionSync::createFromStripe($team);
            } catch (\Throwable $e) {
                Log::warning('Onboarding: failed to sync Stripe subscription', [
                    'team_id' => $team->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    // -- Step 1: Plan selection --

    public function subscribe(string $plan, int $extraBundles = 0): void
    {
        $team = auth()->user()->currentTeam;

        $priceIds = [
            'pro' => config('dmarcwatch.stripe.prices.pro_monthly'),
            'enterprise' => config('dmarcwatch.stripe.prices.enterprise_monthly'),
        ];

        if (! isset($priceIds[$plan])) {
            return;
        }

        try {
            $builder = $team->newSubscription('default', $priceIds[$plan]);

            if ($plan === 'enterprise' && $extraBundles > 0) {
                $addonPriceId = config('dmarcwatch.stripe.prices.domain_addon');
                if ($addonPriceId) {
                    $builder->price($addonPriceId, $extraBundles);
                }
            }

            if (! $team->stripe_id) {
                $builder->trialDays(config('dmarcwatch.trial_days', 14));
            }

            $checkout = $builder->checkout([
                'success_url' => route('onboarding') . '?step=2&subscribed=1',
                'cancel_url' => route('onboarding') . '?step=1&canceled=1',
            ]);

            $this->redirect($checkout->url);
        } catch (\Exception $e) {
            session()->flash('error', 'Unable to create checkout session: ' . $e->getMessage());
        }
    }

    public function skipPlan(): void
    {
        $this->step = 2;
    }

    // -- Step 2: Add domain --

    public function checkDomain(): void
    {
        $domain = trim(strtolower($this->domainName));

        if (empty($domain) || strlen($domain) < 4) {
            $this->lookupError = 'Please enter a valid domain name.';
            return;
        }

        $validator = validator(['domain' => $domain], [
            'domain' => ['required', 'string', 'max:253', 'regex:/^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z]{2,})+$/'],
        ]);

        if ($validator->fails()) {
            $this->lookupError = 'Please enter a valid domain name (e.g. example.com).';
            return;
        }

        $team = auth()->user()->currentTeam;
        $existing = Domain::withTrashed()->where('name', $domain)->where('team_id', $team->id)->first();
        if ($existing && ! $existing->trashed()) {
            $this->lookupError = 'This domain is already being monitored.';
            return;
        }

        $this->lookupError = null;

        $dmarcAnalyzer = app(DmarcAnalyzer::class);
        $this->dmarcCheck = $dmarcAnalyzer->analyze($domain);

        $spfAnalyzer = app(SpfAnalyzer::class);
        $this->spfCheck = $spfAnalyzer->analyze($domain);

        $inboundDomain = config('dmarcwatch.inbound_email.domain', 'reports.dmarcwatch.app');
        $this->generatedRua = Str::random(8) . '@' . $inboundDomain;
    }

    public function saveDomain(): void
    {
        $domain = trim(strtolower($this->domainName));
        $team = auth()->user()->currentTeam;

        if (empty($domain)) {
            $this->lookupError = 'Please enter a domain name.';
            return;
        }

        $existing = Domain::withTrashed()->where('name', $domain)->where('team_id', $team->id)->first();

        if ($existing && ! $existing->trashed()) {
            $this->lookupError = 'This domain is already being monitored.';
            return;
        }

        $rua = $this->generatedRua ?: (Str::random(8) . '@' . config('dmarcwatch.inbound_email.domain'));

        if ($existing && $existing->trashed()) {
            $existing->restore();
            $existing->update(['is_active' => true, 'rua_address' => $rua]);
            $newDomain = $existing;
        } else {
            $newDomain = Domain::create([
                'team_id' => $team->id,
                'name' => $domain,
                'is_active' => true,
                'rua_address' => $rua,
            ]);
        }

        CheckDomainDns::dispatch($newDomain);

        $this->createdDomainId = $newDomain->id;
        $this->ruleDomainId = $newDomain->id;
        $this->step = 3;
    }

    public function skipDomain(): void
    {
        $this->step = 3;
    }

    // -- Step 3: Alert channel --

    public function saveChannel(): void
    {
        $this->validate([
            'channelName' => ['required', 'string', 'max:100'],
            'channelType' => ['required', 'string', 'in:' . implode(',', array_column(AlertChannelType::cases(), 'value'))],
        ]);

        $team = auth()->user()->currentTeam;
        $channelType = AlertChannelType::from($this->channelType);

        $config = match ($this->channelType) {
            'email' => ['email' => $this->emailAddress],
            'slack' => ['webhook_url' => $this->slackWebhook],
            'webhook' => ['url' => $this->webhookUrl, 'secret' => $this->webhookSecret],
            'pushover' => ['user_key' => $this->pushoverUserKey, 'app_token' => $this->pushoverAppToken],
            default => [],
        };

        $channel = AlertChannel::create([
            'team_id' => $team->id,
            'name' => $this->channelName,
            'type' => $this->channelType,
            'config' => $config,
            'is_active' => true,
            'is_verified' => $channelType !== AlertChannelType::EMAIL,
        ]);

        $this->createdChannelId = $channel->id;
        $this->ruleChannelId = $channel->id;
        $this->step = 4;
    }

    public function skipChannel(): void
    {
        $this->step = 4;
    }

    // -- Step 4: Alert rule --

    public function saveRule(): void
    {
        $channelId = $this->ruleChannelId ?? $this->createdChannelId;

        if (! $channelId) {
            $this->completeOnboarding();
            return;
        }

        $team = auth()->user()->currentTeam;

        AlertRule::create([
            'team_id' => $team->id,
            'alert_channel_id' => $channelId,
            'domain_id' => $this->ruleDomainId,
            'event_types' => $this->selectedEvents ?: null,
            'is_active' => true,
        ]);

        $this->completeOnboarding();
    }

    public function skipRule(): void
    {
        $this->completeOnboarding();
    }

    public function completeOnboarding(): void
    {
        $team = auth()->user()->currentTeam;
        $team->update(['onboarded_at' => now()]);

        session()->flash('success', 'Setup complete! Your DMARC monitoring is ready to go.');
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function goToStep(int $step): void
    {
        if ($step >= 1 && $step <= 4 && $step <= $this->step) {
            $this->step = $step;
        }
    }

    public function render()
    {
        $currency = CurrencyHelper::pricing();
        $team = auth()->user()->currentTeam;
        $channels = AlertChannel::where('team_id', $team->id)->orderBy('name')->get();
        $domains = Domain::where('team_id', $team->id)->orderBy('name')->get();

        return view('livewire.onboarding', [
            'currency' => $currency,
            'channels' => $channels,
            'domains' => $domains,
        ]);
    }
}
