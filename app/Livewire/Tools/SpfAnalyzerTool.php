<?php

namespace App\Livewire\Tools;

use App\Services\SpfAnalyzer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SpfAnalyzerTool extends Component
{
    public string $domain = '';
    public ?array $result = null;
    public ?string $error = null;

    public function analyze(): void
    {
        $domain = trim(strtolower($this->domain));

        if (empty($domain)) {
            $this->error = 'Please enter a domain name.';
            return;
        }

        $this->error = null;
        $analyzer = app(SpfAnalyzer::class);
        $this->result = $analyzer->analyze($domain);
    }

    public function render()
    {
        return view('livewire.tools.spf-analyzer');
    }
}
