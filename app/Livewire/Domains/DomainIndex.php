<?php

namespace App\Livewire\Domains;

use App\Models\Domain;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class DomainIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    #[Url]
    public string $sortBy = 'name';

    #[Url]
    public string $sortDir = 'asc';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = Domain::withCount('dmarcReports');

        if ($this->search !== '') {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $allowedSorts = ['name', 'compliance_score', 'created_at', 'last_dns_check_at'];
        if (in_array($this->sortBy, $allowedSorts)) {
            $query->orderBy($this->sortBy, $this->sortDir);
        }

        $domains = $query->paginate(15);

        return view('livewire.domains.domain-index', [
            'domains' => $domains,
        ]);
    }
}
