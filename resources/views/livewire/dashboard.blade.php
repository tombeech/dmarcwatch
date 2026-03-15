<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-forest-900">Dashboard</h1>
            <a href="{{ route('domains.create') }}" class="bg-lime-400 text-forest-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition" wire:navigate>Add Domain</a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-forest-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Domains</div>
                </div>
                <div class="text-3xl font-extrabold text-forest-900">{{ $totalDomains }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-forest-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Compliance</div>
                </div>
                <div class="text-3xl font-extrabold text-forest-900">{{ $avgCompliance }}%</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-forest-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Emails Processed (30d)</div>
                </div>
                <div class="text-3xl font-extrabold text-forest-900">{{ number_format($emailsProcessed) }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-forest-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Failing Sources</div>
                </div>
                <div class="text-3xl font-extrabold {{ $failingSources > 0 ? 'text-red-600' : 'text-forest-900' }}">{{ $failingSources }}</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Recent Reports -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-forest-900">Recent Reports</h2>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentReports as $report)
                        <a href="{{ route('reports.show', $report) }}" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition" wire:navigate>
                            <div>
                                <div class="text-sm font-medium text-forest-900">{{ $report->reporter_org }}</div>
                                <div class="text-xs text-gray-500">{{ $report->domain?->name }} &middot; {{ $report->received_at?->diffForHumans() }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium">{{ number_format($report->total_messages) }} msgs</div>
                                <div class="text-xs {{ $report->fail_count > 0 ? 'text-red-500' : 'text-lime-600' }}">
                                    {{ $report->total_messages > 0 ? round(($report->pass_count / $report->total_messages) * 100) : 0 }}% pass
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-8 text-center text-gray-500 text-sm">
                            No reports yet. Reports will appear here once your domains start receiving DMARC aggregate reports.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Domains -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-forest-900">Your Domains</h2>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($domains as $domain)
                        <a href="{{ route('domains.show', $domain) }}" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition" wire:navigate>
                            <div class="text-sm font-medium text-forest-900">{{ $domain->name }}</div>
                            <div class="flex items-center gap-2">
                                @if($domain->compliance_score !== null)
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $domain->compliance_score >= 80 ? 'bg-lime-100 text-lime-700' : ($domain->compliance_score >= 50 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ round($domain->compliance_score) }}%
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Pending</span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-8 text-center text-gray-500 text-sm">
                            <a href="{{ route('domains.create') }}" class="text-lime-600 hover:text-lime-700 font-medium" wire:navigate>Add your first domain</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
