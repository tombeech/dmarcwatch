@php
    $pricing = \App\Services\CurrencyHelper::pricing();
    $symbol = $pricing['symbol'];
@endphp

<x-marketing-layout title="Pricing - DMARCWatch" meta-description="Simple, transparent pricing for DMARC monitoring. Start free with 3 domains, upgrade when you need more.">

{{-- Hero --}}
<section class="py-20 bg-gradient-to-b from-cream-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-forest-900 tracking-tight">Simple, transparent pricing</h1>
            <p class="mt-5 text-lg text-forest-900/60 leading-relaxed">Start free with 3 domains. Upgrade when you need more capacity, longer retention, or advanced features.</p>
        </div>
    </div>
</section>

{{-- Plan Cards --}}
<section class="pb-20 -mt-4">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-6 lg:gap-8">

            {{-- Free --}}
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200 flex flex-col">
                <div>
                    <h3 class="text-lg font-semibold text-forest-900">Free</h3>
                    <p class="text-sm text-forest-900/50 mt-1">For individuals getting started</p>
                </div>
                <div class="mt-6">
                    <span class="text-5xl font-extrabold text-forest-900">{{ $symbol }}0</span>
                    <span class="text-sm text-forest-900/50 ml-1">/month</span>
                </div>
                <ul class="mt-8 space-y-3.5 flex-1">
                    @foreach([
                        '3 domains',
                        '100 reports/month',
                        '30-day retention',
                        '1 alert channel',
                        'Daily DNS checks',
                        'Compliance scoring',
                    ] as $feature)
                        <li class="flex items-start gap-2.5 text-sm text-forest-900/70">
                            <svg class="w-4 h-4 text-lime-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center bg-gray-100 hover:bg-gray-200 text-forest-900 px-6 py-3 rounded-xl font-semibold text-sm transition-colors">Get started free</a>
            </div>

            {{-- Pro --}}
            <div class="bg-white rounded-2xl p-8 shadow-lg border-2 border-lime-400 relative flex flex-col">
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2">
                    <span class="bg-lime-400 text-forest-900 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Most Popular</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-forest-900">Pro</h3>
                    <p class="text-sm text-forest-900/50 mt-1">For teams managing multiple domains</p>
                </div>
                <div class="mt-6">
                    <span class="text-5xl font-extrabold text-forest-900">{{ $symbol }}{{ $pricing['pro'] }}</span>
                    <span class="text-sm text-forest-900/50 ml-1">/month</span>
                </div>
                <ul class="mt-8 space-y-3.5 flex-1">
                    @foreach([
                        '50 domains',
                        'Unlimited reports',
                        '365-day retention',
                        '5 alert channels (all types)',
                        'Hourly DNS checks',
                        'API access',
                        'Weekly digest emails',
                        'Source identification',
                    ] as $feature)
                        <li class="flex items-start gap-2.5 text-sm text-forest-900/70">
                            <svg class="w-4 h-4 text-lime-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center bg-lime-400 hover:bg-lime-300 text-forest-900 px-6 py-3 rounded-xl font-semibold text-sm transition-colors">Start free trial</a>
            </div>

            {{-- Enterprise --}}
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200 flex flex-col">
                <div>
                    <h3 class="text-lg font-semibold text-forest-900">Enterprise</h3>
                    <p class="text-sm text-forest-900/50 mt-1">For organisations with large portfolios</p>
                </div>
                <div class="mt-6">
                    <span class="text-5xl font-extrabold text-forest-900">{{ $symbol }}{{ $pricing['enterprise'] }}</span>
                    <span class="text-sm text-forest-900/50 ml-1">/month</span>
                </div>
                <ul class="mt-8 space-y-3.5 flex-1">
                    @foreach([
                        '100+ domains (expandable)',
                        'Unlimited reports',
                        'Unlimited retention',
                        'Unlimited alert channels & team members',
                        '15-minute DNS checks',
                        '50,000 API requests/day',
                        'Domain addon bundles',
                        'Priority support',
                    ] as $feature)
                        <li class="flex items-start gap-2.5 text-sm text-forest-900/70">
                            <svg class="w-4 h-4 text-lime-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center bg-forest-900 hover:bg-forest-800 text-white px-6 py-3 rounded-xl font-semibold text-sm transition-colors">Start free trial</a>
            </div>
        </div>
    </div>
</section>

{{-- Domain Addon Slider --}}
<section class="py-16 bg-cream-50" x-data="{ domains: 100 }">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-forest-900 mb-2">Need more domains?</h2>
        <p class="text-forest-900/60 mb-8">Enterprise plan includes 100 domains. Add more in bundles of 50 for {{ $symbol }}{{ $pricing['domain_addon'] }}/month each.</p>
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
            <input type="range" min="100" max="500" step="50" x-model="domains" class="w-full accent-lime-400 mb-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-forest-900/60">Domains: <strong class="text-forest-900" x-text="domains"></strong></span>
                <span class="text-sm text-forest-900/60">Total: <strong class="text-forest-900">{{ $symbol }}<span x-text="{{ $pricing['enterprise'] }} + (Math.max(0, (domains - 100) / 50) * {{ $pricing['domain_addon'] }})"></span>/mo</strong></span>
            </div>
        </div>
    </div>
</section>

{{-- Feature Comparison --}}
<section class="py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-forest-900 text-center mb-12">Feature comparison</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-4 pr-4 font-semibold text-forest-900">Feature</th>
                        <th class="text-center py-4 px-4 font-semibold text-forest-900">Free</th>
                        <th class="text-center py-4 px-4 font-semibold text-forest-900">Pro</th>
                        <th class="text-center py-4 px-4 font-semibold text-forest-900">Enterprise</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach([
                        ['Domains', '3', '50', '100+'],
                        ['Reports per month', '100', 'Unlimited', 'Unlimited'],
                        ['Data retention', '30 days', '365 days', 'Unlimited'],
                        ['Alert channels', '1', '5', 'Unlimited'],
                        ['DNS check frequency', 'Daily', 'Hourly', 'Every 15 min'],
                        ['Compliance scoring', true, true, true],
                        ['Source identification', true, true, true],
                        ['DMARC record generator', true, true, true],
                        ['SPF analyzer', true, true, true],
                        ['Weekly digest emails', false, true, true],
                        ['API access', false, true, true],
                        ['Slack integration', false, true, true],
                        ['Webhook alerts', false, true, true],
                        ['Pushover alerts', false, true, true],
                        ['Team members', '1', '5', 'Unlimited'],
                        ['API requests/day', '-', '5,000', '50,000'],
                        ['Domain addon bundles', false, false, true],
                        ['Priority support', false, false, true],
                    ] as $row)
                        <tr>
                            <td class="py-3 pr-4 text-forest-900/70">{{ $row[0] }}</td>
                            @for($i = 1; $i <= 3; $i++)
                                <td class="py-3 px-4 text-center">
                                    @if(is_bool($row[$i]))
                                        @if($row[$i])
                                            <svg class="w-5 h-5 text-lime-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        @else
                                            <span class="text-gray-300">&mdash;</span>
                                        @endif
                                    @else
                                        <span class="text-forest-900/70">{{ $row[$i] }}</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-20 bg-cream-50" x-data="{ openFaq: null }">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-forest-900 text-center mb-12">Frequently asked questions</h2>
        <div class="space-y-3">
            @foreach([
                ['q' => 'What happens when I reach my report limit on the free plan?', 'a' => 'Reports over your monthly limit are still received and stored, but they will not be processed or displayed until you upgrade. No data is lost.'],
                ['q' => 'Can I change plans at any time?', 'a' => 'Yes. You can upgrade or downgrade at any time. When upgrading, the new plan takes effect immediately and you are charged a prorated amount. When downgrading, the change takes effect at the end of your current billing period.'],
                ['q' => 'Is there a free trial for paid plans?', 'a' => 'Yes. Both Pro and Enterprise plans include a 14-day free trial. No credit card is required to start the trial.'],
                ['q' => 'What payment methods do you accept?', 'a' => 'We accept all major credit and debit cards (Visa, Mastercard, American Express) through Stripe. All payments are processed securely.'],
                ['q' => 'How do domain addon bundles work?', 'a' => 'On the Enterprise plan, you start with 100 domains. If you need more, you can add bundles of 50 additional domains for ' . $symbol . $pricing['domain_addon'] . '/month per bundle. Bundles are added to your existing subscription.'],
                ['q' => 'Do you offer annual billing?', 'a' => 'Yes. Annual billing is available for both Pro and Enterprise plans with a discount equivalent to two months free.'],
                ['q' => 'What counts as a "report"?', 'a' => 'A report is a single DMARC aggregate report (RUA) received from a reporting provider such as Google, Microsoft, or Yahoo. Each XML file counts as one report, regardless of how many records it contains.'],
                ['q' => 'Can I cancel my subscription?', 'a' => 'Yes. You can cancel at any time from your billing page. Your plan will remain active until the end of the current billing period. After that, your account will revert to the free plan.'],
            ] as $i => $faq)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <button @click="openFaq = openFaq === {{ $i }} ? null : {{ $i }}" class="w-full flex items-center justify-between px-6 py-4 text-left">
                        <span class="text-sm font-medium text-forest-900">{{ $faq['q'] }}</span>
                        <svg class="w-4 h-4 text-forest-900/40 flex-shrink-0 ml-4 transition-transform" :class="{ 'rotate-180': openFaq === {{ $i }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === {{ $i }}" x-collapse x-cloak class="px-6 pb-4">
                        <p class="text-sm text-forest-900/60 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-forest-900 py-16">
    <div class="max-w-4xl mx-auto text-center px-4">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to protect your domains?</h2>
        <p class="text-gray-300 mb-8">Start monitoring your DMARC compliance for free. No credit card required.</p>
        <a href="{{ route('register') }}" class="bg-lime-400 text-forest-900 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-lime-300 transition">Create Free Account</a>
    </div>
</section>

@push('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach([
            ['q' => 'What happens when I reach my report limit on the free plan?', 'a' => 'Reports over your monthly limit are still received and stored, but they will not be processed or displayed until you upgrade. No data is lost.'],
            ['q' => 'Can I change plans at any time?', 'a' => 'Yes. You can upgrade or downgrade at any time. When upgrading, the new plan takes effect immediately and you are charged a prorated amount. When downgrading, the change takes effect at the end of your current billing period.'],
            ['q' => 'Is there a free trial for paid plans?', 'a' => 'Yes. Both Pro and Enterprise plans include a 14-day free trial. No credit card is required to start the trial.'],
            ['q' => 'What payment methods do you accept?', 'a' => 'We accept all major credit and debit cards (Visa, Mastercard, American Express) through Stripe. All payments are processed securely.'],
            ['q' => 'Do you offer annual billing?', 'a' => 'Yes. Annual billing is available for both Pro and Enterprise plans with a discount equivalent to two months free.'],
            ['q' => 'What counts as a report?', 'a' => 'A report is a single DMARC aggregate report (RUA) received from a reporting provider such as Google, Microsoft, or Yahoo. Each XML file counts as one report, regardless of how many records it contains.'],
            ['q' => 'Can I cancel my subscription?', 'a' => 'Yes. You can cancel at any time from your billing page. Your plan will remain active until the end of the current billing period. After that, your account will revert to the free plan.']
        ] as $i => $faq)
        {
            "@type": "Question",
            "name": "{{ $faq['q'] }}",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{ $faq['a'] }}"
            }
        }{{ $i < 6 ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endpush

</x-marketing-layout>
