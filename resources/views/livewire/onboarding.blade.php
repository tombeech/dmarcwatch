<div>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-2xl font-bold text-forest-900 mb-2">Welcome to DMARCWatch</h1>
        <p class="text-gray-600 mb-8">Let's get your DMARC monitoring set up in a few steps.</p>

        <!-- Progress -->
        <div class="flex items-center mb-10 space-x-2">
            @foreach(['Plan', 'Domain', 'Alert Channel', 'Alert Rule'] as $i => $label)
                <button wire:click="goToStep({{ $i + 1 }})" class="flex items-center text-sm {{ $step > $i + 1 ? 'text-lime-600' : ($step === $i + 1 ? 'text-forest-900 font-semibold' : 'text-gray-400') }}">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold mr-1.5 {{ $step > $i + 1 ? 'bg-lime-400 text-forest-900' : ($step === $i + 1 ? 'bg-forest-900 text-white' : 'bg-gray-200 text-gray-500') }}">{{ $i + 1 }}</span>
                    <span class="hidden sm:inline">{{ $label }}</span>
                </button>
                @if($i < 3)<div class="flex-1 h-px bg-gray-200"></div>@endif
            @endforeach
        </div>

        <!-- Step 1: Plan -->
        @if($step === 1)
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold text-forest-900 mb-4">Choose a plan</h2>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="border rounded-lg p-4">
                        <h3 class="font-semibold">Free</h3>
                        <p class="text-2xl font-bold mt-2">{{ $currency['symbol'] }}0<span class="text-sm text-gray-500">/mo</span></p>
                        <p class="text-xs text-gray-500 mt-2">3 domains, 100 reports/month</p>
                        <button wire:click="skipPlan" class="mt-4 w-full bg-gray-100 text-forest-900 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Continue Free</button>
                    </div>
                    <div class="border-2 border-lime-400 rounded-lg p-4">
                        <h3 class="font-semibold">Pro</h3>
                        <p class="text-2xl font-bold mt-2">{{ $currency['symbol'] }}{{ $currency['pro'] }}<span class="text-sm text-gray-500">/mo</span></p>
                        <p class="text-xs text-gray-500 mt-2">50 domains, unlimited reports</p>
                        <button wire:click="subscribe('pro')" class="mt-4 w-full bg-lime-400 text-forest-900 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">Subscribe</button>
                    </div>
                    <div class="border rounded-lg p-4">
                        <h3 class="font-semibold">Enterprise</h3>
                        <p class="text-2xl font-bold mt-2">{{ $currency['symbol'] }}{{ $currency['enterprise'] }}<span class="text-sm text-gray-500">/mo</span></p>
                        <p class="text-xs text-gray-500 mt-2">100+ domains, unlimited everything</p>
                        <button wire:click="subscribe('enterprise')" class="mt-4 w-full bg-gray-100 text-forest-900 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Subscribe</button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 2: Domain -->
        @if($step === 2)
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold text-forest-900 mb-4">Add your first domain</h2>
                <div class="flex gap-3">
                    <input type="text" wire:model="domainName" placeholder="example.com" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                    <button wire:click="checkDomain" class="bg-forest-900 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-forest-800 transition">Check DNS</button>
                </div>
                @if($lookupError)
                    <p class="text-red-500 text-sm mt-2">{{ $lookupError }}</p>
                @endif

                @if($dmarcCheck)
                    <div class="mt-6 space-y-4">
                        <div class="rounded-lg border p-4 {{ $dmarcCheck['is_valid'] ? 'border-lime-300 bg-lime-50' : 'border-yellow-300 bg-yellow-50' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-sm">DMARC Record</span>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $dmarcCheck['found'] ? ($dmarcCheck['is_valid'] ? 'bg-lime-200 text-lime-800' : 'bg-yellow-200 text-yellow-800') : 'bg-red-200 text-red-800' }}">
                                    {{ $dmarcCheck['found'] ? ($dmarcCheck['is_valid'] ? 'Valid' : 'Issues Found') : 'Not Found' }}
                                </span>
                            </div>
                            @if($dmarcCheck['record'])
                                <code class="text-xs block bg-white rounded p-2 border break-all">{{ $dmarcCheck['record'] }}</code>
                            @endif
                            @foreach($dmarcCheck['issues'] as $issue)
                                <p class="text-xs text-red-600 mt-1">{{ $issue }}</p>
                            @endforeach
                            @foreach($dmarcCheck['warnings'] ?? [] as $warning)
                                <p class="text-xs text-yellow-600 mt-1">{{ $warning }}</p>
                            @endforeach
                        </div>

                        @if($spfCheck)
                            <div class="rounded-lg border p-4 {{ $spfCheck['is_valid'] ? 'border-lime-300 bg-lime-50' : 'border-yellow-300 bg-yellow-50' }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-sm">SPF Record</span>
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $spfCheck['found'] ? ($spfCheck['is_valid'] ? 'bg-lime-200 text-lime-800' : 'bg-yellow-200 text-yellow-800') : 'bg-red-200 text-red-800' }}">
                                        {{ $spfCheck['found'] ? ($spfCheck['is_valid'] ? 'Valid' : 'Issues Found') : 'Not Found' }}
                                    </span>
                                </div>
                                @if($spfCheck['record'])
                                    <code class="text-xs block bg-white rounded p-2 border break-all">{{ $spfCheck['record'] }}</code>
                                    <p class="text-xs text-gray-500 mt-1">DNS lookups: {{ $spfCheck['lookup_count'] }}/{{ $spfCheck['max_lookups'] }}</p>
                                @endif
                            </div>
                        @endif

                        <div class="rounded-lg border border-blue-300 bg-blue-50 p-4">
                            <p class="text-sm font-semibold text-blue-900 mb-2">Your unique report address</p>
                            <code class="text-sm bg-white rounded p-2 border block">{{ $generatedRua }}</code>
                            <p class="text-xs text-blue-700 mt-2">Add this address to your DMARC record's rua tag to receive aggregate reports.</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <button wire:click="skipDomain" class="text-sm text-gray-500 hover:text-gray-700">Skip for now</button>
                        <button wire:click="saveDomain" class="bg-lime-400 text-forest-900 px-6 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">Save Domain &amp; Continue</button>
                    </div>
                @else
                    <div class="mt-4">
                        <button wire:click="skipDomain" class="text-sm text-gray-500 hover:text-gray-700">Skip for now</button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Step 3: Alert Channel -->
        @if($step === 3)
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold text-forest-900 mb-4">Set up an alert channel</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Channel name</label>
                        <input type="text" wire:model="channelName" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select wire:model.live="channelType" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                            <option value="email">Email</option>
                            <option value="slack">Slack</option>
                            <option value="webhook">Webhook</option>
                            <option value="pushover">Pushover</option>
                        </select>
                    </div>
                    @if($channelType === 'email')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                            <input type="email" wire:model="emailAddress" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        </div>
                    @elseif($channelType === 'slack')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slack webhook URL</label>
                            <input type="url" wire:model="slackWebhook" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        </div>
                    @elseif($channelType === 'webhook')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Webhook URL</label>
                            <input type="url" wire:model="webhookUrl" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Secret (optional)</label>
                            <input type="text" wire:model="webhookSecret" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        </div>
                    @elseif($channelType === 'pushover')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User key</label>
                            <input type="text" wire:model="pushoverUserKey" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">App token</label>
                            <input type="text" wire:model="pushoverAppToken" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        </div>
                    @endif
                </div>
                <div class="mt-6 flex justify-between">
                    <button wire:click="skipChannel" class="text-sm text-gray-500 hover:text-gray-700">Skip</button>
                    <button wire:click="saveChannel" class="bg-lime-400 text-forest-900 px-6 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">Save &amp; Continue</button>
                </div>
            </div>
        @endif

        <!-- Step 4: Alert Rule -->
        @if($step === 4)
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold text-forest-900 mb-4">Create an alert rule</h2>
                <p class="text-sm text-gray-600 mb-4">Choose which events should trigger alerts.</p>
                <div class="space-y-4">
                    @if($channels->isNotEmpty())
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alert channel</label>
                            <select wire:model="ruleChannelId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                                @foreach($channels as $channel)
                                    <option value="{{ $channel->id }}">{{ $channel->name }} ({{ $channel->type->value }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if($domains->isNotEmpty())
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Domain (optional)</label>
                            <select wire:model="ruleDomainId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                                <option value="">All domains</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}">{{ $domain->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="mt-6 flex justify-between">
                    <button wire:click="skipRule" class="text-sm text-gray-500 hover:text-gray-700">Skip</button>
                    <button wire:click="saveRule" class="bg-lime-400 text-forest-900 px-6 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">Finish Setup</button>
                </div>
            </div>
        @endif
    </div>
</div>
