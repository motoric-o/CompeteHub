<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Committee Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Welcome, :name!', ['name' => auth()->user()->name]) }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('You are logged in as Committee / Organizer.') }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                            <h4 class="font-semibold text-indigo-700">{{ __('Manage Competitions') }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Create and manage your competitions.') }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <h4 class="font-semibold text-green-700">{{ __('Registration Forms') }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Design dynamic registration forms.') }}</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <h4 class="font-semibold text-yellow-700">{{ __('Verify Registrations') }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Review and approve participant registrations.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
