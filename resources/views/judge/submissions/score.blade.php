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

                    @php
                        $maxCriteriaScore = $criterias->sum(fn($c) => $c->max_score * $c->weight) ?: 100;
                    @endphp
                    {{-- Score Summary --}}
                    <div class="mt-8 grid grid-cols-3 gap-4 border-t pt-6">
                        <div class="border border-gray-200 rounded-lg p-4 text-center">
                            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider block mb-1">Time Bonus</span>
                            <span class="text-2xl font-bold text-green-600">+{{ floatval($submission->time_bonus) ?: 0 }}</span>
                            <span class="text-xs text-gray-400 block mt-1">/5 maks</span>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4 text-center">
                            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider block mb-1">Avg Score</span>
                            <span class="text-2xl font-bold text-gray-900">{{ $submission->final_score !== null ? floatval($submission->final_score) : '—' }}</span>
                            <span class="text-xs text-gray-400 block mt-1">/{{ floatval($maxCriteriaScore) }} maks</span>
                        </div>
                        <div class="border border-gray-200 bg-gray-50 rounded-lg p-4 text-center">
                            <span class="text-xs text-gray-700 font-bold uppercase tracking-wider block mb-1">Total Score</span>
                            <span class="text-2xl font-bold text-gray-900">{{ floatval($submission->total_score) }}</span>
                            <span class="text-xs text-gray-500 block mt-1">/{{ floatval($maxCriteriaScore + 5) }} maks</span>
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

                    @if($criterias->isEmpty())
                        <div class="p-4 mb-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg border border-yellow-200" role="alert">
                            <span class="font-bold">Peringatan!</span> Panitia belum menentukan kriteria penilaian untuk kompetisi ini. Silakan hubungi panitia terlebih dahulu sebelum memberikan nilai.
                        </div>
                    @else
                        <form action="{{ route('judge.submissions.score', [$competition, $submission]) }}" method="POST">
                            @csrf
                            @if(request('from_queue'))
                                <input type="hidden" name="from_queue" value="1">
                            @endif

                            <div class="space-y-6 mb-6">
                                @foreach($criterias as $criterion)
                                    @php
                                        $existingVal = old("criteria.{$criterion->id}", $myCriterionScores->get($criterion->id)?->value);
                                    @endphp
                                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 hover:border-blue-300 transition-colors duration-200">
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-2">
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-sm sm:text-base">{{ $criterion->name }}</h4>
                                                @if($criterion->description)
                                                    <p class="text-xs text-gray-500 mt-0.5">{{ $criterion->description }}</p>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1.5 self-stretch sm:self-auto justify-end">
                                                <span class="inline-block px-2 py-0.5 text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 rounded">
                                                    Bobot: {{ floatval($criterion->weight) }}x
                                                </span>
                                                <span class="inline-block px-2 py-0.5 text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-300 rounded">
                                                    Maks: {{ $criterion->max_score }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <input type="number" 
                                                   name="criteria[{{ $criterion->id }}]" 
                                                   id="criterion_{{ $criterion->id }}"
                                                   data-weight="{{ $criterion->weight }}"
                                                   min="0" 
                                                   max="{{ $criterion->max_score }}" 
                                                   step="0.01"
                                                   value="{{ $existingVal }}"
                                                   class="criterion-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg font-bold"
                                                   placeholder="0 – {{ $criterion->max_score }}" 
                                                   required>
                                            <x-input-error :messages="$errors->get('criteria.' . $criterion->id)" class="mt-2" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Live Total Score Display --}}
                            <div class="mb-6 p-4 border border-blue-200 bg-blue-50 rounded-lg flex justify-between items-center shadow-sm">
                                <div>
                                    <span class="text-sm font-bold text-blue-800 uppercase tracking-wider">Total Calculated Score</span>
                                    <p class="text-xs text-blue-600 mt-0.5">Sum of (Value × Weight) for all criteria</p>
                                </div>
                                <div class="text-right">
                                    <span id="calculated-total-score" class="text-3xl font-extrabold text-blue-900">0.00</span>
                                </div>
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
                    @endif
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const inputs = document.querySelectorAll('.criterion-input');
                    const totalDisplay = document.getElementById('calculated-total-score');

                    function calculateTotal() {
                        let total = 0;
                        inputs.forEach(input => {
                            const value = parseFloat(input.value) || 0;
                            const weight = parseFloat(input.getAttribute('data-weight')) || 1;
                            total += value * weight;
                        });
                        totalDisplay.textContent = total.toFixed(2);
                    }

                    inputs.forEach(input => {
                        input.addEventListener('input', calculateTotal);
                        input.addEventListener('change', calculateTotal);
                    });

                    // Initial calculation
                    calculateTotal();
                });
            </script>

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
