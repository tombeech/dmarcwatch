<?php

namespace App\Livewire\Reports;

use App\Models\DmarcReport;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ReportShow extends Component
{
    public DmarcReport $report;

    public function mount(DmarcReport $report): void
    {
        $this->report = $report;
    }

    public function render()
    {
        $records = $this->report->records()->with('sendingSource')->get();

        return view('livewire.reports.report-show', [
            'records' => $records,
        ]);
    }
}
