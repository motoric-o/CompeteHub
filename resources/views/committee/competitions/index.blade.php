<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight tracking-tight">
            {{ __('My Competitions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8">
                
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Manage Your Events</h3>
                        <p class="text-sm text-gray-500">Select a competition to manage its forms or verify registrations.</p>
                    </div>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl font-semibold transition-colors">
                        + New Competition
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($competitions as $competition)
                        <div class="border border-gray-200 rounded-2xl p-6 hover:border-indigo-300 hover:shadow-lg transition-all">
                            <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $competition->name }}</h4>
                            <div class="flex gap-4 text-sm text-gray-600 mb-4">
                                <span class="bg-gray-100 px-2 py-1 rounded">{{ ucfirst($competition->type) }}</span>
                                <span class="bg-gray-100 px-2 py-1 rounded">{{ ucfirst($competition->status) }}</span>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('committee.form-templates.index', $competition) }}" class="text-indigo-600 font-medium hover:underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Forms
                                </a>
                                <a href="{{ route('committee.registrations.index', $competition) }}" class="text-green-600 font-medium hover:underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Registrations
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                            <p class="text-gray-500">You haven't created any competitions yet.</p>
                            <p class="text-sm text-gray-400 mt-1">(Create Competition feature is pending F-01 implementation)</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
