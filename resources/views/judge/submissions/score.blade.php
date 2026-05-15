<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Penilaian Submisi #{{ $submission->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-status class="mb-4 text-red-600 bg-red-100 p-4 rounded" :status="session('error')" />

            <div class="mb-6">
                <a href="{{ route('judge.submissions.round', [$competition, $submission->round_id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Kembali ke Daftar Submisi
                </a>
            </div>

            {{-- Submission Info Card --}}
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 mb-6">
                <div class="p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 border-b pb-2">Detail Submisi</h3>
                    <dl class="grid grid-cols-2 gap-y-6 gap-x-4 text-sm">
                        <div>
                            <dt class="text-gray-500 mb-1">Kompetisi</dt>
                            <dd class="font-medium text-gray-900">{{ $competition->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 mb-1">Round</dt>
                            <dd class="font-medium text-gray-900">{{ $submission->round->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 mb-1">Peserta</dt>
                            <dd class="font-medium text-gray-900">
                                {{ $submission->team ? $submission->team->name : ($submission->user ? $submission->user->name : '-') }}
                                <span class="text-xs text-gray-500 font-normal ml-1">{{ $submission->team_id ? '(Tim)' : '(Individu)' }}</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 mb-1">Waktu Submit</dt>
                            <dd class="font-medium text-gray-900">{{ $submission->submitted_at?->format('d M Y H:i:s') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 mb-1">File</dt>
                            <dd class="flex items-center flex-wrap gap-2">
                                <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs font-mono border border-gray-200">.{{ $submission->file_type }}</span>
                                <span class="text-xs text-gray-500">({{ number_format(($submission->file_size ?? 0) / 1024, 1) }} KB)</span>
                                @if($submission->file_path)
                                    <a href="{{ Storage::url($submission->file_path) }}" target="_blank" download
                                       class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-800 ml-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        Download
                                    </a>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 mb-1">Status Revisi</dt>
                            <dd>
                                @if($submission->revision_count > 0)
                                    <span class="text-gray-700 font-medium bg-gray-100 px-2 py-0.5 rounded text-xs">Revisi #{{ $submission->revision_count }}</span>
                                @else
                                    <span class="text-gray-700 font-medium text-sm">Asli (belum revisi)</span>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    {{-- Score Summary --}}
                    <div class="mt-8 grid grid-cols-3 gap-4 border-t pt-6">
                        <div class="border border-gray-200 rounded-lg p-4 text-center">
                            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider block mb-1">Time Bonus</span>
                            <span class="text-2xl font-bold text-green-600">+{{ $submission->time_bonus ?? 0 }}</span>
                            <span class="text-xs text-gray-400 block mt-1">/5 maks</span>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4 text-center">
                            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider block mb-1">Avg Score</span>
                            <span class="text-2xl font-bold text-gray-900">{{ $submission->final_score ?? '—' }}</span>
                            <span class="text-xs text-gray-400 block mt-1">/100 maks</span>
                        </div>
                        <div class="border border-gray-200 bg-gray-50 rounded-lg p-4 text-center">
                            <span class="text-xs text-gray-700 font-bold uppercase tracking-wider block mb-1">Total Score</span>
                            <span class="text-2xl font-bold text-gray-900">{{ $submission->total_score }}</span>
                            <span class="text-xs text-gray-500 block mt-1">/105 maks</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Scoring Form --}}
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 mb-6">
                <div class="p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 border-b pb-2">
                        {{ $myScore ? 'Edit Nilai' : 'Berikan Nilai' }}
                    </h3>

                    <form action="{{ route('judge.submissions.score', [$competition, $submission]) }}" method="POST">
                        @csrf

                        <div class="mb-5">
                            <x-input-label for="score" :value="__('Nilai (0 - 100)')" class="text-gray-700 font-semibold" />
                            <input type="number" name="score" id="score"
                                   min="0" max="100" step="0.01"
                                   value="{{ old('score', $myScore?->score) }}"
                                   class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-2xl font-bold text-center"
                                   placeholder="0 – 100" required>
                            <x-input-error :messages="$errors->get('score')" class="mt-2" />

                        </div>

                        <div class="mb-5">
                            <x-input-label for="notes" :value="__('Catatan (opsional)')" class="text-gray-700 font-semibold" />
                            <textarea name="notes" id="notes" rows="3"
                                      class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Catatan atau feedback untuk peserta...">{{ old('notes', $myScore?->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end border-t pt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ $myScore ? 'Update Nilai' : 'Simpan Nilai' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Other Judges' Scores --}}
            @if($allScores->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-4 border-b pb-2">Nilai dari Juri Lain</h3>
                        <div class="space-y-3">
                            @foreach($allScores as $score)
                                <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-gray-200 flex items-center justify-center text-gray-700 text-xs font-bold uppercase">
                                            {{ substr($score->judge->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-900 text-sm">{{ $score->judge->name ?? 'Unknown' }}</span>
                                            @if($score->user_id === auth()->id())
                                                <span class="text-xs text-gray-500 ml-1 font-normal">(Anda)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-bold text-gray-900">{{ $score->score }}</span>
                                        <span class="text-xs text-gray-400 block">{{ $score->scored_at?->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
