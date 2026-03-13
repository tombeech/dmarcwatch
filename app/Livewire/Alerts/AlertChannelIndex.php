<?php

namespace App\Livewire\Alerts;

use App\Enums\AlertChannelType;
use App\Models\AlertChannel;
use App\Services\PlanLimiter;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AlertChannelIndex extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $channelName = '';
    public string $channelType = 'email';
    public string $emailAddress = '';
    public string $slackWebhook = '';
    public string $webhookUrl = '';
    public string $webhookSecret = '';
    public string $pushoverUserKey = '';
    public string $pushoverAppToken = '';

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $channel = AlertChannel::findOrFail($id);
            $this->editingId = $id;
            $this->channelName = $channel->name;
            $this->channelType = $channel->type->value;
            $config = $channel->config;
            $this->emailAddress = $config['email'] ?? '';
            $this->slackWebhook = $config['webhook_url'] ?? '';
            $this->webhookUrl = $config['url'] ?? '';
            $this->webhookSecret = $config['secret'] ?? '';
            $this->pushoverUserKey = $config['user_key'] ?? '';
            $this->pushoverAppToken = $config['app_token'] ?? '';
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'channelName' => ['required', 'string', 'max:100'],
            'channelType' => ['required', 'in:' . implode(',', array_column(AlertChannelType::cases(), 'value'))],
        ]);

        $team = auth()->user()->currentTeam;
        $config = match ($this->channelType) {
            'email' => ['email' => $this->emailAddress],
            'slack' => ['webhook_url' => $this->slackWebhook],
            'webhook' => ['url' => $this->webhookUrl, 'secret' => $this->webhookSecret],
            'pushover' => ['user_key' => $this->pushoverUserKey, 'app_token' => $this->pushoverAppToken],
            default => [],
        };

        if ($this->editingId) {
            $channel = AlertChannel::findOrFail($this->editingId);
            $channel->update([
                'name' => $this->channelName,
                'type' => $this->channelType,
                'config' => $config,
            ]);
        } else {
            AlertChannel::create([
                'team_id' => $team->id,
                'name' => $this->channelName,
                'type' => $this->channelType,
                'config' => $config,
                'is_active' => true,
                'is_verified' => AlertChannelType::from($this->channelType) !== AlertChannelType::EMAIL,
            ]);
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $channel = AlertChannel::findOrFail($id);
        $channel->update(['is_active' => ! $channel->is_active]);
    }

    public function delete(int $id): void
    {
        AlertChannel::findOrFail($id)->delete();
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->channelName = '';
        $this->channelType = 'email';
        $this->emailAddress = auth()->user()->email ?? '';
        $this->slackWebhook = '';
        $this->webhookUrl = '';
        $this->webhookSecret = '';
        $this->pushoverUserKey = '';
        $this->pushoverAppToken = '';
    }

    public function render()
    {
        $team = auth()->user()->currentTeam;
        $channels = AlertChannel::where('team_id', $team->id)->orderBy('name')->get();
        $canAdd = app(PlanLimiter::class)->canAddAlertChannel($team);

        return view('livewire.alerts.alert-channel-index', [
            'channels' => $channels,
            'canAdd' => $canAdd,
        ]);
    }
}
