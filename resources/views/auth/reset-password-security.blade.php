<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Security Check</h2>
    </div>

    <form method="POST" action="{{ route('password.security.update') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}">

        <!-- Security Question Display -->
        <div class="mb-4">
            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                Question: <span class="text-indigo-600 font-bold">
                    {{ 
                        match($question) {
                            'maiden_name' => "What is your mother's maiden name?",
                            'pet_name' => "What was the name of your first pet?",
                            'city_birth' => "What city were you born in?",
                             default => $question
                        }
                    }}
                </span>
            </label>
        </div>

        <!-- Answer -->
        <div class="mt-4">
            <x-input-label for="security_answer" :value="__('Your Answer')" />
            <x-text-input id="security_answer" class="block mt-1 w-full" type="text" name="security_answer" required autofocus />
            <x-input-error :messages="$errors->get('security_answer')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
