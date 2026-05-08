<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Judge Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Welcome, :name!', ['name' => auth()->user()->name]) }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('You are logged in as Judge / Jury.') }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                            <h4 class="font-semibold text-purple-700">{{ __('My Assignments') }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ __('View competitions assigned to you.') }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <h4 class="font-semibold text-blue-700">{{ __('Score Submissions') }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Review and score participant submissions.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
