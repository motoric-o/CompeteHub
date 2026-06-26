<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight tracking-tight">
            {{ __('Global Leaderboards') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($competitions as $competition)
                    <a href="{{ route('leaderboard.index', $competition) }}" class="block bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="h-2 bg-indigo-500"></div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $competition->name }}</h3>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2">Click to view real-time rankings and scores for this competition.</p>
                            <div class="flex items-center text-indigo-600 text-sm font-semibold">
                                View Leaderboard
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                        <p class="text-gray-500">No competitions found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
