<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight tracking-tight">
            {{ __('Participant Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-teal-400 to-emerald-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-teal-200">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ __('Welcome, :name!', ['name' => auth()->user()->name]) }}</h3>
                            <p class="text-gray-500">{{ __('You are logged in as Participant.') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="#" class="group cursor-pointer bg-gradient-to-br from-teal-50 to-white rounded-2xl p-6 border border-teal-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-100 block">
                            <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ __('Browse Competitions') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('Find exciting competitions to join and showcase your skills.') }}</p>
                        </a>

                        <a href="{{ route('participant.registrations.index') }}" class="group cursor-pointer bg-gradient-to-br from-orange-50 to-white rounded-2xl p-6 border border-orange-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-orange-100 block">
                            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ __('My Registrations') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('Track the status of your current competition registrations.') }}</p>
                        </a>

                        <a href="#" class="group cursor-pointer bg-gradient-to-br from-blue-50 to-white rounded-2xl p-6 border border-blue-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-100 block">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ __('Leaderboard') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('Check global rankings and track your competitive progress.') }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
