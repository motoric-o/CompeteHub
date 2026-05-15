<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submissions for: ') }} {{ $competition->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-status class="mb-4 text-red-600 bg-red-100 p-4 rounded" :status="session('error')" />

            <div class="mb-4 flex justify-end">
                <a href="{{ route('leaderboard.index', $competition) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Lihat Leaderboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Round Kompetisi</h3>
                    
                    @if($rounds->isEmpty())
                        <p class="text-gray-500">Belum ada round untuk kompetisi ini.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($rounds as $round)
                                @php
                                    $submission = $submissions->get($round->id);
                                    $isOpen = true;
                                    if ($round->start_date && $round->start_date > now()) $isOpen = false;
                                    if ($round->end_date && $round->end_date < now()) $isOpen = false;
                                    $canRevise = $submission ? ($submission->revision_count < $maxRevisions) : true;
                                @endphp
                                <div class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900">{{ $round->name }} (Round {{ $round->round_order }})</h4>
                                            <div class="text-sm text-gray-500 mt-1">
                                                @if($round->start_date)
                                                    Start: <span class="font-medium text-gray-700">{{ $round->start_date->format('d M Y H:i') }}</span> <br>
                                                @endif
                                                @if($round->end_date)
                                                    Deadline: <span class="font-medium {{ $round->end_date < now() ? 'text-red-600' : 'text-gray-700' }}">{{ $round->end_date->format('d M Y H:i') }}</span>
                                                @endif
                                            </div>
                                            @if($submission)
                                                <div class="mt-4 space-y-3">
                                                    <div class="flex items-center gap-3 text-sm border-t border-gray-100 pt-3">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wide border
                                                            {{ $submission->status === 'scored' ? 'bg-green-50 border-green-200 text-green-700' : 
                                                               ($submission->status === 'under_review' ? 'bg-yellow-50 border-yellow-200 text-yellow-700' : 'bg-blue-50 border-blue-200 text-blue-700') }}">
                                                            {{ ucfirst($submission->status) }}
                                                        </span>
                                                        <span class="text-gray-500 text-xs">
                                                            Submitted: {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y H:i') : '-' }}
                                                        </span>
                                                    </div>

                                                    <div class="flex flex-wrap gap-4 text-sm mt-3">
                                                        @if($submission->final_score !== null)
                                                            <div class="border border-gray-200 px-3 py-1.5 rounded-md">
                                                                <span class="text-gray-500 text-xs font-medium uppercase tracking-wider block">Judge Score</span>
                                                                <span class="font-bold text-gray-900">{{ $submission->final_score }}<span class="text-gray-400 font-normal">/100</span></span>
                                                            </div>
                                                        @endif
                                                        <div class="border border-gray-200 px-3 py-1.5 rounded-md">
                                                            <span class="text-gray-500 text-xs font-medium uppercase tracking-wider block">Time Bonus</span>
                                                            <span class="font-bold text-green-600">+{{ $submission->time_bonus ?? 0 }}<span class="text-gray-400 font-normal">/5</span></span>
                                                        </div>
                                                        <div class="border border-gray-300 bg-gray-50 px-3 py-1.5 rounded-md">
                                                            <span class="text-gray-700 text-xs font-bold uppercase tracking-wider block">Total</span>
                                                            <span class="font-bold text-gray-900">{{ $submission->total_score }}</span>
                                                        </div>
                                                    </div>

                                                    {{-- Revision info --}}
                                                    <div class="mt-2 text-xs font-medium {{ $canRevise ? 'text-gray-600' : 'text-red-600' }} flex items-center gap-1">
                                                        Revisi {{ $submission->revision_count }}/{{ $maxRevisions }}
                                                        @if(!$canRevise)
                                                            — Batas revisi tercapai
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4 mt-1">
                                            @if($isOpen && $canRevise)
                                                <a href="{{ route('participant.submissions.create', [$competition, $round]) }}" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 inline-flex items-center">
                                                    {{ $submission ? 'Revisi' : 'Submit' }}
                                                </a>
                                            @elseif(!$canRevise)
                                                <span class="px-4 py-2 bg-red-50 border border-red-200 text-red-600 rounded-md text-xs font-semibold uppercase tracking-widest cursor-not-allowed inline-flex">
                                                    Limit Revisi
                                                </span>
                                            @else
                                                <span class="px-4 py-2 bg-gray-100 border border-gray-200 text-gray-500 rounded-md text-xs font-semibold uppercase tracking-widest cursor-not-allowed inline-flex">
                                                    {{ $round->start_date && $round->start_date > now() ? 'Belum Mulai' : 'Ditutup' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
