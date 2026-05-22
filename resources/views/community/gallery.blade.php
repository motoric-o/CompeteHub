<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Community Gallery: ') }} {{ $competition->name }} - {{ $round->name }}
            </h2>
            <a href="{{ route('home') }}" class="text-sm font-bold underline hover:no-underline">Back to Home</a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f7f9f3] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h3 class="text-3xl font-black text-black uppercase tracking-tight">Submissions</h3>
                <p class="text-gray-600 font-mono mt-2">Vote for your favorite submissions! Only verified participants can vote.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($submissions as $submission)
                    <div class="bg-white border-[3px] border-black rounded-[1rem] p-6 shadow-[6px_6px_0px_rgba(0,0,0,1)] hover:-translate-y-[4px] hover:shadow-[10px_10px_0px_rgba(0,0,0,1)] transition-all duration-200 flex flex-col">
                        <div class="flex-grow">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-bold text-xl text-black">
                                        {{ $competition->isTeamBased() ? $submission->team->name : $submission->user->name }}
                                    </h4>
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                @if($submission->submission_url)
                                    <a href="{{ $submission->submission_url }}" target="_blank" class="block w-full text-center bg-gray-100 border-2 border-black rounded-md py-4 font-mono font-bold hover:bg-[#FFED35] transition-colors">
                                        🔗 View URL Submission
                                    </a>
                                @elseif($submission->file_path)
                                    @if(in_array(strtolower($submission->file_type), ['png', 'jpg', 'jpeg', 'gif', 'svg']))
                                        <div class="border-2 border-black rounded-md overflow-hidden aspect-video bg-gray-100 mb-2">
                                            <img src="{{ Storage::url($submission->file_path) }}" alt="Submission Image" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="block w-full text-center bg-gray-100 border-2 border-black rounded-md py-4 font-mono font-bold hover:bg-[#FFED35] transition-colors">
                                        📁 Download {{ strtoupper($submission->file_type) }}
                                    </a>
                                @else
                                    <div class="w-full text-center bg-gray-100 border-2 border-dashed border-black rounded-md py-8 font-mono text-gray-500">
                                        No File/URL
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-auto border-t-[3px] border-black pt-4 flex justify-between items-center">
                            <div class="font-mono font-bold text-xl">
                                <span id="vote-count-{{ $submission->id }}">{{ $submission->votes_count }}</span> Votes
                            </div>
                            @php
                                $hasVoted = $submission->votes->where('user_id', auth()->id())->count() > 0;
                            @endphp
                            <button onclick="toggleVote({{ $submission->id }})" id="vote-btn-{{ $submission->id }}" class="px-6 py-2 border-2 border-black rounded-full font-bold uppercase transition-colors {{ $hasVoted ? 'bg-black text-[#FFED35]' : 'bg-[#FFED35] text-black hover:bg-black hover:text-[#FFED35]' }}">
                                {{ $hasVoted ? 'Voted' : 'Vote' }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center border-[3px] border-black rounded-[1rem] bg-white shadow-[6px_6px_0px_rgba(0,0,0,1)]">
                        <h3 class="text-2xl font-bold mb-2">No submissions yet!</h3>
                        <p class="font-mono text-gray-600">Be the first to submit for this competition.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function toggleVote(submissionId) {
            const btn = document.getElementById(`vote-btn-${submissionId}`);
            const countSpan = document.getElementById(`vote-count-${submissionId}`);
            
            // Optimistic UI update could go here, but let's wait for server response
            
            fetch(`/community/submissions/${submissionId}/vote`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json().then(data => ({status: response.status, body: data})))
            .then(result => {
                if (result.status === 200) {
                    countSpan.innerText = result.body.votes_count;
                    if (result.body.action === 'voted') {
                        btn.innerText = 'VOTED';
                        btn.className = 'px-6 py-2 border-2 border-black rounded-full font-bold uppercase transition-colors bg-black text-[#FFED35]';
                    } else {
                        btn.innerText = 'VOTE';
                        btn.className = 'px-6 py-2 border-2 border-black rounded-full font-bold uppercase transition-colors bg-[#FFED35] text-black hover:bg-black hover:text-[#FFED35]';
                    }
                } else {
                    alert('Error: ' + result.body.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            });
        }
    </script>
</x-app-layout>
