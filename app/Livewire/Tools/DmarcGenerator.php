<?php

namespace App\Livewire\Tools;

use App\Services\DmarcRecordGenerator;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DmarcGenerator extends Component
{
    public string $policy = 'none';
    public string $subdomainPolicy = '';
    public string $rua = '';
    public string $ruf = '';
    public int $pct = 100;
    public string $adkim = 'r';
    public string $aspf = 'r';
    public string $fo = '0';
    public int $ri = 86400;
    public string $generatedRecord = '';

    public function generate(): void
    {
        $generator = app(DmarcRecordGenerator::class);

        $options = [
            'policy' => $this->policy,
            'pct' => $this->pct,
            'adkim' => $this->adkim,
            'aspf' => $this->aspf,
            'fo' => $this->fo !== '0' ? $this->fo : null,
            'ri' => $this->ri !== 86400 ? $this->ri : null,
        ];

        if ($this->subdomainPolicy) {
            $options['subdomain_policy'] = $this->subdomainPolicy;
        }

        if ($this->rua) {
            $options['rua'] = array_map('trim', explode(',', $this->rua));
        }

        if ($this->ruf) {
            $options['ruf'] = array_map('trim', explode(',', $this->ruf));
        }

        $this->generatedRecord = $generator->generate($options);
    }

    public function render()
    {
        return view('livewire.tools.dmarc-generator');
    }
}
