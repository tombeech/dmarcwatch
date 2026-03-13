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
                <div class="text-sm text-gray-500">Total Domains</div>
                <div class="text-2xl font-bold text-forest-900 mt-1">{{ $totalDomains }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">Avg Compliance</div>
                <div class="text-2xl font-bold text-forest-900 mt-1">{{ $avgCompliance }}%</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">Emails Processed (30d)</div>
                <div class="text-2xl font-bold text-forest-900 mt-1">{{ number_format($emailsProcessed) }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">Failing Sources</div>
                <div class="text-2xl font-bold {{ $failingSources > 0 ? 'text-red-600' : 'text-forest-900' }} mt-1">{{ $failingSources }}</div>
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
