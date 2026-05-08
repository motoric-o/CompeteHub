<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight tracking-tight">
            {{ __('Browse Competitions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($competitions as $competition)
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                        <div class="h-32 bg-gradient-to-r from-teal-400 to-emerald-500 relative">
                            <div class="absolute bottom-4 left-4 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-white text-xs font-semibold uppercase tracking-wider">
                                {{ $competition->type }}
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $competition->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $competition->description }}</p>
                            
                            <div class="mt-auto space-y-2 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Registration Fee:</span>
                                    <span class="font-bold text-gray-900">{{ $competition->registration_fee > 0 ? 'Rp ' . number_format($competition->registration_fee, 0, ',', '.') : 'Free' }}</span>
                                </div>
                                @if($competition->registration_end)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Deadline:</span>
                                        <span class="font-medium text-red-600">{{ $competition->registration_end->format('d M Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('participant.registrations.create', $competition) }}" class="block w-full text-center bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 rounded-xl transition-colors">
                                Register Now
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white/90 backdrop-blur-sm rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                        <div class="w-20 h-20 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Competitions Open</h3>
                        <p class="text-gray-500 max-w-md mx-auto">There are currently no active competitions available for registration. Please check back later!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
