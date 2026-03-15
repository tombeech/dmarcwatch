<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MarketingLayout extends Component
{
    public function __construct(
        public string $title = 'DMARCWatch - Email Authentication Monitoring',
        public string $metaDescription = 'Monitor DMARC, SPF, and DKIM records. Receive aggregate reports, identify sending sources, and improve email deliverability.',
    ) {}

    public function render(): View
    {
        return view('layouts.marketing');
    }
}
