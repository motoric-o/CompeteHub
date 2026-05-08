<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight tracking-tight">
            {{ __('Committee Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-indigo-200">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ __('Welcome back, :name!', ['name' => auth()->user()->name]) }}</h3>
                            <p class="text-gray-500">{{ __('You are logged in as Committee / Organizer.') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Card 1 -->
                        <a href="{{ route('committee.competitions.index') }}" class="group cursor-pointer bg-gradient-to-br from-indigo-50 to-white rounded-2xl p-6 border border-indigo-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-100 block">
                            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ __('Manage Competitions') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('Create, edit, and manage all aspects of your competitions.') }}</p>
                        </a>

                        <!-- Card 2 -->
                        <a href="{{ route('committee.competitions.index') }}" class="group cursor-pointer bg-gradient-to-br from-green-50 to-white rounded-2xl p-6 border border-green-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-green-100 block">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ __('Registration Forms') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('Design dynamic registration forms with our builder.') }}</p>
                        </a>

                        <!-- Card 3 -->
                        <a href="{{ route('committee.competitions.index') }}" class="group cursor-pointer bg-gradient-to-br from-yellow-50 to-white rounded-2xl p-6 border border-yellow-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-yellow-100 block">
                            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ __('Verify Registrations') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('Review documents, verify payments, and approve participants.') }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
