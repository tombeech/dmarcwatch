@extends('layouts.marketing')
@section('title', 'Features — DMARCWatch')

@section('content')
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-forest-900">Powerful DMARC monitoring features</h1>
            <p class="mt-4 text-lg text-gray-600">Everything you need to manage email authentication across all your domains.</p>
        </div>

        <!-- Report Processing -->
        <div class="mb-20">
            <h2 class="text-2xl font-bold text-forest-900 mb-8">Report Processing</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['title' => 'Automatic Parsing', 'desc' => 'DMARC aggregate reports are automatically received, decompressed, and parsed. Supports gzip and zip formats from all major providers.'],
                    ['title' => 'Source Identification', 'desc' => 'IP addresses are matched against a database of 100+ known email providers. Instantly see if Google, Microsoft, or SendGrid sent on your behalf.'],
                    ['title' => 'Compliance Scoring', 'desc' => 'Each domain receives a 0-100 compliance score based on policy strength, pass rates, alignment, and DNS configuration.'],
                ] as $f)
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                        <h3 class="font-semibold text-forest-900 mb-2">{{ $f['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- DNS Monitoring -->
        <div class="mb-20">
            <h2 class="text-2xl font-bold text-forest-900 mb-8">DNS Verification</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['title' => 'DMARC Record Analysis', 'desc' => 'Parse and validate all DMARC tags including policy, alignment, reporting URIs, and percentage.'],
                    ['title' => 'SPF Record Analysis', 'desc' => 'Parse SPF mechanisms, count DNS lookups against the 10-lookup limit, and detect common misconfigurations.'],
                    ['title' => 'DKIM Verification', 'desc' => 'Check DKIM records for valid keys across multiple selectors. Supports RSA and Ed25519 key types.'],
                ] as $f)
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                        <h3 class="font-semibold text-forest-900 mb-2">{{ $f['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Alerting -->
        <div class="mb-20">
            <h2 class="text-2xl font-bold text-forest-900 mb-8">Alerting</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['title' => 'Email Alerts', 'desc' => 'Receive alerts directly in your inbox when authentication failures spike or DNS records change.'],
                    ['title' => 'Slack Integration', 'desc' => 'Post alerts to any Slack channel via incoming webhooks. Keep your team informed in real-time.'],
                    ['title' => 'Webhooks & Pushover', 'desc' => 'Integrate with your existing monitoring via webhooks with HMAC signatures, or get mobile push via Pushover.'],
                ] as $f)
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                        <h3 class="font-semibold text-forest-900 mb-2">{{ $f['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tools -->
        <div>
            <h2 class="text-2xl font-bold text-forest-900 mb-8">Built-in Tools</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['title' => 'DMARC Record Generator', 'desc' => 'Build valid DMARC records step-by-step with policy selection, reporting URIs, alignment, and percentage controls.'],
                    ['title' => 'SPF Record Analyzer', 'desc' => 'Analyze SPF records, view include trees, count DNS lookups, and detect issues before they cause failures.'],
                    ['title' => 'DKIM Checker', 'desc' => 'Verify DKIM records for any domain and selector combination. Validate key presence and record syntax.'],
                ] as $f)
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                        <h3 class="font-semibold text-forest-900 mb-2">{{ $f['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="bg-forest-900 py-16">
    <div class="max-w-4xl mx-auto text-center px-4">
        <h2 class="text-3xl font-bold text-white mb-4">Start monitoring your domains today</h2>
        <p class="text-gray-300 mb-8">Free plan includes 3 domains. No credit card required.</p>
        <a href="{{ route('register') }}" class="bg-lime-400 text-forest-900 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-lime-300 transition">Create Free Account</a>
    </div>
</section>
@endsection