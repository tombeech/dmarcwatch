<?php

namespace App\Livewire\Reports;

use App\Models\DmarcReport;
use App\Models\Domain;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ReportIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public ?int $domainFilter = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedDomainFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $team = auth()->user()->currentTeam;
        $query = DmarcReport::with('domain')->where('team_id', $team->id);

        if ($this->domainFilter) {
            $query->where('domain_id', $this->domainFilter);
        }

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('reporter_org', 'like', '%' . $this->search . '%')
                    ->orWhereHas('domain', fn ($dq) => $dq->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        $reports = $query->latest('received_at')->paginate(20);
        $domains = Domain::where('team_id', $team->id)->orderBy('name')->get();

        return view('livewire.reports.report-index', [
            'reports' => $reports,
            'domains' => $domains,
        ]);
    }
}
