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
    public ?array $commonSelectorResults = null;

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

        $commonSelectors = ['default', 'google', 'selector1', 'selector2', 'k1', 'mandrill', 'everlytickey1', 'everlytickey2', 'dkim', 'mail'];
        $this->commonSelectorResults = [];
        foreach ($commonSelectors as $cs) {
            $check = $verifier->verify($domain, $cs);
            $this->commonSelectorResults[] = [
                'selector' => $cs,
                'found' => $check['found'],
                'key_length' => isset($check['tags']['p']) ? strlen(base64_decode($check['tags']['p'])) * 8 : null,
                'key_type' => $check['tags']['k'] ?? ($check['found'] ? 'rsa' : null),
            ];
        }
    }

    public function render()
    {
        return view('livewire.tools.dkim-checker');
    }
}
