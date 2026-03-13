<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-forest-900">Alert Channels</h1>
            <button wire:click="openCreateModal" class="bg-lime-400 text-forest-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">Add Channel</button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Channels List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($channels as $channel)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm font-medium text-forest-900">{{ $channel->name }}</td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ ucfirst($channel->type->value) }}</span>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600 truncate max-w-[200px]">
                                @if($channel->type->value === 'email')
                                    {{ $channel->config['email'] ?? '—' }}
                                @elseif($channel->type->value === 'slack')
                                    {{ Str::limit($channel->config['webhook_url'] ?? '—', 40) }}
                                @elseif($channel->type->value === 'webhook')
                                    {{ Str::limit($channel->config['url'] ?? '—', 40) }}
                                @elseif($channel->type->value === 'pushover')
                                    Pushover ({{ Str::limit($channel->config['user_key'] ?? '', 8) }}...)
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $channel->is_verified ? 'bg-lime-100 text-lime-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $channel->is_verified ? 'Verified' : 'Unverified' }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <button wire:click="testChannel({{ $channel->id }})" class="text-xs text-lime-600 hover:text-lime-700 font-medium">Test</button>
                                    <button wire:click="openEditModal({{ $channel->id }})" class="text-xs text-gray-500 hover:text-gray-700 font-medium">Edit</button>
                                    <button wire:click="deleteChannel({{ $channel->id }})" wire:confirm="Are you sure you want to delete this channel?" class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                                No alert channels configured. <button wire:click="openCreateModal" class="text-lime-600 hover:text-lime-700 font-medium">Add your first channel</button>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(session()->has('success'))
            <div class="mt-4 bg-lime-50 border border-lime-200 rounded-lg p-3 text-sm text-lime-800">{{ session('success') }}</div>
        @endif
        @if(session()->has('error'))
            <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-800">{{ session('error') }}</div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="closeModal">
            <div class="bg-white rounded-xl p-6 shadow-xl max-w-lg w-full mx-4">
                <h2 class="text-lg font-semibold text-forest-900 mb-4">{{ $editingChannelId ? 'Edit' : 'Add' }} Alert Channel</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" wire:model="form.name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        @error('form.name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select wire:model.live="form.type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500" {{ $editingChannelId ? 'disabled' : '' }}>
                            <option value="email">Email</option>
                            <option value="slack">Slack</option>
                            <option value="webhook">Webhook</option>
                            <option value="pushover">Pushover</option>
                        </select>
                    </div>

                    @if($form['type'] === 'email')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                            <input type="email" wire:model="form.email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                            @error('form.email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @elseif($form['type'] === 'slack')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slack webhook URL</label>
                            <input type="url" wire:model="form.webhook_url" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                            @error('form.webhook_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @elseif($form['type'] === 'webhook')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Webhook URL</label>
                            <input type="url" wire:model="form.url" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                            @error('form.url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Secret (optional)</label>
                            <input type="text" wire:model="form.secret" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        </div>
                    @elseif($form['type'] === 'pushover')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User key</label>
                            <input type="text" wire:model="form.user_key" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                            @error('form.user_key') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">App token</label>
                            <input type="text" wire:model="form.app_token" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                            @error('form.app_token') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="closeModal" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                    <button wire:click="saveChannel" class="bg-lime-400 text-forest-900 px-6 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">
                        {{ $editingChannelId ? 'Update' : 'Create' }} Channel
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
