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
    public bool $showModal = false;
    public ?int $editingRuleId = null;

    public array $form = [
        'name' => '',
        'event_type' => 'compliance_drop',
        'channel_id' => '',
        'domain_id' => '',
        'threshold_value' => '',
        'is_active' => true,
    ];

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $rule = AlertRule::findOrFail($id);
        $this->editingRuleId = $id;
        $this->form = [
            'name' => $this->describeRule($rule),
            'event_type' => $rule->event_types[0] ?? 'compliance_drop',
            'channel_id' => $rule->alert_channel_id ?? '',
            'domain_id' => $rule->domain_id ?? '',
            'threshold_value' => $rule->threshold_value ?? '',
            'is_active' => $rule->is_active,
        ];
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function saveRule(): void
    {
        $this->validate([
            'form.event_type' => ['required', 'string'],
            'form.channel_id' => ['required', 'exists:alert_channels,id'],
            'form.domain_id' => ['nullable', 'exists:domains,id'],
            'form.threshold_value' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $team = auth()->user()->currentTeam;
        $eventType = $this->form['event_type'];

        $data = [
            'team_id' => $team->id,
            'alert_channel_id' => $this->form['channel_id'],
            'domain_id' => $this->form['domain_id'] ?: null,
            'event_types' => [$eventType],
            'threshold_type' => $eventType === 'compliance_drop' ? 'compliance_below' : null,
            'threshold_value' => $eventType === 'compliance_drop' ? $this->form['threshold_value'] : null,
            'is_active' => $this->form['is_active'] ?? true,
        ];

        if ($this->editingRuleId) {
            AlertRule::findOrFail($this->editingRuleId)->update($data);
        } else {
            AlertRule::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleRule(int $id): void
    {
        $rule = AlertRule::findOrFail($id);
        $rule->update(['is_active' => ! $rule->is_active]);
    }

    public function deleteRule(int $id): void
    {
        AlertRule::findOrFail($id)->delete();
    }

    protected function resetForm(): void
    {
        $this->editingRuleId = null;
        $this->form = [
            'name' => '',
            'event_type' => 'compliance_drop',
            'channel_id' => '',
            'domain_id' => '',
            'threshold_value' => '',
            'is_active' => true,
        ];
    }

    protected function describeRule(AlertRule $rule): string
    {
        $eventType = $rule->event_types[0] ?? 'unknown';
        $label = str_replace('_', ' ', ucfirst($eventType));

        return $label;
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
