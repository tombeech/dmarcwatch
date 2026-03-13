<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cream-100">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-xl border border-gray-200">
        {{ $slot }}
    </div>

    <p class="mt-6 text-xs text-gray-400">&copy; {{ date('Y') }} DMARCWatch. All rights reserved.</p>
</div>
