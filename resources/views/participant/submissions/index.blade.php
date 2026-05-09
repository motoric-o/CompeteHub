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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Competition Rounds</h3>
                    
                    @if($rounds->isEmpty())
                        <p class="text-gray-500">No rounds configured for this competition yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($rounds as $round)
                                @php
                                    $submission = $submissions->get($round->id);
                                    $isOpen = true;
                                    if ($round->start_date && $round->start_date > now()) $isOpen = false;
                                    if ($round->end_date && $round->end_date < now()) $isOpen = false;
                                @endphp
                                <div class="border rounded-lg p-4 flex justify-between items-center bg-gray-50">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $round->name }} (Round {{ $round->round_order }})</h4>
                                        <div class="text-sm text-gray-500 mt-1">
                                            @if($round->start_date)
                                                Start: {{ $round->start_date->format('d M Y H:i') }} <br>
                                            @endif
                                            @if($round->end_date)
                                                Deadline: <span class="{{ $round->end_date < now() ? 'text-red-500' : 'text-green-600' }}">{{ $round->end_date->format('d M Y H:i') }}</span>
                                            @endif
                                        </div>
                                        @if($submission)
                                            <div class="mt-2 text-sm text-indigo-600 font-medium">
                                                Status: {{ ucfirst($submission->status) }} 
                                                @if($submission->final_score !== null)
                                                | Score: {{ $submission->final_score }}
                                                @endif
                                                <br>
                                                Submitted at: {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y H:i') : $submission->created_at->format('d M Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if($isOpen)
                                            <a href="{{ route('participant.submissions.create', [$competition, $round]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-semibold transition">
                                                {{ $submission ? 'Update Submission' : 'Submit' }}
                                            </a>
                                        @else
                                            <span class="px-4 py-2 bg-gray-300 text-gray-600 rounded-md text-sm font-semibold cursor-not-allowed">
                                                {{ $round->start_date && $round->start_date > now() ? 'Not Started' : 'Closed' }}
                                            </span>
                                        @endif
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
