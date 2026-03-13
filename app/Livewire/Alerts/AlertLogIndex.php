<?php

namespace App\Livewire\Alerts;

use App\Models\AlertChannel;
use App\Models\AlertLog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AlertLogIndex extends Component
{
    use WithPagination;

    #[Url]
    public ?int $channelFilter = null;

    #[Url]
    public ?string $statusFilter = null;

    #[Url]
    public ?string $dateRange = '30';

    public bool $showDetailModal = false;
    public ?AlertLog $selectedAlert = null;

    public function showDetail(int $id): void
    {
        $this->selectedAlert = AlertLog::with(['alertRule', 'alertChannel'])->find($id);
        $this->showDetailModal = true;
    }

    public function closeDetail(): void
    {
        $this->showDetailModal = false;
        $this->selectedAlert = null;
    }

    public function render()
    {
        $team = auth()->user()->currentTeam;

        $baseQuery = AlertLog::whereHas('alertRule', fn ($q) => $q->withoutGlobalScope('team')->where('team_id', $team->id));

        $totalAlerts = (clone $baseQuery)->count();
        $deliveredAlerts = (clone $baseQuery)->where('status', 'sent')->count();
        $failedAlerts = (clone $baseQuery)->where('status', 'failed')->count();

        $query = clone $baseQuery;
        $query->with(['alertRule', 'alertChannel']);

        if ($this->channelFilter) {
            $query->where('alert_channel_id', $this->channelFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dateRange) {
            $query->where('sent_at', '>=', now()->subDays((int) $this->dateRange));
        }

        $alerts = $query->latest('sent_at')->paginate(20);
        $availableChannels = AlertChannel::where('team_id', $team->id)->orderBy('name')->get();

        return view('livewire.alerts.alert-log-index', [
            'alerts' => $alerts,
            'availableChannels' => $availableChannels,
            'totalAlerts' => $totalAlerts,
            'deliveredAlerts' => $deliveredAlerts,
            'failedAlerts' => $failedAlerts,
        ]);
    }
}
