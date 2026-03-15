<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-forest-900">Reset password</h2>
            <p class="text-sm text-gray-500 mt-1">Enter your email and we'll send you a reset link.</p>
        </div>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" class="text-sm font-medium text-gray-700" />
                <x-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-lime-400 text-forest-900 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">
                    Send reset link
                </button>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-lime-600 hover:text-lime-700 font-medium">&larr; Back to sign in</a>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
