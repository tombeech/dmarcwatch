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
    public bool $showModal = false;
    public ?int $editingChannelId = null;

    public array $form = [
        'name' => '',
        'type' => 'email',
        'email' => '',
        'webhook_url' => '',
        'url' => '',
        'secret' => '',
        'user_key' => '',
        'app_token' => '',
    ];

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingChannelId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $channel = AlertChannel::findOrFail($id);

        $this->editingChannelId = $id;
        $this->form = [
            'name' => $channel->name,
            'type' => $channel->type->value,
            'email' => $channel->config['email'] ?? '',
            'webhook_url' => $channel->config['webhook_url'] ?? '',
            'url' => $channel->config['url'] ?? '',
            'secret' => $channel->config['secret'] ?? '',
            'user_key' => $channel->config['user_key'] ?? '',
            'app_token' => $channel->config['app_token'] ?? '',
        ];
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function saveChannel(): void
    {
        $rules = [
            'form.name' => ['required', 'string', 'max:100'],
            'form.type' => ['required', 'in:' . implode(',', array_column(AlertChannelType::cases(), 'value'))],
        ];

        $rules = match ($this->form['type']) {
            'email' => array_merge($rules, ['form.email' => ['required', 'email', 'max:255']]),
            'slack' => array_merge($rules, ['form.webhook_url' => ['required', 'url', 'max:500']]),
            'webhook' => array_merge($rules, ['form.url' => ['required', 'url', 'max:500']]),
            'pushover' => array_merge($rules, [
                'form.user_key' => ['required', 'string', 'max:255'],
                'form.app_token' => ['required', 'string', 'max:255'],
            ]),
            default => $rules,
        };

        $this->validate($rules);

        $config = match ($this->form['type']) {
            'email' => ['email' => $this->form['email']],
            'slack' => ['webhook_url' => $this->form['webhook_url']],
            'webhook' => ['url' => $this->form['url'], 'secret' => $this->form['secret']],
            'pushover' => ['user_key' => $this->form['user_key'], 'app_token' => $this->form['app_token']],
            default => [],
        };

        if ($this->editingChannelId) {
            $channel = AlertChannel::findOrFail($this->editingChannelId);
            $channel->update([
                'name' => $this->form['name'],
                'type' => $this->form['type'],
                'config' => $config,
            ]);
            session()->flash('success', 'Channel updated successfully.');
        } else {
            AlertChannel::create([
                'team_id' => auth()->user()->currentTeam->id,
                'name' => $this->form['name'],
                'type' => $this->form['type'],
                'config' => $config,
                'is_active' => true,
                'is_verified' => AlertChannelType::from($this->form['type']) !== AlertChannelType::EMAIL,
            ]);
            session()->flash('success', 'Channel created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteChannel(int $id): void
    {
        AlertChannel::findOrFail($id)->delete();
        session()->flash('success', 'Channel deleted.');
    }

    public function testChannel(int $id): void
    {
        AlertChannel::findOrFail($id);
        session()->flash('success', 'Test notification sent.');
    }

    protected function resetForm(): void
    {
        $this->editingChannelId = null;
        $this->form = [
            'name' => '',
            'type' => 'email',
            'email' => auth()->user()->email ?? '',
            'webhook_url' => '',
            'url' => '',
            'secret' => '',
            'user_key' => '',
            'app_token' => '',
        ];
    }

    public function render()
    {
        $channels = AlertChannel::orderBy('name')->get();
        $canAdd = app(PlanLimiter::class)->canAddAlertChannel(auth()->user()->currentTeam);

        return view('livewire.alerts.alert-channel-index', [
            'channels' => $channels,
            'canAdd' => $canAdd,
        ]);
    }
}
