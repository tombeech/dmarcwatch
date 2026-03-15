<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'DMARCWatch - Email Authentication Monitoring' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Monitor DMARC, SPF, and DKIM records. Receive aggregate reports, identify sending sources, and improve email deliverability.' }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? 'DMARCWatch - Email Authentication Monitoring' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Monitor DMARC, SPF, and DKIM records. Receive aggregate reports, identify sending sources, and improve email deliverability.' }}">
    <meta property="og:site_name" content="DMARCWatch">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'DMARCWatch - Email Authentication Monitoring' }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? 'Monitor DMARC, SPF, and DKIM records. Receive aggregate reports, identify sending sources, and improve email deliverability.' }}">

    <link rel="icon" href="/favicon.ico" sizes="any">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .nav-link { @@apply text-sm text-forest-900/70 hover:text-forest-900 transition-colors duration-200; }
    </style>

    {{-- Schema.org Organization --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "DMARCWatch",
        "url": "https://dmarcwatch.app",
        "description": "Email authentication monitoring for teams",
        "parentOrganization": {
            "@@type": "Organization",
            "name": "Permission Email Ltd",
            "url": "https://dmarcwatch.app"
        },
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "Unit 3, Millars Brook",
            "addressLocality": "Wokingham",
            "postalCode": "RG41 2AD",
            "addressCountry": "GB"
        },
        "contactPoint": {
            "@@type": "ContactPoint",
            "email": "hello@dmarcwatch.app",
            "contactType": "customer service"
        }
    }
    </script>

    @stack('schema')
</head>
<body class="antialiased bg-white text-forest-900">

    {{-- Navigation --}}
    <nav x-data="{ open: false, productsOpen: false, resourcesOpen: false }" class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-forest-900 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                    </div>
                    <span class="text-base font-bold text-forest-900 tracking-tight">DMARCWatch</span>
                </a>

                {{-- Desktop nav --}}
                <div class="hidden lg:flex items-center gap-1">
                    {{-- Products dropdown --}}
                    <div class="relative" @mouseenter="productsOpen = true" @mouseleave="productsOpen = false">
                        <button class="nav-link px-3 py-2 flex items-center gap-1">
                            Product
                            <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="productsOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak class="absolute top-full left-0 mt-0 w-[480px] bg-white rounded-xl shadow-lg border border-gray-100 p-5 grid grid-cols-2 gap-4">
                            <a href="/features" class="group p-3 rounded-lg hover:bg-cream-100 transition-colors">
                                <div class="flex items-center gap-3 mb-1.5">
                                    <div class="w-8 h-8 bg-forest-900 rounded-md flex items-center justify-center">
                                        <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-forest-900">DMARC Reports</span>
                                </div>
                                <p class="text-xs text-forest-900/60 leading-relaxed pl-11">Automated parsing, compliance scoring, and source identification.</p>
                            </a>
                            <a href="/features#dns" class="group p-3 rounded-lg hover:bg-cream-100 transition-colors">
                                <div class="flex items-center gap-3 mb-1.5">
                                    <div class="w-8 h-8 bg-forest-900 rounded-md flex items-center justify-center">
                                        <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-forest-900">DNS Verification</span>
                                </div>
                                <p class="text-xs text-forest-900/60 leading-relaxed pl-11">DMARC, SPF, and DKIM record analysis and monitoring.</p>
                            </a>
                            <a href="/features#alerts" class="group p-3 rounded-lg hover:bg-cream-100 transition-colors">
                                <div class="flex items-center gap-3 mb-1.5">
                                    <div class="w-8 h-8 bg-forest-900 rounded-md flex items-center justify-center">
                                        <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-forest-900">Alerting</span>
                                </div>
                                <p class="text-xs text-forest-900/60 leading-relaxed pl-11">Email, Slack, webhooks, and Pushover notifications.</p>
                            </a>
                            <a href="/features#tools" class="group p-3 rounded-lg hover:bg-cream-100 transition-colors">
                                <div class="flex items-center gap-3 mb-1.5">
                                    <div class="w-8 h-8 bg-forest-900 rounded-md flex items-center justify-center">
                                        <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-forest-900">DNS Tools</span>
                                </div>
                                <p class="text-xs text-forest-900/60 leading-relaxed pl-11">DMARC generator, SPF analyser, and DKIM checker.</p>
                            </a>
                        </div>
                    </div>

                    <a href="/pricing" class="nav-link px-3 py-2">Pricing</a>

                    {{-- Resources dropdown --}}
                    <div class="relative" @mouseenter="resourcesOpen = true" @mouseleave="resourcesOpen = false">
                        <button class="nav-link px-3 py-2 flex items-center gap-1">
                            Resources
                            <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="resourcesOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak class="absolute top-full left-0 mt-0 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                            <a href="/guides" class="block px-4 py-2 text-sm text-forest-900/70 hover:text-forest-900 hover:bg-cream-100 transition-colors">Guides</a>
                            <a href="/guides/getting-started" class="block px-4 py-2 text-sm text-forest-900/70 hover:text-forest-900 hover:bg-cream-100 transition-colors">Getting Started</a>
                            <a href="/about" class="block px-4 py-2 text-sm text-forest-900/70 hover:text-forest-900 hover:bg-cream-100 transition-colors">About</a>
                            <a href="/contact" class="block px-4 py-2 text-sm text-forest-900/70 hover:text-forest-900 hover:bg-cream-100 transition-colors">Contact</a>
                        </div>
                    </div>

                    <a href="/contact" class="nav-link px-3 py-2">Contact</a>
                </div>

                {{-- Right side --}}
                <div class="hidden lg:flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-forest-900/70 hover:text-forest-900 px-3 py-2">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-forest-900/70 hover:text-forest-900 px-3 py-2">Log in</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-forest-900 hover:bg-forest-800 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            Get started free
                        </a>
                    @endauth
                </div>

                {{-- Mobile menu button --}}
                <button @click="open = !open" class="lg:hidden p-2 rounded-lg text-forest-900/60 hover:bg-cream-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" x-collapse x-cloak class="lg:hidden border-t border-gray-100 bg-white">
            <div class="px-4 py-4 space-y-1">
                <a href="/features" class="block text-sm text-forest-900/70 py-2 px-2 rounded-lg hover:bg-cream-100">Features</a>
                <a href="/pricing" class="block text-sm text-forest-900/70 py-2 px-2 rounded-lg hover:bg-cream-100">Pricing</a>
                <a href="/guides" class="block text-sm text-forest-900/70 py-2 px-2 rounded-lg hover:bg-cream-100">Guides</a>
                <a href="/about" class="block text-sm text-forest-900/70 py-2 px-2 rounded-lg hover:bg-cream-100">About</a>
                <a href="/contact" class="block text-sm text-forest-900/70 py-2 px-2 rounded-lg hover:bg-cream-100">Contact</a>
                <div class="pt-3 border-t border-gray-100 mt-3 space-y-1">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-sm font-medium text-forest-900 py-2 px-2">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block text-sm text-forest-900/70 py-2 px-2">Log in</a>
                        <a href="{{ route('register') }}" class="block text-center text-sm font-medium py-2.5 bg-forest-900 text-white rounded-lg mt-2">Get started free</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-forest-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">
            <div class="grid grid-cols-2 md:grid-cols-6 gap-8 pb-12 border-b border-white/10">
                {{-- Brand --}}
                <div class="col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-7 h-7 bg-white/10 rounded-md flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                            </svg>
                        </div>
                        <span class="text-white font-bold tracking-tight">DMARCWatch</span>
                    </div>
                    <p class="text-sm text-white/40 leading-relaxed max-w-xs">Email authentication monitoring for teams that need to protect their domains.</p>
                    <p class="text-xs text-white/20 mt-4 leading-relaxed">
                        DMARCWatch is a trading name of Permission Email Ltd<br>
                        Unit 3, Millars Brook, Wokingham, RG41 2AD
                    </p>
                </div>

                {{-- Product --}}
                <div>
                    <h4 class="text-xs font-semibold text-white/40 uppercase tracking-wider mb-4">Product</h4>
                    <ul class="space-y-2.5">
                        <li><a href="/features" class="text-sm text-white/60 hover:text-white transition-colors">Features</a></li>
                        <li><a href="/pricing" class="text-sm text-white/60 hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="/features#alerts" class="text-sm text-white/60 hover:text-white transition-colors">Integrations</a></li>
                    </ul>
                </div>

                {{-- Use Cases --}}
                <div>
                    <h4 class="text-xs font-semibold text-white/40 uppercase tracking-wider mb-4">Guides</h4>
                    <ul class="space-y-2.5">
                        <li><a href="/guides/getting-started" class="text-sm text-white/60 hover:text-white transition-colors">Getting Started</a></li>
                        <li><a href="/guides/understanding-spf" class="text-sm text-white/60 hover:text-white transition-colors">SPF Guide</a></li>
                        <li><a href="/guides/dkim-explained" class="text-sm text-white/60 hover:text-white transition-colors">DKIM Guide</a></li>
                        <li><a href="/guides/dmarc-policy-guide" class="text-sm text-white/60 hover:text-white transition-colors">DMARC Policies</a></li>
                    </ul>
                </div>

                {{-- Resources --}}
                <div>
                    <h4 class="text-xs font-semibold text-white/40 uppercase tracking-wider mb-4">Resources</h4>
                    <ul class="space-y-2.5">
                        <li><a href="/guides" class="text-sm text-white/60 hover:text-white transition-colors">All Guides</a></li>
                        <li><a href="/about" class="text-sm text-white/60 hover:text-white transition-colors">About</a></li>
                        <li><a href="/contact" class="text-sm text-white/60 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h4 class="text-xs font-semibold text-white/40 uppercase tracking-wider mb-4">Company</h4>
                    <ul class="space-y-2.5">
                        <li><a href="/about" class="text-sm text-white/60 hover:text-white transition-colors">About</a></li>
                        <li><a href="/terms-of-service" class="text-sm text-white/60 hover:text-white transition-colors">Terms</a></li>
                        <li><a href="/privacy-policy" class="text-sm text-white/60 hover:text-white transition-colors">Privacy</a></li>
                    </ul>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8">
                <p class="text-sm text-white/30">&copy; {{ date('Y') }} DMARCWatch, a trading name of Permission Email Ltd (Company No. 13560555). All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="/terms-of-service" class="text-sm text-white/30 hover:text-white/60 transition-colors">Terms</a>
                    <a href="/privacy-policy" class="text-sm text-white/30 hover:text-white/60 transition-colors">Privacy</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
