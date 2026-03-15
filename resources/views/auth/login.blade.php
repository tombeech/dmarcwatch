<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-forest-900">Welcome back</h2>
            <p class="text-sm text-gray-500 mt-1">Sign in to your DMARCWatch account</p>
        </div>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" class="text-sm font-medium text-gray-700" />
                <x-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <div class="flex items-center justify-between">
                    <x-label for="password" value="{{ __('Password') }}" class="text-sm font-medium text-gray-700" />
                    @if (Route::has('password.request'))
                        <a class="text-xs text-lime-600 hover:text-lime-700 font-medium" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <x-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" class="rounded border-gray-300 text-lime-600 focus:ring-lime-500" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-lime-400 text-forest-900 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">
                    Sign in
                </button>
            </div>

            <div class="mt-6 text-center">
                <span class="text-sm text-gray-500">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-sm text-lime-600 hover:text-lime-700 font-medium ml-1">Create one</a>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
