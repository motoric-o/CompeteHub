<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Participant Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Welcome, :name!', ['name' => auth()->user()->name]) }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('You are logged in as Participant.') }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-teal-50 rounded-lg p-4 border border-teal-200">
                            <h4 class="font-semibold text-teal-700">{{ __('Browse Competitions') }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Find and register for competitions.') }}</p>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                            <h4 class="font-semibold text-orange-700">{{ __('My Registrations') }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Track your registration status.') }}</p>
                        </div>
                        <div class="bg-pink-50 rounded-lg p-4 border border-pink-200">
                            <h4 class="font-semibold text-pink-700">{{ __('Leaderboard') }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ __('View competition rankings.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
