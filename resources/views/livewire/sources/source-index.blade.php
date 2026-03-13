<div>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-forest-900">Sending Sources</h1>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Filters -->
        <div class="flex flex-wrap gap-4 mb-6">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by IP or sender name..." class="flex-1 min-w-[200px] rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 text-sm">
            <select wire:model.live="domainFilter" class="rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 text-sm">
                <option value="">All Domains</option>
                @foreach($availableDomains as $domain)
                    <option value="{{ $domain->id }}">{{ $domain->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="typeFilter" class="rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 text-sm">
                <option value="">All Sources</option>
                <option value="authorized">Authorized</option>
                <option value="unknown">Unknown</option>
            </select>
        </div>

        <!-- Authorized Sources -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-forest-900 mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-lime-400 rounded-full"></span>
                Authorized Sources
            </h2>
            @if($authorizedSources->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source IP</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hostname</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Known Sender</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Messages</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">SPF</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">DKIM</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Seen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($authorizedSources as $source)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-3 text-sm font-mono">{{ $source->source_ip }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-600">{{ $source->hostname ?? '—' }}</td>
                                    <td class="px-5 py-3">
                                        <span class="text-sm font-medium text-lime-700">{{ $source->sendingSource?->name ?? '—' }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-600">{{ $source->domain?->name ?? '—' }}</td>
                                    <td class="px-5 py-3 text-sm font-medium">{{ number_format($source->total_messages) }}</td>
                                    <td class="px-5 py-3">
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $source->spf_pass_rate >= 80 ? 'bg-lime-100 text-lime-700' : ($source->spf_pass_rate >= 50 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ round($source->spf_pass_rate) }}%
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $source->dkim_pass_rate >= 80 ? 'bg-lime-100 text-lime-700' : ($source->dkim_pass_rate >= 50 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ round($source->dkim_pass_rate) }}%
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-gray-500">{{ $source->last_seen_at?->diffForHumans() ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center text-gray-500 text-sm">
                    No authorized sources found.
                </div>
            @endif
        </div>

        <!-- Unknown Sources -->
        <div>
            <h2 class="text-lg font-semibold text-forest-900 mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-yellow-400 rounded-full"></span>
                Unknown Sources
            </h2>
            @if($unknownSources->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source IP</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hostname</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Messages</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">SPF</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">DKIM</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Seen</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($unknownSources as $source)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-3 text-sm font-mono">{{ $source->source_ip }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-600">{{ $source->hostname ?? '—' }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-600">{{ $source->domain?->name ?? '—' }}</td>
                                    <td class="px-5 py-3 text-sm font-medium">{{ number_format($source->total_messages) }}</td>
                                    <td class="px-5 py-3">
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $source->spf_pass_rate >= 80 ? 'bg-lime-100 text-lime-700' : ($source->spf_pass_rate >= 50 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ round($source->spf_pass_rate) }}%
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $source->dkim_pass_rate >= 80 ? 'bg-lime-100 text-lime-700' : ($source->dkim_pass_rate >= 50 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ round($source->dkim_pass_rate) }}%
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-gray-500">{{ $source->last_seen_at?->diffForHumans() ?? '—' }}</td>
                                    <td class="px-5 py-3">
                                        <button wire:click="markAsAuthorized({{ $source->id }})" class="text-xs text-lime-600 hover:text-lime-700 font-medium">Authorize</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center text-gray-500 text-sm">
                    No unknown sources found. All sending sources have been identified.
                </div>
            @endif
        </div>
    </div>
</div>
