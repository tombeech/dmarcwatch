<?php

namespace App\Livewire\Domains;

use App\Jobs\CheckDomainDns;
use App\Models\Domain;
use App\Services\DmarcAnalyzer;
use App\Services\DmarcRecordGenerator;
use App\Services\SpfAnalyzer;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DomainCreate extends Component
{
    public string $domainName = '';
    public ?array $dmarcCheck = null;
    public ?array $spfCheck = null;
    public string $generatedRua = '';
    public string $suggestedDmarcRecord = '';
    public ?string $error = null;
    public bool $domainSaved = false;

    public function checkDomain(): void
    {
        $domain = trim(strtolower($this->domainName));

        $validator = validator(['domain' => $domain], [
            'domain' => ['required', 'string', 'max:253', 'regex:/^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z]{2,})+$/'],
        ]);

        if ($validator->fails()) {
            $this->error = 'Please enter a valid domain name.';
            return;
        }

        $team = auth()->user()->currentTeam;
        if (Domain::withTrashed()->where('name', $domain)->where('team_id', $team->id)->whereNull('deleted_at')->exists()) {
            $this->error = 'This domain is already being monitored.';
            return;
        }

        $this->error = null;
        $this->dmarcCheck = app(DmarcAnalyzer::class)->analyze($domain);
        $this->spfCheck = app(SpfAnalyzer::class)->analyze($domain);

        $inboundDomain = config('dmarcwatch.inbound_email.domain', 'reports.dmarcwatch.app');
        $this->generatedRua = Str::random(8) . '@' . $inboundDomain;

        $generator = app(DmarcRecordGenerator::class);
        $this->suggestedDmarcRecord = $generator->generate([
            'policy' => 'quarantine',
            'rua' => [$this->generatedRua],
            'pct' => 100,
            'adkim' => 'r',
            'aspf' => 'r',
        ]);
    }

    public function saveDomain(): void
    {
        $domain = trim(strtolower($this->domainName));
        $team = auth()->user()->currentTeam;

        $rua = $this->generatedRua ?: (Str::random(8) . '@' . config('dmarcwatch.inbound_email.domain'));

        $newDomain = Domain::create([
            'team_id' => $team->id,
            'name' => $domain,
            'is_active' => true,
            'rua_address' => $rua,
        ]);

        CheckDomainDns::dispatch($newDomain);
        $this->domainSaved = true;

        session()->flash('message', 'Domain added successfully. DNS check is running.');
    }

    public function render()
    {
        return view('livewire.domains.domain-create');
    }
}
