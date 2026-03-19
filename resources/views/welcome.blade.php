<x-marketing-layout title="DMARCWatch - Take Control of Your Email Authentication" meta-description="Monitor DMARC, SPF, and DKIM records. Process aggregate reports, identify sending sources, and protect your domain from email spoofing.">
<!-- Hero -->
<section class="bg-forest-900 text-white py-20 lg:py-28 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(74,222,128,0.06)_0%,_transparent_50%)]"></div>
    <div class="absolute inset-0 opacity-15" style="background-image: url('{{ asset('images/bg2.svg') }}'); background-attachment: fixed; background-position: center; background-size: cover; background-repeat: no-repeat;"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight">
                Take Control of Your <span class="text-lime-400">Email Authentication</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-gray-300 leading-relaxed">
                Monitor DMARC, SPF, and DKIM records. Process aggregate reports automatically, identify every sending source, and protect your domain from spoofing.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-lime-400 text-forest-900 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-lime-300 transition">Start Free</a>
                <a href="/features" class="border border-gray-600 text-white px-8 py-3 rounded-lg text-lg font-medium hover:border-gray-400 transition">Learn More</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="bg-forest-950 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-3xl font-bold text-lime-400">3</div>
                <div class="text-sm text-gray-400 mt-1">Protocols Monitored</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-lime-400">&lt; 60s</div>
                <div class="text-sm text-gray-400 mt-1">Report Processing</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-lime-400">100+</div>
                <div class="text-sm text-gray-400 mt-1">Known Senders</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-lime-400">5</div>
                <div class="text-sm text-gray-400 mt-1">Alert Channels</div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-forest-900">Everything you need for email authentication</h2>
            <p class="mt-4 text-lg text-gray-600">From DMARC report processing to compliance scoring and alerting.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach([
                ['title' => 'DMARC Report Processing', 'desc' => 'Automatically receive and parse aggregate reports from Google, Microsoft, Yahoo, and other providers.', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['title' => 'DNS Verification', 'desc' => 'Continuously monitor your DMARC, SPF, and DKIM records for validity and configuration issues.', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['title' => 'Compliance Scoring', 'desc' => 'Get a 0-100 compliance score for each domain based on policy, pass rates, alignment, and DNS validity.', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['title' => 'Source Identification', 'desc' => 'Automatically identify sending sources from IP addresses. Know who is sending email on your behalf.', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                ['title' => 'Multi-Channel Alerts', 'desc' => 'Get notified via email, Slack, webhooks, or Pushover when authentication failures spike or records change.', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                ['title' => 'Record Generators', 'desc' => 'Build valid DMARC and SPF records with our step-by-step wizards. Analyze existing records for issues.', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
            ] as $feature)
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="w-10 h-10 bg-lime-50 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-forest-900 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How it works -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-forest-900">How it works</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-12">
            @foreach([
                ['step' => '1', 'title' => 'Add your domain', 'desc' => 'Enter your domain and we\'ll check your existing DMARC, SPF, and DKIM records. Get a unique reporting address to collect aggregate reports.'],
                ['step' => '2', 'title' => 'Publish your DMARC record', 'desc' => 'Update your DNS with the DMARC record we generate, pointing the RUA tag to your unique DMARCWatch address.'],
                ['step' => '3', 'title' => 'Monitor and improve', 'desc' => 'Reports flow in automatically. See who\'s sending email for your domain, track compliance, and get alerted on issues.'],
            ] as $step)
                <div class="text-center">
                    <div class="w-12 h-12 bg-forest-900 text-lime-400 rounded-full flex items-center justify-center text-xl font-bold mx-auto mb-4">{{ $step['step'] }}</div>
                    <h3 class="text-lg font-semibold text-forest-900 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="bg-forest-900 py-16">
    <div class="max-w-4xl mx-auto text-center px-4">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to secure your email?</h2>
        <p class="text-gray-300 mb-8">Start monitoring your DMARC compliance for free. No credit card required.</p>
        <a href="{{ route('register') }}" class="bg-lime-400 text-forest-900 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-lime-300 transition">Create Free Account</a>
    </div>
</section>
</x-marketing-layout>
