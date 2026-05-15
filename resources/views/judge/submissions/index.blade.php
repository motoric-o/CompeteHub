<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-auth-session-status class="mb-4" :status="session('success')" />

            <h2 class="font-bold text-gray-800 text-xl mb-6 ml-2">My Assigned Competitions</h2>

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 p-8">
                @if($assignments->isEmpty())
                    <p class="text-gray-500">Belum ada kompetisi yang ditugaskan kepada Anda.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($assignments as $assignment)
                            <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow bg-white flex flex-col h-full">
                                <h4 class="font-bold text-lg text-gray-900 mb-3">{{ $assignment->competition->name }}</h4>
                                
                                <p class="text-sm text-gray-500 mb-6 flex-grow">
                                    {{ $assignment->competition->description ?? 'Deskripsi kompetisi belum tersedia.' }}
                                </p>

                                @if($assignment->competition->rounds->isNotEmpty())
                                    <div class="mt-auto">
                                        <a href="{{ route('judge.submissions.round', [$assignment->competition, $assignment->competition->rounds->first()]) }}" 
                                           class="inline-block px-5 py-2.5 bg-[#6366f1] text-white text-xs font-bold rounded hover:bg-[#4f46e5] transition-colors uppercase tracking-wide">
                                            VIEW SUBMISSIONS
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-auto">
                                        <span class="inline-block px-5 py-2.5 bg-gray-300 text-gray-600 text-xs font-bold rounded uppercase tracking-wide cursor-not-allowed">
                                            NO ROUNDS
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
