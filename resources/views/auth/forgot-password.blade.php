<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-8">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">

            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">
                Forgot Your Password?
            </h2>

            <!-- Subtitle -->
            <p class="text-gray-600 text-sm text-center mb-6">
                Enter your email below and we’ll send you a link to reset your password.
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Input -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email Address')" class="text-gray-700" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button
                        type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-lg text-sm font-semibold transition">
                        Email Password Reset Link
                    </button>
                </div>

                <!-- Back to login -->
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline text-sm">
                        Back to Login
                    </a>
                </div>

            </form>
        </div>
    </div>

</x-guest-layout>
