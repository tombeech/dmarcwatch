<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-forest-900">Alert Rules</h1>
            <button wire:click="openCreateModal" class="bg-lime-400 text-forest-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">Add Rule</button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if($channels->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center mb-6">
                <p class="text-sm text-yellow-800">You need to <a href="{{ route('alerts.channels') }}" class="font-semibold underline hover:text-yellow-900" wire:navigate>create an alert channel</a> before setting up rules.</p>
            </div>
        @endif

        <!-- Rules List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Channel</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Condition</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rules as $rule)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm font-medium text-forest-900">{{ $rule->name }}</td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-forest-100 text-forest-700">{{ str_replace('_', ' ', ucfirst($rule->event_type)) }}</span>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $rule->alertChannel?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $rule->domain?->name ?? 'All domains' }}</td>
                            <td class="px-5 py-3 text-xs text-gray-500">
                                @if($rule->threshold_type && $rule->threshold_value)
                                    {{ ucfirst(str_replace('_', ' ', $rule->threshold_type)) }}: {{ $rule->threshold_value }}{{ $rule->threshold_type === 'compliance_below' ? '%' : '' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <button wire:click="toggleRule({{ $rule->id }})" class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $rule->is_active ? 'bg-lime-400' : 'bg-gray-300' }}">
                                    <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition {{ $rule->is_active ? 'translate-x-4.5' : 'translate-x-0.5' }}"></span>
                                </button>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <button wire:click="openEditModal({{ $rule->id }})" class="text-xs text-gray-500 hover:text-gray-700 font-medium">Edit</button>
                                    <button wire:click="deleteRule({{ $rule->id }})" wire:confirm="Are you sure you want to delete this rule?" class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                                No alert rules configured. <button wire:click="openCreateModal" class="text-lime-600 hover:text-lime-700 font-medium">Create your first rule</button>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(session()->has('success'))
            <div class="mt-4 bg-lime-50 border border-lime-200 rounded-lg p-3 text-sm text-lime-800">{{ session('success') }}</div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="closeModal">
            <div class="bg-white rounded-xl p-6 shadow-xl max-w-lg w-full mx-4">
                <h2 class="text-lg font-semibold text-forest-900 mb-4">{{ $editingRuleId ? 'Edit' : 'Add' }} Alert Rule</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rule name</label>
                        <input type="text" wire:model="form.name" placeholder="e.g. Low compliance alert" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        @error('form.name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event type</label>
                        <select wire:model.live="form.event_type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                            <option value="compliance_drop">Compliance drops below threshold</option>
                            <option value="new_source">New sending source detected</option>
                            <option value="dns_change">DNS record change</option>
                            <option value="report_received">Report received</option>
                            <option value="policy_failure">Policy failure detected</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alert channel</label>
                        <select wire:model="form.channel_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                            <option value="">Select a channel</option>
                            @foreach($channels as $channel)
                                <option value="{{ $channel->id }}">{{ $channel->name }} ({{ ucfirst($channel->type->value) }})</option>
                            @endforeach
                        </select>
                        @error('form.channel_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Domain (optional)</label>
                        <select wire:model="form.domain_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                            <option value="">All domains</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}">{{ $domain->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(in_array($form['event_type'] ?? '', ['compliance_drop']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Threshold (%)</label>
                            <input type="number" wire:model="form.threshold_value" min="0" max="100" placeholder="e.g. 80" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                            <p class="text-xs text-gray-500 mt-1">Alert when compliance drops below this percentage.</p>
                            @error('form.threshold_value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cooldown (minutes)</label>
                        <input type="number" wire:model="form.cooldown_minutes" min="0" placeholder="60" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        <p class="text-xs text-gray-500 mt-1">Minimum time between alerts for the same rule.</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="closeModal" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                    <button wire:click="saveRule" class="bg-lime-400 text-forest-900 px-6 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">
                        {{ $editingRuleId ? 'Update' : 'Create' }} Rule
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
