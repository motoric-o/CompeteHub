<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="page-title">{{ $competition->name }} — Hub</h2>
            <a href="{{ route('participant.my-competitions.index') }}" class="btn btn-secondary btn-sm">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <x-auth-session-status class="mb-4" :status="session('success')" />
        <x-auth-session-status class="mb-4 bg-red-100 text-red-600 p-4 border-2 border-black font-bold" :status="session('error')" />

        <!-- Status Bar -->
        <div class="card mb-6 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full border border-border bg-yellow-100 flex items-center justify-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div>
                    <p class="text-sm text-muted-foreground font-bold">Status Pendaftaran</p>
                    <p class="font-bold text-lg text-green-600 uppercase">Terverifikasi</p>
                </div>
            </div>
            
            <div class="text-right">
                <p class="text-sm text-muted-foreground font-bold">Terdaftar sebagai</p>
                <p class="font-bold text-lg">
                    {{ $registration->team_id ? 'Tim: ' . $registration->team->name : 'Individu: ' . auth()->user()->name }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Active Round & Actions -->
            <div class="lg:col-span-2 space-y-6">
                @if($activeRound)
                    <div class="card border-2 border-primary relative overflow-hidden">
                        @if($activeRound->status === 'finished' || ($activeRound->end_date && $activeRound->end_date < now()))
                            <div class="absolute top-0 right-0 bg-gray-500 text-white text-xs font-bold px-3 py-1 border-b-2 border-l-2 border-black rounded-bl-lg">
                                ROUND SELESAI
                            </div>
                        @else
                            <div class="absolute top-0 right-0 bg-primary text-primary-foreground text-xs font-bold px-3 py-1 border-b-2 border-l-2 border-black rounded-bl-lg">
                                ACTIVE ROUND
                            </div>
                        @endif
                        <h3 class="text-2xl font-bold mb-2">{{ $activeRound->name }}</h3>
                        <div class="text-sm font-medium text-gray-500 mb-6 flex gap-4">
                            @if($activeRound->start_date)
                                <span>Mulai: {{ $activeRound->start_date->format('d M Y H:i') }}</span>
                            @endif
                            @if($activeRound->end_date)
                                <span>Deadline: <strong class="text-red-500">{{ $activeRound->end_date->format('d M Y H:i') }}</strong></span>
                            @endif
                        </div>

                        <!-- Bracket Matchup (If Any) -->
                        @if($bracket)
                            <div class="bg-gray-100 border border-border p-4 rounded mb-6 text-center">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Pertandingan Anda</p>
                                <div class="flex justify-center items-center gap-4">
                                    <div class="font-bold text-lg flex-1 text-right">
                                        @if($bracket->participant_a)
                                            {{ $competition->type === 'team' ? \App\Models\Team::find($bracket->participant_a)->name : \App\Models\User::find($bracket->participant_a)->name }}
                                        @else
                                            (TBD)
                                        @endif
                                    </div>
                                    <div class="w-10 h-10 bg-red-500 text-white font-bold flex items-center justify-center rounded-full border-2 border-black shrink-0">
                                        VS
                                    </div>
                                    <div class="font-bold text-lg flex-1 text-left">
                                        @if($bracket->participant_b)
                                            {{ $competition->type === 'team' ? \App\Models\Team::find($bracket->participant_b)->name : \App\Models\User::find($bracket->participant_b)->name }}
                                        @else
                                            (TBD)
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Scoring & Points -->
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded mb-6">
                            <h4 class="font-bold mb-2 text-yellow-800">Skor Anda</h4>
                            @if($submission)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-xs font-bold text-yellow-600 uppercase">Time Bonus</p>
                                        <p class="text-lg font-bold text-yellow-900">+{{ $submission->time_bonus ?? 0 }} pts</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-bold text-yellow-600 uppercase">Total Nilai</p>
                                        <p class="text-3xl font-black text-yellow-900">
                                            {{ $submission->final_score !== null ? $submission->final_score : 'Belum Dinilai' }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm font-medium text-yellow-700">Anda belum mengirimkan submission untuk round ini.</p>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-3">
                            <a href="{{ route('participant.submissions.create', [$competition, $activeRound]) }}" class="btn {{ $activeRound->status === 'finished' ? 'bg-gray-400 border-2 border-gray-500 cursor-not-allowed' : 'btn-primary' }} w-full justify-center py-3 text-lg" {{ $activeRound->status === 'finished' ? 'onclick="event.preventDefault();"' : '' }}>
                                {{ $submission ? 'Edit Submission' : 'Upload Submission' }}
                            </a>

                            @if($activeRound->scoringType && $activeRound->scoringType->name === 'Community Voting')
                                <a href="{{ route('community.gallery.index', ['competition_id' => $competition->id]) }}" class="btn bg-white border-2 border-black text-black hover:bg-gray-50 w-full justify-center py-3 text-lg">
                                    Lihat Galeri & Voting
                                </a>
                            @endif

                            <a href="{{ route('leaderboard.index', $competition) }}" class="btn bg-gray-800 border-2 border-black text-white hover:bg-gray-700 w-full justify-center py-3 text-lg">
                                Lihat Leaderboard
                            </a>
                        </div>
                    </div>
                @else
                    <div class="card bg-gray-50 flex flex-col justify-center items-center py-12 text-center">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-400 mb-4"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        <h3 class="text-xl font-bold mb-2">Kompetisi Belum Dimulai</h3>
                        <p class="text-muted-foreground">Belum ada round yang aktif saat ini. Silakan periksa timeline di samping untuk jadwal.</p>
                    </div>
                @endif
            </div>

            <!-- Right Column: Timeline -->
            <div class="lg:col-span-1">
                <div class="card">
                    <h3 class="text-xl font-bold mb-6 border-b-2 border-border pb-2">Timeline Babak</h3>
                    <div class="space-y-6">
                        @foreach($rounds as $idx => $r)
                            @php
                                $isActive = $activeRound && $activeRound->id === $r->id && $r->status !== 'finished';
                                $isPast = $r->status === 'finished' || ($r->end_date && $r->end_date < now());
                                $isFuture = !$isPast && !$isActive;
                            @endphp
                            <div class="relative pl-6 border-l-2 {{ $isActive ? 'border-primary' : ($isPast ? 'border-gray-300' : 'border-gray-200 dashed') }}">
                                <!-- Node indicator -->
                                <div class="absolute w-4 h-4 rounded-full border-2 border-black bg-white -left-[9px] top-1 {{ $isActive ? 'bg-primary' : ($isPast ? 'bg-gray-400' : 'bg-white') }}"></div>
                                
                                <h4 class="font-bold {{ $isActive ? 'text-primary' : ($isPast ? 'text-gray-500 line-through' : 'text-gray-700') }}">
                                    {{ $idx + 1 }}. {{ $r->name }}
                                </h4>
                                <div class="text-xs font-medium mt-1 {{ $isActive ? 'text-gray-700' : 'text-gray-500' }}">
                                    @if($r->start_date || $r->end_date)
                                        {{ $r->start_date ? $r->start_date->format('d M') : '?' }} - {{ $r->end_date ? $r->end_date->format('d M') : '?' }}
                                    @else
                                        TBA
                                    @endif
                                </div>
                                @if($isActive)
                                    <span class="inline-block px-2 py-0.5 mt-2 text-[10px] font-bold bg-yellow-200 text-yellow-800 border border-black rounded">
                                        SEDANG BERLANGSUNG
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
