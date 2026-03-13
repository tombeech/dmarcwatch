<div>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-forest-900">SPF Analyzer</h1>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-600 mb-6">Analyze and validate your domain's SPF record. Check for common issues and DNS lookup limits.</p>

            <div class="flex gap-3">
                <input type="text" wire:model="domain" placeholder="example.com" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                <button wire:click="analyze" class="bg-forest-900 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-forest-800 transition">
                    <span wire:loading.remove wire:target="analyze">Analyze</span>
                    <span wire:loading wire:target="analyze">Analyzing...</span>
                </button>
            </div>
            @error('domain') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
        </div>

        @if($result)
            <!-- SPF Record -->
            <div class="mt-6 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-forest-900">SPF Record</h3>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $result['found'] ? ($result['is_valid'] ? 'bg-lime-200 text-lime-800' : 'bg-yellow-200 text-yellow-800') : 'bg-red-200 text-red-800' }}">
                        {{ $result['found'] ? ($result['is_valid'] ? 'Valid' : 'Issues Found') : 'Not Found' }}
                    </span>
                </div>

                @if($result['record'])
                    <code class="text-sm block bg-gray-50 rounded-lg p-4 border break-all mb-4">{{ $result['record'] }}</code>
                @endif

                <!-- DNS Lookups -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">DNS Lookups</span>
                        <span class="text-sm font-bold {{ $result['lookup_count'] <= 10 ? 'text-lime-600' : 'text-red-600' }}">{{ $result['lookup_count'] }} / {{ $result['max_lookups'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full transition-all {{ $result['lookup_count'] <= 7 ? 'bg-lime-400' : ($result['lookup_count'] <= 10 ? 'bg-yellow-400' : 'bg-red-400') }}" style="width: {{ min(($result['lookup_count'] / $result['max_lookups']) * 100, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">SPF allows a maximum of {{ $result['max_lookups'] }} DNS lookups per evaluation.</p>
                </div>

                <!-- Issues -->
                @if(!empty($result['issues']))
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-red-700 mb-2">Issues</h4>
                        <div class="space-y-2">
                            @foreach($result['issues'] as $issue)
                                <div class="flex items-start gap-2 text-sm text-red-600 bg-red-50 rounded-lg p-3">
                                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                    <span>{{ $issue }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Warnings -->
                @if(!empty($result['warnings']))
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-yellow-700 mb-2">Warnings</h4>
                        <div class="space-y-2">
                            @foreach($result['warnings'] as $warning)
                                <div class="flex items-start gap-2 text-sm text-yellow-700 bg-yellow-50 rounded-lg p-3">
                                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span>{{ $warning }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Included Mechanisms -->
                @if(!empty($result['mechanisms']))
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Mechanisms</h4>
                        <div class="bg-gray-50 rounded-lg border overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qualifier</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($result['mechanisms'] as $mechanism)
                                        <tr>
                                            <td class="px-4 py-2 text-sm font-mono">{{ $mechanism['type'] }}</td>
                                            <td class="px-4 py-2 text-sm font-mono text-gray-600">{{ $mechanism['value'] ?? '—' }}</td>
                                            <td class="px-4 py-2">
                                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $mechanism['qualifier'] === 'pass' ? 'bg-lime-100 text-lime-700' : ($mechanism['qualifier'] === 'fail' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                                                    {{ $mechanism['qualifier'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
