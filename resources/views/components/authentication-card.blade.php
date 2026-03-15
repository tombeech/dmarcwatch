<div class="min-h-screen flex">
    {{-- Left: Brand panel --}}
    <div class="hidden lg:flex lg:w-1/2 bg-forest-900 flex-col justify-between p-12">
        <div>
            {{ $logo }}
        </div>

        <div>
            <h1 class="text-4xl font-bold text-white leading-tight mb-4">
                Email authentication<br>
                <span class="text-lime-400">under control.</span>
            </h1>
            <p class="text-white/60 text-lg max-w-md">
                Monitor DMARC, SPF, and DKIM records. Receive aggregate reports, identify unauthorised senders, and improve email deliverability.
            </p>

            <div class="mt-10 grid grid-cols-3 gap-6">
                <div>
                    <div class="text-2xl font-bold text-lime-400">3 protocols</div>
                    <div class="text-white/50 text-sm mt-1">DMARC, SPF, DKIM</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-lime-400">&lt;60s</div>
                    <div class="text-white/50 text-sm mt-1">Report processing</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-lime-400">5 channels</div>
                    <div class="text-white/50 text-sm mt-1">Alert options</div>
                </div>
            </div>
        </div>

        <div class="text-white/30 text-sm">
            &copy; {{ date('Y') }} DMARCWatch. All rights reserved.
        </div>
    </div>

    {{-- Right: Form panel --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 sm:px-12 bg-white">
        {{-- Mobile logo --}}
        <div class="lg:hidden mb-8">
            {{ $logo }}
        </div>

        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </div>
</div>
