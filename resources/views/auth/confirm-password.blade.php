<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-forest-900">Confirm password</h2>
            <p class="text-sm text-gray-500 mt-1">This is a secure area. Please confirm your password before continuing.</p>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-label for="password" value="{{ __('Password') }}" class="text-sm font-medium text-gray-700" />
                <x-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-lime-400 text-forest-900 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">
                    Confirm
                </button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
