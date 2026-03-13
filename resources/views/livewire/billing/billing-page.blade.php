<div>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-forest-900">Billing & Plan</h1>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Current Plan -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-forest-900">Current Plan</h2>
                <span class="text-xs font-medium px-3 py-1 rounded-full {{ $currentPlan === 'free' ? 'bg-gray-100 text-gray-700' : ($currentPlan === 'pro' ? 'bg-lime-100 text-lime-700' : 'bg-forest-100 text-forest-700') }}">
                    {{ ucfirst($currentPlan) }}
                </span>
            </div>

            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <span class="text-xs text-gray-500">Domains</span>
                    <p class="text-sm font-medium text-forest-900 mt-0.5">{{ $usage['domains'] }} / {{ $limits['domains'] === -1 ? 'Unlimited' : $limits['domains'] }}</p>
                    @if($limits['domains'] !== -1)
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-lime-400 h-1.5 rounded-full" style="width: {{ min(($usage['domains'] / $limits['domains']) * 100, 100) }}%"></div>
                        </div>
                    @endif
                </div>
                <div>
                    <span class="text-xs text-gray-500">Reports (this month)</span>
                    <p class="text-sm font-medium text-forest-900 mt-0.5">{{ number_format($usage['reports']) }} / {{ $limits['reports'] === -1 ? 'Unlimited' : number_format($limits['reports']) }}</p>
                    @if($limits['reports'] !== -1)
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="h-1.5 rounded-full {{ ($usage['reports'] / $limits['reports']) > 0.9 ? 'bg-red-400' : 'bg-lime-400' }}" style="width: {{ min(($usage['reports'] / $limits['reports']) * 100, 100) }}%"></div>
                        </div>
                    @endif
                </div>
                <div>
                    <span class="text-xs text-gray-500">Alert Channels</span>
                    <p class="text-sm font-medium text-forest-900 mt-0.5">{{ $usage['channels'] }} / {{ $limits['channels'] === -1 ? 'Unlimited' : $limits['channels'] }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500">Retention</span>
                    <p class="text-sm font-medium text-forest-900 mt-0.5">{{ $limits['retention_days'] }} days</p>
                </div>
            </div>

            @if($currentPlan !== 'free' && $subscription)
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Next billing date: <span class="font-medium text-forest-900">{{ $subscription->current_period_end?->format('M j, Y') }}</span>
                    </div>
                    <button wire:click="openBillingPortal" class="text-sm text-lime-600 hover:text-lime-700 font-medium">Manage Subscription</button>
                </div>
            @endif
        </div>

        <!-- Plan Options -->
        <h2 class="text-lg font-semibold text-forest-900 mb-4">{{ $currentPlan === 'free' ? 'Upgrade Your Plan' : 'Change Plan' }}</h2>
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <!-- Free -->
            <div class="bg-white rounded-xl p-6 shadow-sm border {{ $currentPlan === 'free' ? 'border-lime-400 border-2' : 'border-gray-100' }}">
                @if($currentPlan === 'free')
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-lime-100 text-lime-700 mb-3 inline-block">Current Plan</span>
                @endif
                <h3 class="text-lg font-bold text-forest-900">Free</h3>
                <p class="text-3xl font-bold mt-2">{{ $currency['symbol'] }}0<span class="text-sm text-gray-500 font-normal">/mo</span></p>
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        3 domains
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        100 reports/month
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        1 alert channel
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        30 day retention
                    </li>
                </ul>
                @if($currentPlan !== 'free')
                    <button wire:click="changePlan('free')" wire:confirm="Are you sure you want to downgrade to the free plan?" class="mt-6 w-full bg-gray-100 text-forest-900 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Downgrade</button>
                @endif
            </div>

            <!-- Pro -->
            <div class="bg-white rounded-xl p-6 shadow-sm border {{ $currentPlan === 'pro' ? 'border-lime-400 border-2' : 'border-gray-100' }}">
                @if($currentPlan === 'pro')
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-lime-100 text-lime-700 mb-3 inline-block">Current Plan</span>
                @endif
                <h3 class="text-lg font-bold text-forest-900">Pro</h3>
                <p class="text-3xl font-bold mt-2">{{ $currency['symbol'] }}{{ $currency['pro'] }}<span class="text-sm text-gray-500 font-normal">/mo</span></p>
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        50 domains
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Unlimited reports
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        10 alert channels
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        365 day retention
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        DNS tools
                    </li>
                </ul>
                @if($currentPlan !== 'pro')
                    <button wire:click="changePlan('pro')" class="mt-6 w-full bg-lime-400 text-forest-900 py-2.5 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">
                        {{ $currentPlan === 'enterprise' ? 'Downgrade to Pro' : 'Upgrade to Pro' }}
                    </button>
                @endif
            </div>

            <!-- Enterprise -->
            <div class="bg-white rounded-xl p-6 shadow-sm border {{ $currentPlan === 'enterprise' ? 'border-lime-400 border-2' : 'border-gray-100' }}">
                @if($currentPlan === 'enterprise')
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-lime-100 text-lime-700 mb-3 inline-block">Current Plan</span>
                @endif
                <h3 class="text-lg font-bold text-forest-900">Enterprise</h3>
                <p class="text-3xl font-bold mt-2">{{ $currency['symbol'] }}{{ $currency['enterprise'] }}<span class="text-sm text-gray-500 font-normal">/mo</span></p>
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Unlimited domains
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Unlimited everything
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Unlimited alert channels
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Unlimited retention
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Priority support
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        API access
                    </li>
                </ul>
                @if($currentPlan !== 'enterprise')
                    <button wire:click="changePlan('enterprise')" class="mt-6 w-full bg-forest-900 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-forest-800 transition">Upgrade to Enterprise</button>
                @endif
            </div>
        </div>

        <!-- Invoices -->
        @if($invoices->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-forest-900">Invoice History</h2>
                </div>
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $invoice->created_at->format('M j, Y') }}</td>
                                <td class="px-5 py-3 text-sm text-forest-900">{{ $invoice->description }}</td>
                                <td class="px-5 py-3 text-sm font-medium text-forest-900">{{ $currency['symbol'] }}{{ number_format($invoice->amount / 100, 2) }}</td>
                                <td class="px-5 py-3">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $invoice->status === 'paid' ? 'bg-lime-100 text-lime-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    @if($invoice->invoice_url)
                                        <a href="{{ $invoice->invoice_url }}" target="_blank" class="text-xs text-lime-600 hover:text-lime-700 font-medium">Download</a>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
