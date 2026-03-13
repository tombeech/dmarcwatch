<div>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-forest-900">Reports</h1>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Filters -->
        <div class="flex flex-wrap gap-4 mb-6">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by reporter..." class="flex-1 min-w-[200px] rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 text-sm">
            <select wire:model.live="domainFilter" class="rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 text-sm">
                <option value="">All Domains</option>
                @foreach($availableDomains as $domain)
                    <option value="{{ $domain->id }}">{{ $domain->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="dateRange" class="rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 text-sm">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 90 days</option>
                <option value="">All time</option>
            </select>
        </div>

        <!-- Reports Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sort('reporter_org')" class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:text-gray-700">Reporter</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                        <th wire:click="sort('date_begin')" class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:text-gray-700">Period</th>
                        <th wire:click="sort('total_messages')" class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:text-gray-700">Messages</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pass Rate</th>
                        <th wire:click="sort('received_at')" class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:text-gray-700">Received</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <a href="{{ route('reports.show', $report) }}" class="text-sm font-medium text-forest-900 hover:text-lime-600" wire:navigate>{{ $report->reporter_org }}</a>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $report->domain?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-xs text-gray-500">
                                {{ $report->date_begin?->format('M j') }} - {{ $report->date_end?->format('M j, Y') }}
                            </td>
                            <td class="px-5 py-3 text-sm font-medium text-forest-900">{{ number_format($report->total_messages) }}</td>
                            <td class="px-5 py-3">
                                @php $passRate = $report->total_messages > 0 ? round(($report->pass_count / $report->total_messages) * 100) : 0; @endphp
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $passRate >= 80 ? 'bg-lime-100 text-lime-700' : ($passRate >= 50 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $passRate }}%
                                </span>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-500">{{ $report->received_at?->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                                No reports found. Reports will appear here once your domains start receiving DMARC aggregate reports.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-gray-100">{{ $reports->links() }}</div>
        </div>
    </div>
</div>
