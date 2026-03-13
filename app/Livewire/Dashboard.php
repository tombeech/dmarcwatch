<?php

namespace App\Livewire;

use App\Models\DmarcReport;
use App\Models\Domain;
use App\Models\ReportRecord;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function mount(): void
    {
        $team = auth()->user()->currentTeam;

        if (! $team->onboarded_at) {
            $this->redirect(route('onboarding'), navigate: true);
        }
    }

    public function render()
    {
        $team = auth()->user()->currentTeam;

        $totalDomains = Domain::where('team_id', $team->id)->count();
        $avgCompliance = Domain::where('team_id', $team->id)->whereNotNull('compliance_score')->avg('compliance_score') ?? 0;
        $emailsProcessed = DmarcReport::where('team_id', $team->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('total_messages');
        $failingSources = ReportRecord::whereHas('dmarcReport', fn ($q) => $q->where('team_id', $team->id))
            ->where('created_at', '>=', now()->subDays(30))
            ->where(fn ($q) => $q->where('dkim_result', '!=', 'pass')->orWhere('spf_result', '!=', 'pass'))
            ->distinct('source_ip')
            ->count('source_ip');

        $recentReports = DmarcReport::with('domain')
            ->where('team_id', $team->id)
            ->latest('received_at')
            ->take(10)
            ->get();

        $domains = Domain::where('team_id', $team->id)
            ->withCount('dmarcReports')
            ->orderByDesc('compliance_score')
            ->take(12)
            ->get();

        return view('livewire.dashboard', [
            'totalDomains' => $totalDomains,
            'avgCompliance' => round($avgCompliance, 1),
            'emailsProcessed' => $emailsProcessed,
            'failingSources' => $failingSources,
            'recentReports' => $recentReports,
            'domains' => $domains,
        ]);
    }
}
