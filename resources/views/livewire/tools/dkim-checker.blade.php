<div>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-forest-900">DKIM Checker</h1>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-600 mb-6">Check DKIM records for your domain. Enter your domain and selector to validate the DKIM configuration.</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                    <input type="text" wire:model="domain" placeholder="example.com" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                    @error('domain') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selector</label>
                    <input type="text" wire:model="selector" placeholder="default, google, s1, k1..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                    <p class="text-xs text-gray-500 mt-1">Common selectors: default, google, s1, s2, k1, selector1, selector2, dkim, mail</p>
                    @error('selector') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button wire:click="check" class="bg-forest-900 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-forest-800 transition">
                        <span wire:loading.remove wire:target="check">Check DKIM</span>
                        <span wire:loading wire:target="check">Checking...</span>
                    </button>
                    <button wire:click="checkCommonSelectors" class="bg-gray-100 text-forest-900 px-6 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        <span wire:loading.remove wire:target="checkCommonSelectors">Try Common Selectors</span>
                        <span wire:loading wire:target="checkCommonSelectors">Scanning...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Single Check Result -->
        @if($result)
            <div class="mt-6 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-forest-900">DKIM Record: {{ $result['selector'] }}._domainkey.{{ $result['domain'] }}</h3>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $result['found'] ? ($result['is_valid'] ? 'bg-lime-200 text-lime-800' : 'bg-yellow-200 text-yellow-800') : 'bg-red-200 text-red-800' }}">
                        {{ $result['found'] ? ($result['is_valid'] ? 'Valid' : 'Issues Found') : 'Not Found' }}
                    </span>
                </div>

                @if($result['record'])
                    <code class="text-xs block bg-gray-50 rounded-lg p-4 border break-all mb-4">{{ $result['record'] }}</code>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-gray-500">Key Type</span>
                            <p class="text-sm font-medium text-forest-900">{{ $result['key_type'] ?? 'RSA' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Key Length</span>
                            <p class="text-sm font-medium {{ ($result['key_length'] ?? 0) >= 2048 ? 'text-lime-600' : 'text-yellow-600' }}">{{ $result['key_length'] ?? 'Unknown' }} bits</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Flags</span>
                            <p class="text-sm font-medium text-forest-900">{{ $result['flags'] ?? '—' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Hash Algorithm</span>
                            <p class="text-sm font-medium text-forest-900">{{ $result['hash_algorithm'] ?? '—' }}</p>
                        </div>
                    </div>
                @endif

                @if(!empty($result['issues']))
                    <div class="mt-4 space-y-2">
                        @foreach($result['issues'] as $issue)
                            <div class="flex items-start gap-2 text-sm text-red-600 bg-red-50 rounded-lg p-3">
                                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                <span>{{ $issue }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(!empty($result['warnings']))
                    <div class="mt-4 space-y-2">
                        @foreach($result['warnings'] as $warning)
                            <div class="flex items-start gap-2 text-sm text-yellow-700 bg-yellow-50 rounded-lg p-3">
                                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span>{{ $warning }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- Common Selectors Scan Results -->
        @if($commonSelectorResults)
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-forest-900">Common Selector Scan</h3>
                    <p class="text-xs text-gray-500 mt-1">Checked common DKIM selectors for {{ $domain }}</p>
                </div>
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selector</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Key Length</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Key Type</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($commonSelectorResults as $selectorResult)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 text-sm font-mono">{{ $selectorResult['selector'] }}</td>
                                <td class="px-5 py-3">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $selectorResult['found'] ? 'bg-lime-100 text-lime-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $selectorResult['found'] ? 'Found' : 'Not Found' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $selectorResult['found'] ? ($selectorResult['key_length'] ?? '—') . ' bits' : '—' }}</td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $selectorResult['found'] ? ($selectorResult['key_type'] ?? 'RSA') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
