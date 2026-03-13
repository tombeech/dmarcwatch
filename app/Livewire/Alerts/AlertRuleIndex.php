<?php

namespace App\Livewire\Alerts;

use App\Enums\DmarcEventType;
use App\Models\AlertChannel;
use App\Models\AlertRule;
use App\Models\Domain;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AlertRuleIndex extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public ?int $channelId = null;
    public ?int $domainId = null;
    public array $eventTypes = [];

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $rule = AlertRule::findOrFail($id);
            $this->editingId = $id;
            $this->channelId = $rule->alert_channel_id;
            $this->domainId = $rule->domain_id;
            $this->eventTypes = $rule->event_types ?? [];
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'channelId' => ['required', 'exists:alert_channels,id'],
        ]);

        $team = auth()->user()->currentTeam;

        $data = [
            'team_id' => $team->id,
            'alert_channel_id' => $this->channelId,
            'domain_id' => $this->domainId,
            'event_types' => ! empty($this->eventTypes) ? $this->eventTypes : null,
            'is_active' => true,
        ];

        if ($this->editingId) {
            AlertRule::findOrFail($this->editingId)->update($data);
        } else {
            AlertRule::create($data);
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $rule = AlertRule::findOrFail($id);
        $rule->update(['is_active' => ! $rule->is_active]);
    }

    public function delete(int $id): void
    {
        AlertRule::findOrFail($id)->delete();
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->channelId = null;
        $this->domainId = null;
        $this->eventTypes = array_column(DmarcEventType::cases(), 'value');
    }

    public function render()
    {
        $team = auth()->user()->currentTeam;
        $rules = AlertRule::with(['alertChannel', 'domain'])->where('team_id', $team->id)->get();
        $channels = AlertChannel::where('team_id', $team->id)->where('is_active', true)->get();
        $domains = Domain::where('team_id', $team->id)->orderBy('name')->get();
        $eventTypes = DmarcEventType::cases();

        return view('livewire.alerts.alert-rule-index', [
            'rules' => $rules,
            'channels' => $channels,
            'domains' => $domains,
            'eventTypes' => $eventTypes,
        ]);
    }
}
