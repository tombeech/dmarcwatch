<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-forest-900">Two-factor authentication</h2>
            <p class="text-sm text-gray-500 mt-1">Confirm access to your account.</p>
        </div>

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600" x-show="! recovery">
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            </div>

            <div class="mb-4 text-sm text-gray-600" x-cloak x-show="recovery">
                {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="mt-4" x-show="! recovery">
                    <x-label for="code" value="{{ __('Code') }}" class="text-sm font-medium text-gray-700" />
                    <x-input id="code" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
                </div>

                <div class="mt-4" x-cloak x-show="recovery">
                    <x-label for="recovery_code" value="{{ __('Recovery Code') }}" class="text-sm font-medium text-gray-700" />
                    <x-input id="recovery_code" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="button" class="text-sm text-lime-600 hover:text-lime-700 font-medium cursor-pointer"
                                    x-show="! recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                        {{ __('Use a recovery code') }}
                    </button>

                    <button type="button" class="text-sm text-lime-600 hover:text-lime-700 font-medium cursor-pointer"
                                    x-cloak
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                        {{ __('Use an authentication code') }}
                    </button>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-lime-400 text-forest-900 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
