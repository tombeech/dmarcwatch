<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('domains.index') }}" class="text-gray-400 hover:text-gray-600" wire:navigate>&larr;</a>
            <h1 class="text-2xl font-bold text-forest-900">Add Domain</h1>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if($domainSaved)
            <div class="bg-lime-50 border border-lime-200 rounded-xl p-6 text-center">
                <h2 class="text-lg font-semibold text-lime-800 mb-2">Domain added successfully!</h2>
                <p class="text-sm text-lime-700 mb-4">A DNS check is running in the background.</p>
                <a href="{{ route('domains.index') }}" class="bg-lime-400 text-forest-900 px-6 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition" wire:navigate>View Domains</a>
            </div>
        @else
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex gap-3 mb-4">
                    <input type="text" wire:model="domainName" placeholder="example.com" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                    <button wire:click="checkDomain" class="bg-forest-900 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-forest-800 transition">Check DNS</button>
                </div>
                @if($error)<p class="text-red-500 text-sm mb-4">{{ $error }}</p>@endif

                @if($dmarcCheck)
                    <div class="space-y-4">
                        <div class="rounded-lg border p-4 {{ $dmarcCheck['is_valid'] ? 'border-lime-300 bg-lime-50' : 'border-yellow-300 bg-yellow-50' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-sm">DMARC</span>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $dmarcCheck['found'] ? ($dmarcCheck['is_valid'] ? 'bg-lime-200 text-lime-800' : 'bg-yellow-200 text-yellow-800') : 'bg-red-200 text-red-800' }}">
                                    {{ $dmarcCheck['found'] ? ($dmarcCheck['is_valid'] ? 'Valid' : 'Issues') : 'Not Found' }}
                                </span>
                            </div>
                            @if($dmarcCheck['record'])<code class="text-xs block bg-white rounded p-2 border break-all">{{ $dmarcCheck['record'] }}</code>@endif
                        </div>

                        @if($spfCheck)
                            <div class="rounded-lg border p-4 {{ $spfCheck['is_valid'] ? 'border-lime-300 bg-lime-50' : 'border-yellow-300 bg-yellow-50' }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-sm">SPF</span>
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $spfCheck['found'] ? ($spfCheck['is_valid'] ? 'bg-lime-200 text-lime-800' : 'bg-yellow-200 text-yellow-800') : 'bg-red-200 text-red-800' }}">
                                        {{ $spfCheck['found'] ? ($spfCheck['is_valid'] ? 'Valid' : 'Issues') : 'Not Found' }}
                                    </span>
                                </div>
                                @if($spfCheck['record'])<code class="text-xs block bg-white rounded p-2 border break-all">{{ $spfCheck['record'] }}</code>@endif
                            </div>
                        @endif

                        <div class="rounded-lg border border-blue-300 bg-blue-50 p-4">
                            <p class="text-sm font-semibold text-blue-900 mb-2">Your report address</p>
                            <code class="text-sm bg-white rounded p-2 border block">{{ $generatedRua }}</code>
                        </div>

                        @if($suggestedDmarcRecord)
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <p class="text-sm font-semibold text-gray-900 mb-2">Suggested DMARC record</p>
                                <code class="text-xs block bg-white rounded p-2 border break-all">{{ $suggestedDmarcRecord }}</code>
                                <p class="text-xs text-gray-500 mt-2">Publish this as a TXT record at <code>_dmarc.{{ $domainName }}</code></p>
                            </div>
                        @endif

                        <button wire:click="saveDomain" class="w-full bg-lime-400 text-forest-900 py-3 rounded-lg font-semibold hover:bg-lime-300 transition">Add Domain</button>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
