<?php

namespace App\Livewire\Domains;

use App\Jobs\CheckDomainDns;
use App\Models\Domain;
use App\Models\ReportRecord;
use App\Services\ComplianceScorer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DomainShow extends Component
{
    public Domain $domain;
    public string $activeTab = 'overview';

    public function mount(Domain $domain): void
    {
        $this->domain = $domain;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function runDnsCheck(): void
    {
        CheckDomainDns::dispatch($this->domain);
        session()->flash('message', 'DNS check queued. Results will appear shortly.');
    }

    public function recalculateCompliance(): void
    {
        $scorer = app(ComplianceScorer::class);
        $scorer->score($this->domain);
        $this->domain->refresh();
    }

    public function toggleActive(): void
    {
        $this->domain->update(['is_active' => ! $this->domain->is_active]);
        $this->domain->refresh();
    }

    public function render()
    {
        $recentReports = $this->domain->dmarcReports()->latest('received_at')->take(10)->get();
        $dnsChecks = $this->domain->dnsChecks()->latest('checked_at')->take(5)->get();

        $topSources = ReportRecord::whereHas('dmarcReport', fn ($q) => $q->where('domain_id', $this->domain->id))
            ->selectRaw('source_ip, sending_source_id, SUM(count) as total_messages, MAX(created_at) as last_seen')
            ->groupBy('source_ip', 'sending_source_id')
            ->orderByDesc('total_messages')
            ->with('sendingSource')
            ->take(20)
            ->get();

        return view('livewire.domains.domain-show', [
            'recentReports' => $recentReports,
            'dnsChecks' => $dnsChecks,
            'topSources' => $topSources,
        ]);
    }
}
