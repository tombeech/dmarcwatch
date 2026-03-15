<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-forest-900">Create your account</h2>
            <p class="text-sm text-gray-500 mt-1">Get started with DMARCWatch in minutes</p>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" class="text-sm font-medium text-gray-700" />
                <x-input id="name" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" class="text-sm font-medium text-gray-700" />
                <x-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" class="text-sm font-medium text-gray-700" />
                <x-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-sm font-medium text-gray-700" />
                <x-input id="password_confirmation" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required class="rounded border-gray-300 text-lime-600 focus:ring-lime-500" />
                            <div class="ms-2 text-sm text-gray-600">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-lime-600 hover:text-lime-700 font-medium">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-lime-600 hover:text-lime-700 font-medium">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="mt-6">
                <button type="submit" class="w-full bg-lime-400 text-forest-900 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">
                    Create account
                </button>
            </div>

            <div class="mt-6 text-center">
                <span class="text-sm text-gray-500">Already have an account?</span>
                <a href="{{ route('login') }}" class="text-sm text-lime-600 hover:text-lime-700 font-medium ml-1">Sign in</a>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
