<?php

namespace App\Livewire\Tools;

use App\Services\DkimVerifier;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DkimChecker extends Component
{
    public string $domain = '';
    public string $selector = 'default';
    public ?array $result = null;
    public ?string $error = null;

    public function check(): void
    {
        $domain = trim(strtolower($this->domain));
        $selector = trim(strtolower($this->selector));

        if (empty($domain) || empty($selector)) {
            $this->error = 'Please enter both a domain and a selector.';
            return;
        }

        $this->error = null;
        $verifier = app(DkimVerifier::class);
        $this->result = $verifier->verify($domain, $selector);
    }

    public function render()
    {
        return view('livewire.tools.dkim-checker');
    }
}
