<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-forest-900">Domains</h1>
            <a href="{{ route('domains.create') }}" class="bg-lime-400 text-forest-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition" wire:navigate>Add Domain</a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex gap-4 mb-6">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search domains..." class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 text-sm">
            <select wire:model.live="statusFilter" class="rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 text-sm">
                <option value="">All</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sort('name')" class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:text-gray-700">Domain</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Policy</th>
                        <th wire:click="sort('compliance_score')" class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:text-gray-700">Compliance</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reports</th>
                        <th wire:click="sort('last_dns_check_at')" class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:text-gray-700">Last Check</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($domains as $domain)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <a href="{{ route('domains.show', $domain) }}" class="text-sm font-medium text-forest-900 hover:text-lime-600" wire:navigate>{{ $domain->name }}</a>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $domain->dmarc_policy ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @if($domain->compliance_score !== null)
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $domain->compliance_score >= 80 ? 'bg-lime-100 text-lime-700' : ($domain->compliance_score >= 50 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ round($domain->compliance_score) }}%
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $domain->dmarc_reports_count }}</td>
                            <td class="px-5 py-3 text-xs text-gray-500">{{ $domain->last_dns_check_at?->diffForHumans() ?? 'Never' }}</td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $domain->is_active ? 'bg-lime-100 text-lime-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $domain->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                                No domains yet. <a href="{{ route('domains.create') }}" class="text-lime-600 hover:text-lime-700 font-medium" wire:navigate>Add your first domain</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-gray-100">{{ $domains->links() }}</div>
        </div>
    </div>
</div>
