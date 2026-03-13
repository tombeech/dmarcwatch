<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.index') }}" class="text-gray-400 hover:text-gray-600" wire:navigate>&larr;</a>
            <h1 class="text-2xl font-bold text-forest-900">Report: {{ $report->reporter_org }}</h1>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Report Metadata -->
        <div class="grid md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">Domain</div>
                <div class="text-sm font-semibold text-forest-900 mt-1">
                    @if($report->domain)
                        <a href="{{ route('domains.show', $report->domain) }}" class="hover:text-lime-600" wire:navigate>{{ $report->domain->name }}</a>
                    @else
                        —
                    @endif
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">Report Period</div>
                <div class="text-sm font-semibold text-forest-900 mt-1">{{ $report->date_begin?->format('M j') }} - {{ $report->date_end?->format('M j, Y') }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">Total Messages</div>
                <div class="text-2xl font-bold text-forest-900 mt-1">{{ number_format($report->total_messages) }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">Pass Rate</div>
                @php $passRate = $report->total_messages > 0 ? round(($report->pass_count / $report->total_messages) * 100) : 0; @endphp
                <div class="text-2xl font-bold mt-1 {{ $passRate >= 80 ? 'text-lime-600' : ($passRate >= 50 ? 'text-yellow-500' : 'text-red-500') }}">{{ $passRate }}%</div>
            </div>
        </div>

        <!-- Summary -->
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Passed</span>
                    <span class="text-lg font-bold text-lime-600">{{ number_format($report->pass_count) }}</span>
                </div>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-lime-400 h-2 rounded-full" style="width: {{ $report->total_messages > 0 ? ($report->pass_count / $report->total_messages) * 100 : 0 }}%"></div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Failed</span>
                    <span class="text-lg font-bold text-red-600">{{ number_format($report->fail_count) }}</span>
                </div>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-red-400 h-2 rounded-full" style="width: {{ $report->total_messages > 0 ? ($report->fail_count / $report->total_messages) * 100 : 0 }}%"></div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">Published Policy</div>
                <div class="mt-2 space-y-1">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Policy (p)</span>
                        <span class="font-medium text-forest-900">{{ $report->policy_published_p ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Sub-domain (sp)</span>
                        <span class="font-medium text-forest-900">{{ $report->policy_published_sp ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Alignment (adkim)</span>
                        <span class="font-medium text-forest-900">{{ $report->policy_published_adkim ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Alignment (aspf)</span>
                        <span class="font-medium text-forest-900">{{ $report->policy_published_aspf ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Percentage (pct)</span>
                        <span class="font-medium text-forest-900">{{ $report->policy_published_pct ?? '—' }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Info -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-6">
            <h3 class="font-semibold text-forest-900 mb-3">Report Details</h3>
            <div class="grid md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Report ID</span>
                    <p class="font-mono text-xs mt-0.5 break-all">{{ $report->external_id }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Reporter Email</span>
                    <p class="mt-0.5">{{ $report->reporter_email ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Received</span>
                    <p class="mt-0.5">{{ $report->received_at?->format('M j, Y g:ia') }}</p>
                </div>
            </div>
        </div>

        <!-- Records Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-forest-900">Records</h3>
                <span class="text-xs text-gray-500">{{ $records->total() }} record(s)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source IP</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disposition</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">DKIM Result</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">DKIM Domain</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">SPF Result</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">SPF Domain</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Header From</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($records as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 text-sm font-mono">{{ $record->source_ip }}</td>
                                <td class="px-5 py-3 text-sm font-medium">{{ number_format($record->count) }}</td>
                                <td class="px-5 py-3">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $record->disposition === 'none' ? 'bg-lime-100 text-lime-700' : ($record->disposition === 'quarantine' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $record->disposition ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $record->dkim_result === 'pass' ? 'bg-lime-100 text-lime-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $record->dkim_result ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-600">{{ $record->dkim_domain ?? '—' }}</td>
                                <td class="px-5 py-3">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $record->spf_result === 'pass' ? 'bg-lime-100 text-lime-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $record->spf_result ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-600">{{ $record->spf_domain ?? '—' }}</td>
                                <td class="px-5 py-3 text-xs text-gray-600">{{ $record->header_from ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-gray-500 text-sm">No records in this report.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-gray-100">{{ $records->links() }}</div>
        </div>
    </div>
</div>
