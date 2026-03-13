<?php

namespace App\Livewire\Sources;

use App\Models\ReportRecord;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SourceIndex extends Component
{
    public function render()
    {
        $team = auth()->user()->currentTeam;

        $sources = ReportRecord::whereHas('dmarcReport', fn ($q) => $q->where('team_id', $team->id))
            ->selectRaw('source_ip, sending_source_id, SUM(count) as total_messages, SUM(CASE WHEN dkim_result = \'pass\' AND spf_result = \'pass\' THEN count ELSE 0 END) as pass_messages, MAX(report_records.created_at) as last_seen')
            ->groupBy('source_ip', 'sending_source_id')
            ->with('sendingSource')
            ->orderByDesc('total_messages')
            ->get();

        $authorized = $sources->filter(fn ($s) => $s->sendingSource !== null);
        $unknown = $sources->filter(fn ($s) => $s->sendingSource === null);

        return view('livewire.sources.source-index', [
            'authorized' => $authorized,
            'unknown' => $unknown,
        ]);
    }
}
