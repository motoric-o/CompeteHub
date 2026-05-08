<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Create an account</h2>
        <p class="text-sm text-gray-500 mt-2">Join CompeteHub and start your journey.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-gray-700 font-medium" />
            <x-text-input id="name" class="block mt-1 w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-colors" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-colors" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div>
            <x-input-label for="role" :value="__('Register As')" class="text-gray-700 font-medium" />
            <select id="role" name="role"
                class="block mt-1 w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-colors text-gray-700">
                <option value="participant" {{ old('role') === 'participant' ? 'selected' : '' }}>👤 Participant</option>
                <option value="committee" {{ old('role') === 'committee' ? 'selected' : '' }}>🏆 Committee / Organizer</option>
                <option value="judge" {{ old('role') === 'judge' ? 'selected' : '' }}>⚖️ Judge / Jury</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                <x-text-input id="password" class="block mt-1 w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                                type="password"
                                name="password"
                                required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-medium" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all active:scale-[0.98]">
                {{ __('Create Account') }}
            </button>
        </div>

        <p class="text-center text-sm text-gray-600 mt-4">
            Already registered? 
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">Sign in</a>
        </p>
    </form>
</x-guest-layout>
