<div>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-forest-900">Alert Log</h1>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Filters -->
        <div class="flex flex-wrap gap-4 mb-6">
            <select wire:model.live="channelFilter" class="rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 text-sm">
                <option value="">All Channels</option>
                @foreach($availableChannels as $channel)
                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="statusFilter" class="rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 text-sm">
                <option value="">All Statuses</option>
                <option value="sent">Sent</option>
                <option value="failed">Failed</option>
                <option value="pending">Pending</option>
            </select>
            <select wire:model.live="dateRange" class="rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 text-sm">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 90 days</option>
                <option value="">All time</option>
            </select>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">Total Alerts</div>
                <div class="text-2xl font-bold text-forest-900 mt-1">{{ number_format($totalAlerts) }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">Delivered</div>
                <div class="text-2xl font-bold text-lime-600 mt-1">{{ number_format($deliveredAlerts) }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">Failed</div>
                <div class="text-2xl font-bold {{ $failedAlerts > 0 ? 'text-red-600' : 'text-forest-900' }} mt-1">{{ number_format($failedAlerts) }}</div>
            </div>
        </div>

        <!-- Alerts Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rule</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Channel</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sent At</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($alerts as $alert)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-forest-100 text-forest-700">{{ str_replace('_', ' ', ucfirst($alert->event_type)) }}</span>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $alert->alertRule?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $alert->alertChannel?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $alert->domain?->name ?? 'All' }}</td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $alert->status === 'sent' ? 'bg-lime-100 text-lime-700' : ($alert->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ ucfirst($alert->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-500">{{ $alert->sent_at?->format('M j, Y g:ia') ?? '—' }}</td>
                            <td class="px-5 py-3">
                                <button wire:click="showDetail({{ $alert->id }})" class="text-xs text-lime-600 hover:text-lime-700 font-medium">View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                                No alerts have been sent yet. Alerts will appear here when triggered by your rules.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-gray-100">{{ $alerts->links() }}</div>
        </div>
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedAlert)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="closeDetail">
            <div class="bg-white rounded-xl p-6 shadow-xl max-w-lg w-full mx-4">
                <h2 class="text-lg font-semibold text-forest-900 mb-4">Alert Detail</h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Event</span>
                        <span class="font-medium text-forest-900">{{ str_replace('_', ' ', ucfirst($selectedAlert->event_type)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Rule</span>
                        <span class="font-medium">{{ $selectedAlert->alertRule?->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Channel</span>
                        <span class="font-medium">{{ $selectedAlert->alertChannel?->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $selectedAlert->status === 'sent' ? 'bg-lime-100 text-lime-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($selectedAlert->status) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Sent</span>
                        <span>{{ $selectedAlert->sent_at?->format('M j, Y g:ia') ?? '—' }}</span>
                    </div>
                    @if($selectedAlert->error_message)
                        <div>
                            <span class="text-gray-500 block mb-1">Error</span>
                            <p class="text-red-600 bg-red-50 rounded-lg p-3 text-xs">{{ $selectedAlert->error_message }}</p>
                        </div>
                    @endif
                    @if($selectedAlert->payload)
                        <div>
                            <span class="text-gray-500 block mb-1">Payload</span>
                            <pre class="text-xs bg-gray-50 rounded-lg p-3 border overflow-x-auto">{{ json_encode($selectedAlert->payload, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-end">
                    <button wire:click="closeDetail" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Close</button>
                </div>
            </div>
        </div>
    @endif
</div>
