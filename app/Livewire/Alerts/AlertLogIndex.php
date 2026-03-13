<?php

namespace App\Livewire\Alerts;

use App\Models\AlertLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AlertLogIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $team = auth()->user()->currentTeam;

        $logs = AlertLog::with(['alertRule', 'alertChannel'])
            ->whereHas('alertRule', fn ($q) => $q->withoutGlobalScope('team')->where('team_id', $team->id))
            ->latest('sent_at')
            ->paginate(20);

        return view('livewire.alerts.alert-log-index', [
            'logs' => $logs,
        ]);
    }
}
