<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My Registrations') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @forelse($registrations as $reg)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6 flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $reg->competition->name }}</h3>
                            <p class="text-sm text-gray-500">Registered {{ $reg->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $reg->status === 'payment_ok' ? 'bg-green-100 text-green-700' :
                                   ($reg->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst(str_replace('_', ' ', $reg->status)) }}
                            </span>
                            <a href="{{ route('participant.registrations.show', [$reg->competition, $reg]) }}"
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-500 text-center">You have no registrations yet.</div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
