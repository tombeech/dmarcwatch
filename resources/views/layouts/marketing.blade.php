<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DMARCWatch — Email Authentication Monitoring')</title>
    <meta name="description" content="@yield('description', 'Monitor DMARC, SPF, and DKIM records. Receive aggregate reports, identify sending sources, and improve email deliverability.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }</style>
    @yield('head')
</head>
<body class="antialiased bg-cream-100 text-forest-900">
    <!-- Navigation -->
    <nav class="bg-forest-900 border-b border-forest-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white">DMARCWatch</span>
                    </a>
                    <div class="hidden md:flex ml-10 space-x-8">
                        <a href="/features" class="text-gray-300 hover:text-white text-sm font-medium transition">Features</a>
                        <a href="/pricing" class="text-gray-300 hover:text-white text-sm font-medium transition">Pricing</a>
                        <a href="/guides" class="text-gray-300 hover:text-white text-sm font-medium transition">Guides</a>
                        <a href="/about" class="text-gray-300 hover:text-white text-sm font-medium transition">About</a>
                        <a href="/contact" class="text-gray-300 hover:text-white text-sm font-medium transition">Contact</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white text-sm font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white text-sm font-medium">Log in</a>
                        <a href="{{ route('register') }}" class="bg-lime-400 text-forest-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-forest-900 text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white font-semibold mb-4">Product</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/features" class="hover:text-white transition">Features</a></li>
                        <li><a href="/pricing" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Get Started</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Tools</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/guides/getting-started" class="hover:text-white transition">Getting Started</a></li>
                        <li><a href="/guides/understanding-spf" class="hover:text-white transition">SPF Guide</a></li>
                        <li><a href="/guides/dkim-explained" class="hover:text-white transition">DKIM Guide</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Company</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/about" class="hover:text-white transition">About</a></li>
                        <li><a href="/contact" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Legal</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-forest-800 mt-8 pt-8 text-sm text-center">
                &copy; {{ date('Y') }} DMARCWatch. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>