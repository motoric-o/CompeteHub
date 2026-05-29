<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $submission ? __('Revisi Submission') : __('Upload Submission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800">{{ $competition->name }}</h3>
                        <p class="text-sm text-gray-500">Round: {{ $round->name }}</p>
                    </div>

                    {{-- Time Bonus Info --}}
                    @if($bonusPreview['is_revision'])
                        <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <h4 class="font-semibold text-gray-900">Revisi — Time Bonus Dipertahankan</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Revisi tidak akan mereset atau mengubah time bonus yang sudah Anda dapatkan di awal.
                            </p>
                            <div class="mt-4 grid grid-cols-3 gap-4">
                                <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Bonus Saat Ini</span>
                                    <span class="text-xl font-bold text-gray-900">+{{ $bonusPreview['current_bonus'] }}</span>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Setelah Revisi</span>
                                    <span class="text-xl font-bold text-gray-900">+{{ $bonusPreview['next_bonus'] }}</span>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Sisa Revisi</span>
                                    <span class="text-xl font-bold {{ $bonusPreview['revisions_left'] > 0 ? 'text-gray-900' : 'text-red-600' }}">{{ $bonusPreview['revisions_left'] }}</span>
                                    <span class="text-xs text-gray-400">/{{ $bonusPreview['max_revisions'] }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <h4 class="font-semibold text-gray-900">Time Bonus (Otomatis oleh Sistem)</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                1/3 tercepat dari {{ $bonusPreview['total_registrants'] }} pendaftar mendapat time bonus (max {{ $bonusPreview['max_time_bonus'] }} pts).
                                Submit lebih awal = bonus lebih besar! Time bonus Anda tidak akan hilang meskipun nanti direvisi.
                            </p>
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Estimasi Time Bonus</span>
                                    <span class="text-xl font-bold text-gray-900">+{{ $bonusPreview['next_bonus'] }}</span>
                                    <span class="text-xs text-gray-400">/{{ $bonusPreview['max_time_bonus'] }} max</span>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Maks Revisi</span>
                                    <span class="text-xl font-bold text-gray-900">{{ $bonusPreview['max_revisions'] }}×</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($submission)
                        <div class="mb-6 bg-white border border-gray-200 text-gray-800 px-4 py-3 rounded-md">
                            <p class="font-semibold text-gray-900">Anda sudah submit jawaban untuk round ini.</p>
                            <div class="text-sm mt-2 space-y-1">
                                <p><span class="text-gray-500">Status:</span> {{ ucfirst($submission->status) }}</p>
                                <p><span class="text-gray-500">Revisi ke:</span> {{ $submission->revision_count }} dari {{ $bonusPreview['max_revisions'] }}</p>
                            </div>
                            <p class="text-sm mt-3 text-gray-500">Submit ulang akan memperbarui jawaban sebelumnya.</p>
                        </div>
                    @endif

                    <form id="submissionForm" action="{{ route('participant.submissions.store', [$competition, $round]) }}" method="POST" enctype="multipart/form-data" class="mt-6 border-t pt-6">
                        @csrf
                        
                        @if($competition->isQuiz())
                            @php
                                $myAnswers = $submission ? $submission->quizAnswers->keyBy('question_id') : collect();
                            @endphp
                            
                            <div class="space-y-6 mb-6">
                                <h4 class="font-bold text-lg text-gray-900 border-b pb-2">Jawab Pertanyaan Quiz</h4>
                                
                                @forelse($questions as $index => $q)
                                    <div class="bg-gray-50 p-4 border border-gray-200 rounded-md">
                                        <p class="font-bold text-gray-800 mb-2">
                                            Soal #{{ $index + 1 }}: {{ $q->question_text }}
                                            <span class="text-xs text-gray-500 font-normal">({{ $q->points }} Poin)</span>
                                        </p>
                                        
                                        @if($q->question_type === 'multiple_choice' && is_array($q->options))
                                            <div class="space-y-2 mt-3">
                                                @foreach($q->options as $option)
                                                    <label class="flex items-center space-x-2 cursor-pointer p-2 rounded hover:bg-gray-100 transition duration-150">
                                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $option }}" 
                                                            {{ $myAnswers->has($q->id) && $myAnswers->get($q->id)->answer_text === $option ? 'checked' : '' }}
                                                            class="form-radio text-indigo-600" required>
                                                        <span class="text-sm text-gray-700">{{ $option }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="mt-3">
                                                <textarea name="answers[{{ $q->id }}]" class="form-input w-full p-2 border border-gray-300 rounded" rows="3" required placeholder="Tulis jawaban Anda di sini...">{{ $myAnswers->has($q->id) ? $myAnswers->get($q->id)->answer_text : '' }}</textarea>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-gray-500 italic">Belum ada pertanyaan quiz untuk round ini.</p>
                                @endforelse
                            </div>
                        @else
                            <div class="mb-4">
                                <x-input-label for="submission_file" :value="__('File Submission (PDF, ZIP, MP4 - Max 20MB)')" class="font-semibold" />
                                <input type="file" name="submission_file" id="submission_file" class="mt-2 block w-full text-sm text-gray-600
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border file:border-gray-300
                                  file:text-sm file:font-semibold
                                  file:bg-white file:text-gray-700
                                  hover:file:bg-gray-50 cursor-pointer" required>
                                <x-input-error :messages="$errors->get('submission_file')" class="mt-2" />
                            </div>
                        @endif

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('participant.submissions.index', $competition) }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium mr-4">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ $submission ? 'Submit Jawaban Baru' : 'Submit Jawaban' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const form = document.getElementById('submissionForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('submission_file');
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                if (file.size > 20 * 1024 * 1024) {
                    e.preventDefault();
                    alert('File terlalu besar! Maks 20 MB.');
                }
            }
        });
    }
</script>
