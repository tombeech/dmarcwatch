<nav x-data="{ open: false }" class="bg-forest-900 sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">

            <!-- Left: Logo + Nav Links -->
            <div class="flex items-center">

                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 me-8 shrink-0">
                    <div class="w-7 h-7 bg-white/10 rounded-md flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-white tracking-tight hidden sm:inline">DMARCWatch</span>
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:gap-0.5">

                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150
                              {{ request()->routeIs('dashboard')
                                  ? 'bg-white/15 text-white'
                                  : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        {{ __('Dashboard') }}
                    </a>

                    <!-- Domains -->
                    <a href="{{ route('domains.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150
                              {{ request()->routeIs('domains.*')
                                  ? 'bg-white/15 text-white'
                                  : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        {{ __('Domains') }}
                    </a>

                    <!-- Reports -->
                    <a href="{{ route('reports.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150
                              {{ request()->routeIs('reports.*')
                                  ? 'bg-white/15 text-white'
                                  : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('Reports') }}
                    </a>

                    <!-- Sources -->
                    <a href="{{ route('sources.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150
                              {{ request()->routeIs('sources.*')
                                  ? 'bg-white/15 text-white'
                                  : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                        </svg>
                        {{ __('Sources') }}
                    </a>

                    <!-- Tools Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150
                                       {{ request()->routeIs('tools.*')
                                           ? 'bg-white/15 text-white'
                                           : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ __('Tools') }}
                            <svg class="w-3 h-3 opacity-50 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 mt-1 w-48 rounded-lg shadow-lg bg-forest-800 ring-1 ring-white/10 py-1 z-50"
                             style="top: 100%;">
                            <a href="{{ route('tools.dmarc-generator') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('tools.dmarc-generator') ? 'text-lime-400 bg-white/5' : 'text-white/70 hover:text-white hover:bg-white/5' }} transition-colors">
                                <svg class="w-4 h-4 opacity-60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                </svg>
                                {{ __('DMARC Generator') }}
                            </a>
                            <a href="{{ route('tools.spf-analyzer') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('tools.spf-analyzer') ? 'text-lime-400 bg-white/5' : 'text-white/70 hover:text-white hover:bg-white/5' }} transition-colors">
                                <svg class="w-4 h-4 opacity-60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                {{ __('SPF Analyzer') }}
                            </a>
                            <a href="{{ route('tools.dkim-checker') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('tools.dkim-checker') ? 'text-lime-400 bg-white/5' : 'text-white/70 hover:text-white hover:bg-white/5' }} transition-colors">
                                <svg class="w-4 h-4 opacity-60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                {{ __('DKIM Checker') }}
                            </a>
                        </div>
                    </div>

                    <!-- Alerts Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150
                                       {{ request()->routeIs('alerts.*')
                                           ? 'bg-white/15 text-white'
                                           : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            {{ __('Alerts') }}
                            <svg class="w-3 h-3 opacity-50 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 mt-1 w-48 rounded-lg shadow-lg bg-forest-800 ring-1 ring-white/10 py-1 z-50"
                             style="top: 100%;">
                            <a href="{{ route('alerts.channels') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('alerts.channels') ? 'text-lime-400 bg-white/5' : 'text-white/70 hover:text-white hover:bg-white/5' }} transition-colors">
                                <svg class="w-4 h-4 opacity-60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                {{ __('Channels') }}
                            </a>
                            <a href="{{ route('alerts.rules') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('alerts.rules') ? 'text-lime-400 bg-white/5' : 'text-white/70 hover:text-white hover:bg-white/5' }} transition-colors">
                                <svg class="w-4 h-4 opacity-60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                                {{ __('Rules') }}
                            </a>
                            <a href="{{ route('alerts.logs') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('alerts.logs') ? 'text-lime-400 bg-white/5' : 'text-white/70 hover:text-white hover:bg-white/5' }} transition-colors">
                                <svg class="w-4 h-4 opacity-60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ __('Logs') }}
                            </a>
                        </div>
                    </div>

                    <!-- Billing -->
                    <a href="{{ route('billing') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150
                              {{ request()->routeIs('billing')
                                  ? 'bg-white/15 text-white'
                                  : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        {{ __('Billing') }}
                    </a>

                </div>
            </div>

            <!-- Right: Teams + User -->
            <div class="hidden sm:flex sm:items-center sm:gap-2">

                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <button type="button"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium text-white/60 hover:text-white hover:bg-white/10 focus:outline-none transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="max-w-[120px] truncate">{{ Auth::user()->currentTeam->name }}</span>
                                    <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <div class="block px-4 py-2 text-xs font-semibold text-forest-900/40 uppercase tracking-wider">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-100 my-1"></div>

                                        <div class="block px-4 py-2 text-xs font-semibold text-forest-900/40 uppercase tracking-wider">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Divider -->
                <div class="h-5 w-px bg-white/10"></div>

                <!-- Settings / User Dropdown -->
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex items-center gap-2 rounded-full focus:outline-none transition">
                                    <img class="w-7 h-7 rounded-full object-cover ring-2 ring-white/20"
                                         src="{{ Auth::user()->profile_photo_url }}"
                                         alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <button type="button"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium text-white/60 hover:text-white hover:bg-white/10 focus:outline-none transition-colors duration-150">
                                    <div class="w-6 h-6 rounded-full bg-lime-400/20 flex items-center justify-center text-lime-400 text-xs font-bold shrink-0">
                                        {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                    <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs font-semibold text-forest-900/40 uppercase tracking-wider">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

            </div>

            <!-- Mobile Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-md text-white/60 hover:text-white hover:bg-white/10 focus:outline-none transition-colors duration-150">
                    <svg class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}"
         class="hidden sm:hidden border-t border-white/10">

        <!-- Primary Links -->
        <div class="px-3 pt-3 pb-2 space-y-0.5">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                {{ __('Dashboard') }}
            </a>
            <a href="{{ route('domains.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('domains.*') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                {{ __('Domains') }}
            </a>
            <a href="{{ route('reports.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('reports.*') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('Reports') }}
            </a>
            <a href="{{ route('sources.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('sources.*') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
                {{ __('Sources') }}
            </a>

            <!-- Tools sub-group -->
            <div class="px-3 pt-2 pb-1">
                <p class="text-xs font-semibold text-white/30 uppercase tracking-wider mb-1">{{ __('Tools') }}</p>
            </div>
            <a href="{{ route('tools.dmarc-generator') }}" class="flex items-center gap-2.5 px-3 py-2 pl-6 rounded-md text-sm font-medium {{ request()->routeIs('tools.dmarc-generator') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
                {{ __('DMARC Generator') }}
            </a>
            <a href="{{ route('tools.spf-analyzer') }}" class="flex items-center gap-2.5 px-3 py-2 pl-6 rounded-md text-sm font-medium {{ request()->routeIs('tools.spf-analyzer') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                {{ __('SPF Analyzer') }}
            </a>
            <a href="{{ route('tools.dkim-checker') }}" class="flex items-center gap-2.5 px-3 py-2 pl-6 rounded-md text-sm font-medium {{ request()->routeIs('tools.dkim-checker') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                {{ __('DKIM Checker') }}
            </a>

            <!-- Alerts sub-group -->
            <div class="px-3 pt-2 pb-1">
                <p class="text-xs font-semibold text-white/30 uppercase tracking-wider mb-1">{{ __('Alerts') }}</p>
            </div>
            <a href="{{ route('alerts.channels') }}" class="flex items-center gap-2.5 px-3 py-2 pl-6 rounded-md text-sm font-medium {{ request()->routeIs('alerts.channels') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                {{ __('Channels') }}
            </a>
            <a href="{{ route('alerts.rules') }}" class="flex items-center gap-2.5 px-3 py-2 pl-6 rounded-md text-sm font-medium {{ request()->routeIs('alerts.rules') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                {{ __('Rules') }}
            </a>
            <a href="{{ route('alerts.logs') }}" class="flex items-center gap-2.5 px-3 py-2 pl-6 rounded-md text-sm font-medium {{ request()->routeIs('alerts.logs') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('Logs') }}
            </a>

            <a href="{{ route('billing') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('billing') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                {{ __('Billing') }}
            </a>
        </div>

        <!-- Responsive User / Settings -->
        <div class="pt-3 pb-3 border-t border-white/10">
            <div class="flex items-center gap-3 px-4 py-2">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <img class="w-8 h-8 rounded-full object-cover ring-2 ring-white/20 shrink-0"
                         src="{{ Auth::user()->profile_photo_url }}"
                         alt="{{ Auth::user()->name }}" />
                @else
                    <div class="w-8 h-8 rounded-full bg-lime-400/20 flex items-center justify-center text-lime-400 text-sm font-bold shrink-0">
                        {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="min-w-0">
                    <div class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-white/40 truncate">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-2 px-3 space-y-0.5">
                <a href="{{ route('profile.show') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('profile.show') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                    {{ __('Profile') }}
                </a>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <a href="{{ route('api-tokens.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('api-tokens.index') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                        {{ __('API Tokens') }}
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <a href="{{ route('logout') }}" @click.prevent="$root.submit();" class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-white/60">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>

            <!-- Team Management -->
            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                <div class="mt-3 pt-3 border-t border-white/10 px-3">
                    <div class="px-3 pb-1">
                        <p class="text-xs font-semibold text-white/30 uppercase tracking-wider">{{ __('Manage Team') }}</p>
                    </div>

                    <div class="space-y-0.5">
                        <a href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('teams.show') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                            {{ __('Team Settings') }}
                        </a>

                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                            <a href="{{ route('teams.create') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('teams.create') ? 'bg-white/15 text-white' : 'text-white/60' }}">
                                {{ __('Create New Team') }}
                            </a>
                        @endcan
                    </div>

                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="mt-3 pt-3 border-t border-white/10">
                            <div class="px-3 pb-1">
                                <p class="text-xs font-semibold text-white/30 uppercase tracking-wider">{{ __('Switch Teams') }}</p>
                            </div>

                            <div class="space-y-0.5">
                                @foreach (Auth::user()->allTeams() as $team)
                                    <x-switchable-team :team="$team" component="responsive-nav-link" />
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</nav>
