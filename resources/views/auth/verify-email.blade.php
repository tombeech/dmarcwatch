<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-forest-900">Verify your email</h2>
            <p class="text-sm text-gray-500 mt-1">Please verify your email address by clicking on the link we just emailed to you.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <button type="submit" class="w-full bg-lime-400 text-forest-900 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-lime-300 transition">
                        {{ __('Resend Verification Email') }}
                    </button>
                </div>
            </form>

            <div>
                <a
                    href="{{ route('profile.show') }}"
                    class="text-sm text-lime-600 hover:text-lime-700 font-medium"
                >
                    {{ __('Edit Profile') }}</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit" class="text-sm text-lime-600 hover:text-lime-700 font-medium ms-2">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
