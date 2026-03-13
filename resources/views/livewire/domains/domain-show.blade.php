<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('domains.index') }}" class="text-gray-400 hover:text-gray-600" wire:navigate>&larr;</a>
            <h1 class="text-2xl font-bold text-forest-900">{{ $domain->name }}</h1>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $domain->is_active ? 'bg-lime-100 text-lime-700' : 'bg-gray-100 text-gray-500' }}">{{ $domain->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Tabs -->
        <div class="flex gap-1 mb-6 bg-gray-100 rounded-lg p-1 w-fit">
            @foreach(['overview' => 'Overview', 'reports' => 'Reports', 'sources' => 'Sources', 'settings' => 'Settings'] as $key => $label)
                <button wire:click="setTab('{{ $key }}')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $activeTab === $key ? 'bg-white text-forest-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">{{ $label }}</button>
            @endforeach
        </div>

        @if($activeTab === 'overview')
            <!-- DNS Status Cards -->
            <div class="grid md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-gray-500">DMARC</span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $domain->dmarc_record ? 'bg-lime-100 text-lime-700' : 'bg-red-100 text-red-700' }}">
                            {{ $domain->dmarc_record ? 'Found' : 'Missing' }}
                        </span>
                    </div>
                    @if($domain->dmarc_record)
                        <code class="text-xs break-all block">{{ Str::limit($domain->dmarc_record, 100) }}</code>
                        <p class="text-xs text-gray-500 mt-2">Policy: <strong>{{ $domain->dmarc_policy ?? 'unknown' }}</strong></p>
                    @else
                        <p class="text-xs text-gray-500">No DMARC record detected.</p>
                    @endif
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-gray-500">SPF</span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $domain->spf_status === 'valid' ? 'bg-lime-100 text-lime-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($domain->spf_status ?? 'unknown') }}
                        </span>
                    </div>
                    @if($domain->spf_record)
                        <code class="text-xs break-all block">{{ Str::limit($domain->spf_record, 100) }}</code>
                    @else
                        <p class="text-xs text-gray-500">No SPF record detected.</p>
                    @endif
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-gray-500">DKIM</span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $domain->dkim_status === 'valid' ? 'bg-lime-100 text-lime-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($domain->dkim_status ?? 'unknown') }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $domain->dkim_selectors ? count($domain->dkim_selectors) . ' selector(s)' : 'No selectors configured' }}</p>
                </div>
            </div>

            <!-- Compliance + Actions -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <h3 class="font-semibold text-forest-900 mb-4">Compliance Score</h3>
                    <div class="text-5xl font-bold {{ ($domain->compliance_score ?? 0) >= 80 ? 'text-lime-600' : (($domain->compliance_score ?? 0) >= 50 ? 'text-yellow-500' : 'text-red-500') }}">
                        {{ $domain->compliance_score !== null ? round($domain->compliance_score) . '%' : 'N/A' }}
                    </div>
                    <button wire:click="recalculateCompliance" class="mt-3 text-sm text-lime-600 hover:text-lime-700">Recalculate</button>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <h3 class="font-semibold text-forest-900 mb-4">Report Address (RUA)</h3>
                    <code class="text-sm bg-gray-50 rounded p-3 border block break-all">{{ $domain->rua_address }}</code>
                    <p class="text-xs text-gray-500 mt-2">Add this to your DMARC record: <code class="bg-gray-100 px-1 rounded">rua=mailto:{{ $domain->rua_address }}</code></p>
                </div>
            </div>

            <!-- Top Sources -->
            @if($topSources->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-forest-900">Top Sending Sources</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Source IP</th>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Known Sender</th>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Messages</th>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Last Seen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($topSources as $source)
                                <tr>
                                    <td class="px-5 py-2 text-sm font-mono">{{ $source->source_ip }}</td>
                                    <td class="px-5 py-2 text-sm">{{ $source->sendingSource?->name ?? '—' }}</td>
                                    <td class="px-5 py-2 text-sm">{{ number_format($source->total_messages) }}</td>
                                    <td class="px-5 py-2 text-xs text-gray-500">{{ \Carbon\Carbon::parse($source->last_seen)->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-4">
                <button wire:click="runDnsCheck" class="bg-forest-900 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-forest-800 transition">Run DNS Check Now</button>
            </div>
        @endif

        @if($activeTab === 'reports')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-forest-900">Reports</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentReports as $report)
                        <a href="{{ route('reports.show', $report) }}" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition" wire:navigate>
                            <div>
                                <span class="text-sm font-medium text-forest-900">{{ $report->reporter_org }}</span>
                                <span class="text-xs text-gray-500 ml-2">{{ $report->date_begin?->format('M j') }} - {{ $report->date_end?->format('M j, Y') }}</span>
                            </div>
                            <div class="text-sm">
                                <span class="font-medium">{{ number_format($report->total_messages) }}</span>
                                <span class="text-xs {{ $report->fail_count > 0 ? 'text-red-500' : 'text-lime-600' }} ml-1">
                                    {{ $report->total_messages > 0 ? round(($report->pass_count / $report->total_messages) * 100) : 0 }}% pass
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-8 text-center text-gray-500 text-sm">No reports yet for this domain.</div>
                    @endforelse
                </div>
            </div>
        @endif

        @if($activeTab === 'sources')
            @if($topSources->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source IP</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Known Sender</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Messages</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Seen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($topSources as $source)
                                <tr>
                                    <td class="px-5 py-3 text-sm font-mono">{{ $source->source_ip }}</td>
                                    <td class="px-5 py-3 text-sm">
                                        @if($source->sendingSource)
                                            <span class="text-lime-700 font-medium">{{ $source->sendingSource->name }}</span>
                                        @else
                                            <span class="text-gray-400">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-sm">{{ number_format($source->total_messages) }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-500">{{ \Carbon\Carbon::parse($source->last_seen)->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100 text-center text-gray-500 text-sm">No sending sources recorded yet.</div>
            @endif
        @endif

        @if($activeTab === 'settings')
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 max-w-lg">
                <h3 class="font-semibold text-forest-900 mb-4">Domain Settings</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Active</span>
                        <button wire:click="toggleActive" class="relative inline-flex h-6 w-11 items-center rounded-full transition {{ $domain->is_active ? 'bg-lime-400' : 'bg-gray-300' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $domain->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                    <div>
                        <span class="text-sm text-gray-700">DNS check interval</span>
                        <p class="text-xs text-gray-500 mt-1">Every {{ $domain->dns_check_interval_minutes }} minutes</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-700">Created</span>
                        <p class="text-xs text-gray-500 mt-1">{{ $domain->created_at->format('M j, Y') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
