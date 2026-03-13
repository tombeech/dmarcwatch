<div>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-forest-900">DMARC Record Generator</h1>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-600 mb-6">Generate a DMARC TXT record for your domain. Configure the options below and copy the generated record.</p>

            <div class="space-y-5">
                <!-- Domain -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                    <input type="text" wire:model.live="domain" placeholder="example.com" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                </div>

                <!-- Policy -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Policy (p)</label>
                    <select wire:model.live="policy" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        <option value="none">None - Monitor only (recommended to start)</option>
                        <option value="quarantine">Quarantine - Mark as spam</option>
                        <option value="reject">Reject - Block delivery</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Start with "none" to collect data, then gradually move to "quarantine" and "reject".</p>
                </div>

                <!-- Sub-domain Policy -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub-domain Policy (sp)</label>
                    <select wire:model.live="subdomainPolicy" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        <option value="">Same as domain policy</option>
                        <option value="none">None</option>
                        <option value="quarantine">Quarantine</option>
                        <option value="reject">Reject</option>
                    </select>
                </div>

                <!-- RUA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aggregate Report Address (rua)</label>
                    <input type="email" wire:model.live="rua" placeholder="mailto:dmarc@example.com" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                    <p class="text-xs text-gray-500 mt-1">Where aggregate (XML) reports should be sent.</p>
                </div>

                <!-- RUF -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Forensic Report Address (ruf) <span class="text-gray-400">- optional</span></label>
                    <input type="email" wire:model.live="ruf" placeholder="mailto:dmarc-forensic@example.com" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                </div>

                <!-- DKIM Alignment -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">DKIM Alignment (adkim)</label>
                    <select wire:model.live="adkim" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        <option value="r">Relaxed (default)</option>
                        <option value="s">Strict</option>
                    </select>
                </div>

                <!-- SPF Alignment -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SPF Alignment (aspf)</label>
                    <select wire:model.live="aspf" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        <option value="r">Relaxed (default)</option>
                        <option value="s">Strict</option>
                    </select>
                </div>

                <!-- Percentage -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Percentage (pct)</label>
                    <input type="number" wire:model.live="pct" min="1" max="100" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                    <p class="text-xs text-gray-500 mt-1">Percentage of messages the policy applies to. Use less than 100% for gradual rollout.</p>
                </div>

                <!-- Reporting Interval -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reporting Interval (ri)</label>
                    <select wire:model.live="ri" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        <option value="86400">Daily (default)</option>
                        <option value="3600">Hourly</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Generated Record -->
        @if($generatedRecord)
            <div class="mt-6 bg-forest-900 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-white">Generated DMARC Record</h3>
                    <button wire:click="copyRecord" class="text-xs bg-lime-400 text-forest-900 px-3 py-1 rounded-md font-semibold hover:bg-lime-300 transition">Copy</button>
                </div>
                <div class="mb-3">
                    <span class="text-xs text-gray-400">DNS TXT record name:</span>
                    <code class="text-sm text-lime-400 block mt-1">_dmarc.{{ $domain }}</code>
                </div>
                <div>
                    <span class="text-xs text-gray-400">Record value:</span>
                    <code class="text-sm text-white block mt-1 break-all bg-forest-800 rounded p-3">{{ $generatedRecord }}</code>
                </div>
            </div>
        @endif

        @if(session()->has('copied'))
            <div class="mt-3 text-sm text-lime-600 font-medium">Record copied to clipboard!</div>
        @endif
    </div>
</div>
