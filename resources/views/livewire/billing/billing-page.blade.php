<div>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-forest-900">Billing & Plan</h1>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-lime-50 border border-lime-200 text-lime-800 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
        @endif

        {{-- Current Plan --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-forest-900">Current Plan</h2>
                <span class="text-xs font-medium px-3 py-1 rounded-full {{ $currentPlan === 'free' ? 'bg-gray-100 text-gray-700' : ($currentPlan === 'pro' ? 'bg-lime-100 text-lime-700' : 'bg-forest-100 text-forest-700') }}">
                    {{ ucfirst($currentPlan) }}
                </span>
            </div>

            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Domains</span>
                    <p class="text-sm font-medium text-forest-900 mt-0.5">{{ $usage['domains'] }} / {{ $limits['domains'] === -1 ? 'Unlimited' : $limits['domains'] }}</p>
                    @if($limits['domains'] !== -1)
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-lime-400 h-1.5 rounded-full" style="width: {{ min(($usage['domains'] / max($limits['domains'], 1)) * 100, 100) }}%"></div>
                        </div>
                    @endif
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Reports (this month)</span>
                    <p class="text-sm font-medium text-forest-900 mt-0.5">{{ number_format($usage['reports']) }} / {{ $limits['reports'] === -1 ? 'Unlimited' : number_format($limits['reports']) }}</p>
                    @if($limits['reports'] !== -1)
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="h-1.5 rounded-full {{ ($usage['reports'] / max($limits['reports'], 1)) > 0.9 ? 'bg-red-400' : 'bg-lime-400' }}" style="width: {{ min(($usage['reports'] / max($limits['reports'], 1)) * 100, 100) }}%"></div>
                        </div>
                    @endif
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Alert Channels</span>
                    <p class="text-sm font-medium text-forest-900 mt-0.5">{{ $usage['channels'] }} / {{ $limits['channels'] === -1 ? 'Unlimited' : $limits['channels'] }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Retention</span>
                    <p class="text-sm font-medium text-forest-900 mt-0.5">{{ $limits['retention_days'] === 'Unlimited' ? 'Unlimited' : $limits['retention_days'] . ' days' }}</p>
                </div>
            </div>

            @if($currentPlan !== 'free' && $subscription)
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        @if($subscription->onGracePeriod())
                            Cancels on: <span class="font-medium text-red-600">{{ $subscription->ends_at?->format('M j, Y') }}</span>
                        @else
                            Next billing date: <span class="font-medium text-forest-900">{{ $subscription->current_period_end?->format('M j, Y') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        @if($subscription->onGracePeriod())
                            <button wire:click="resumeSubscription" class="text-sm text-lime-600 hover:text-lime-700 font-medium">Resume Subscription</button>
                        @else
                            <button wire:click="manageBilling" class="text-sm text-lime-600 hover:text-lime-700 font-medium">Manage Billing</button>
                            <button wire:click="cancelSubscription" wire:confirm="Are you sure you want to cancel your subscription? You will retain access until the end of your billing period." class="text-sm text-red-500 hover:text-red-600 font-medium">Cancel</button>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Plan Options --}}
        <h2 class="text-lg font-semibold text-forest-900 mb-4">{{ $currentPlan === 'free' ? 'Upgrade Your Plan' : 'Change Plan' }}</h2>
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            {{-- Free --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border {{ $currentPlan === 'free' ? 'border-lime-400 border-2' : 'border-gray-100' }}">
                @if($currentPlan === 'free')
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-lime-100 text-lime-700 mb-3 inline-block">Current Plan</span>
                @endif
                <h3 class="text-lg font-bold text-forest-900">Free</h3>
                <p class="text-3xl font-bold mt-2">{{ $currency['symbol'] }}0<span class="text-sm text-gray-500 font-normal">/mo</span></p>
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    @foreach(['3 domains', '100 reports/month', '1 alert channel', '30 day retention'] as $feature)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-lime-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
                @if($currentPlan !== 'free')
                    <button wire:click="changePlan('free')" wire:confirm="Are you sure you want to downgrade to the free plan? Your current plan will remain active until the end of the billing period." class="mt-6 w-full bg-gray-100 text-forest-900 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Downgrade</button>
                @endif
            </div>

            {{-- Pro --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border {{ $currentPlan === 'pro' ? 'border-lime-400 border-2' : 'border-gray-100' }}">
                @if($currentPlan === 'pro')
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-lime-100 text-lime-700 mb-3 inline-block">Current Plan</span>
                @endif
                <h3 class="text-lg font-bold text-forest-900">Pro</h3>
                <p class="text-3xl font-bold mt-2">{{ $currency['symbol'] }}{{ $currency['pro'] }}<span class="text-sm text-gray-500 font-normal">/mo</span></p>
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    @foreach(['50 domains', 'Unlimited reports', '5 alert channels', '365 day retention', 'API access', 'Weekly digests'] as $feature)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-lime-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
                @if($currentPlan !== 'pro')
                    <button wire:click="changePlan('pro')" class="mt-6 w-full bg-lime-400 text-forest-900 py-2.5 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">
                        {{ $currentPlan === 'enterprise' ? 'Downgrade to Pro' : 'Upgrade to Pro' }}
                    </button>
                @endif
            </div>

            {{-- Enterprise --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border {{ $currentPlan === 'enterprise' ? 'border-lime-400 border-2' : 'border-gray-100' }}">
                @if($currentPlan === 'enterprise')
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-lime-100 text-lime-700 mb-3 inline-block">Current Plan</span>
                @endif
                <h3 class="text-lg font-bold text-forest-900">Enterprise</h3>
                <p class="text-3xl font-bold mt-2">{{ $currency['symbol'] }}{{ $currency['enterprise'] }}<span class="text-sm text-gray-500 font-normal">/mo</span></p>
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    @foreach(['100+ domains', 'Unlimited everything', 'Unlimited alert channels', 'Unlimited retention', '15-min DNS checks', 'Priority support'] as $feature)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-lime-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
                @if($currentPlan !== 'enterprise')
                    <button wire:click="changePlan('enterprise')" class="mt-6 w-full bg-forest-900 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-forest-800 transition">Upgrade to Enterprise</button>
                @endif
            </div>
        </div>

        {{-- Invoices --}}
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
                                        <span class="text-xs text-gray-400">&mdash;</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Upgrade Confirmation Modal --}}
    @if($showUpgradeModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="cancelUpgrade">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
                <h3 class="text-lg font-semibold text-forest-900 mb-2">Confirm Plan Change</h3>
                <p class="text-sm text-gray-600 mb-4">
                    You are switching to the <strong class="text-forest-900">{{ ucfirst($pendingPlan) }}</strong> plan at
                    <strong class="text-forest-900">{{ $currency['symbol'] }}{{ $pendingAmount }}/month</strong>.
                    The change will take effect immediately and you will be charged a prorated amount.
                </p>
                <div class="flex items-center gap-3 justify-end">
                    <button wire:click="cancelUpgrade" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 font-medium">Cancel</button>
                    <button wire:click="confirmUpgrade" class="px-5 py-2.5 bg-forest-900 hover:bg-forest-800 text-white text-sm font-medium rounded-lg transition-colors">Confirm Change</button>
                </div>
            </div>
        </div>
    @endif
</div>
