<nav x-data="{ open: false }" class="bg-forest-900 border-b border-forest-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-white" wire:navigate>
                        <span class="text-lime-400">DMARC</span>Watch
                    </a>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden sm:flex sm:items-center sm:ml-10 space-x-6">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 hover:text-white' }} text-sm font-medium transition" wire:navigate>Dashboard</a>
                    <a href="{{ route('domains.index') }}" class="{{ request()->routeIs('domains.*') ? 'text-white' : 'text-gray-400 hover:text-white' }} text-sm font-medium transition" wire:navigate>Domains</a>
                    <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'text-white' : 'text-gray-400 hover:text-white' }} text-sm font-medium transition" wire:navigate>Reports</a>
                    <a href="{{ route('sources.index') }}" class="{{ request()->routeIs('sources.*') ? 'text-white' : 'text-gray-400 hover:text-white' }} text-sm font-medium transition" wire:navigate>Sources</a>

                    <!-- Tools dropdown -->
                    <div class="relative" x-data="{ toolsOpen: false }">
                        <button @click="toolsOpen = !toolsOpen" @click.outside="toolsOpen = false" class="{{ request()->routeIs('tools.*') ? 'text-white' : 'text-gray-400 hover:text-white' }} text-sm font-medium transition flex items-center gap-1">
                            Tools
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="toolsOpen" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                            <a href="{{ route('tools.dmarc-generator') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" wire:navigate>DMARC Generator</a>
                            <a href="{{ route('tools.spf-analyzer') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" wire:navigate>SPF Analyzer</a>
                            <a href="{{ route('tools.dkim-checker') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" wire:navigate>DKIM Checker</a>
                        </div>
                    </div>

                    <!-- Alerts dropdown -->
                    <div class="relative" x-data="{ alertsOpen: false }">
                        <button @click="alertsOpen = !alertsOpen" @click.outside="alertsOpen = false" class="{{ request()->routeIs('alerts.*') ? 'text-white' : 'text-gray-400 hover:text-white' }} text-sm font-medium transition flex items-center gap-1">
                            Alerts
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="alertsOpen" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                            <a href="{{ route('alerts.channels') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" wire:navigate>Channels</a>
                            <a href="{{ route('alerts.rules') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" wire:navigate>Rules</a>
                            <a href="{{ route('alerts.logs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" wire:navigate>Logs</a>
                        </div>
                    </div>

                    <a href="{{ route('billing') }}" class="{{ request()->routeIs('billing') ? 'text-white' : 'text-gray-400 hover:text-white' }} text-sm font-medium transition" wire:navigate>Billing</a>
                </div>
            </div>

            <!-- Right side: Teams & Profile -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="relative mr-3" x-data="{ teamsOpen: false }">
                        <button @click="teamsOpen = !teamsOpen" @click.outside="teamsOpen = false" class="text-gray-400 hover:text-white text-sm font-medium transition flex items-center gap-1">
                            {{ Auth::user()->currentTeam->name }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="teamsOpen" x-transition class="absolute right-0 mt-2 w-60 bg-white rounded-lg shadow-lg py-1 z-50">
                            <div class="px-4 py-2 text-xs text-gray-400 uppercase">Switch Teams</div>
                            @foreach (Auth::user()->allTeams() as $team)
                                <form method="POST" action="{{ route('current-team.update') }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                                        @if ($team->id === Auth::user()->currentTeam->id)
                                            <svg class="w-4 h-4 text-lime-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        @endif
                                        {{ $team->name }}
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false" class="text-gray-400 hover:text-white text-sm font-medium transition flex items-center gap-2">
                        <span class="w-8 h-8 bg-forest-700 rounded-full flex items-center justify-center text-white text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </button>
                    <div x-show="profileOpen" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="text-gray-400 hover:text-white">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden bg-forest-950">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}" class="block py-2 text-sm text-gray-300 hover:text-white" wire:navigate>Dashboard</a>
            <a href="{{ route('domains.index') }}" class="block py-2 text-sm text-gray-300 hover:text-white" wire:navigate>Domains</a>
            <a href="{{ route('reports.index') }}" class="block py-2 text-sm text-gray-300 hover:text-white" wire:navigate>Reports</a>
            <a href="{{ route('sources.index') }}" class="block py-2 text-sm text-gray-300 hover:text-white" wire:navigate>Sources</a>
            <a href="{{ route('tools.dmarc-generator') }}" class="block py-2 text-sm text-gray-300 hover:text-white" wire:navigate>DMARC Generator</a>
            <a href="{{ route('tools.spf-analyzer') }}" class="block py-2 text-sm text-gray-300 hover:text-white" wire:navigate>SPF Analyzer</a>
            <a href="{{ route('tools.dkim-checker') }}" class="block py-2 text-sm text-gray-300 hover:text-white" wire:navigate>DKIM Checker</a>
            <a href="{{ route('alerts.channels') }}" class="block py-2 text-sm text-gray-300 hover:text-white" wire:navigate>Alert Channels</a>
            <a href="{{ route('alerts.rules') }}" class="block py-2 text-sm text-gray-300 hover:text-white" wire:navigate>Alert Rules</a>
            <a href="{{ route('alerts.logs') }}" class="block py-2 text-sm text-gray-300 hover:text-white" wire:navigate>Alert Logs</a>
            <a href="{{ route('billing') }}" class="block py-2 text-sm text-gray-300 hover:text-white" wire:navigate>Billing</a>
        </div>
        <div class="pt-4 pb-1 border-t border-forest-800 px-4">
            <div class="text-sm text-white font-medium">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-400">{{ Auth::user()->email }}</div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.show') }}" class="block py-2 text-sm text-gray-300 hover:text-white">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block py-2 text-sm text-gray-300 hover:text-white">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</nav>
