<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">Submission</h2>
                <p class="text-sm text-muted-foreground mt-1">{{ $competition->name }}</p>
            </div>
            <a href="{{ route('leaderboard.index', $competition) }}" class="btn btn-secondary text-sm">
                Lihat Leaderboard
            </a>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-status class="mb-4 text-red-600 bg-red-100 p-4 rounded" :status="session('error')" />

            <div class="card">
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div>
                        <h3 class="text-lg font-bold">Round Kompetisi</h3>
                        <p class="text-sm text-muted-foreground mt-1">
                            Cek status submission dan risiko deadline untuk setiap round.
                        </p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border bg-gray-50 border-gray-200 text-gray-700">
                        {{ $rounds->count() }} round
                    </span>
                </div>

                @if($rounds->isEmpty())
                    <div class="p-4 rounded-lg border bg-gray-50 border-gray-200 text-gray-700 text-sm">
                        Belum ada round untuk kompetisi ini.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($rounds as $round)
                            @php
                                $submission = $submissions->get($round->id);
                                $risk = $deadlineRisks[$round->id] ?? [
                                    'label' => 'Unknown',
                                    'message' => 'Status deadline belum tersedia.',
                                    'badge_class' => 'bg-gray-50 border-gray-200 text-gray-600',
                                    'panel_class' => 'bg-gray-50 border-gray-200 text-gray-700',
                                    'is_actionable' => false,
                                ];

                                $isOpen = true;
                                if ($round->start_date && $round->start_date > now()) $isOpen = false;
                                if ($round->end_date && $round->end_date < now()) $isOpen = false;
                                $canRevise = $submission ? ($submission->revision_count < $maxRevisions) : true;
                            @endphp

                            <div class="border border-gray-200 rounded-xl p-5 hover:bg-gray-50 transition-colors">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div class="flex-1 space-y-4">
                                        <div class="flex flex-wrap items-start justify-between gap-3">
                                            <div>
                                                <h4 class="font-bold text-gray-900">
                                                    {{ $round->name }} <span class="text-gray-400 font-medium">Round {{ $round->round_order }}</span>
                                                </h4>
                                                <div class="text-sm text-gray-500 mt-1 space-y-1">
                                                    @if($round->start_date)
                                                        <div>Start: <span class="font-medium text-gray-700">{{ $round->start_date->format('d M Y H:i') }}</span></div>
                                                    @endif
                                                    @if($round->end_date)
                                                        <div>Deadline: <span class="font-medium {{ $round->end_date < now() ? 'text-red-600' : 'text-gray-700' }}">{{ $round->end_date->format('d M Y H:i') }}</span></div>
                                                    @else
                                                        <div>Deadline: <span class="font-medium text-gray-700">Belum diatur</span></div>
                                                    @endif
                                                </div>
                                            </div>

                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $risk['badge_class'] }}">
                                                {{ $risk['label'] }}
                                            </span>
                                        </div>

                                        <div class="p-3 rounded-lg border text-sm {{ $risk['panel_class'] }}">
                                            {{ $risk['message'] }}
                                        </div>

                                        @if($submission)
                                            <div class="space-y-3 border-t border-gray-100 pt-4">
                                                <div class="flex flex-wrap items-center gap-3 text-sm">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wide border
                                                        {{ $submission->status === 'scored' ? 'bg-green-50 border-green-200 text-green-700' : 
                                                           ($submission->status === 'under_review' ? 'bg-yellow-50 border-yellow-200 text-yellow-700' : 'bg-blue-50 border-blue-200 text-blue-700') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                                    </span>
                                                    <span class="text-gray-500 text-xs">
                                                        Submitted: {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y H:i') : '-' }}
                                                    </span>
                                                </div>

                                                <div class="flex flex-wrap gap-3 text-sm">
                                                    @if($submission->final_score !== null)
                                                        @php
                                                            $maxScore = $competition->isQuiz() ? $round->quizQuestions->sum('points') : 100;
                                                        @endphp
                                                        <div class="border border-gray-200 px-3 py-1.5 rounded-md bg-white">
                                                            <span class="text-gray-500 text-xs font-medium uppercase tracking-wider block">{{ $competition->isQuiz() ? 'Quiz Score' : ($round->scoringType && $round->scoringType->name === 'Time Based' ? 'Time Score' : 'Judge Score') }}</span>
                                                            <span class="font-bold text-gray-900">{{ floatval($submission->final_score) }}<span class="text-gray-400 font-normal">/{{ $maxScore }}</span></span>
                                                        </div>
                                                    @endif

                                                    <div class="border border-gray-200 px-3 py-1.5 rounded-md bg-white">
                                                        <span class="text-gray-500 text-xs font-medium uppercase tracking-wider block">Time Bonus</span>
                                                        <span class="font-bold text-green-600">+{{ $submission->time_bonus ?? 0 }}<span class="text-gray-400 font-normal">/5</span></span>
                                                    </div>

                                                    <div class="border border-gray-300 bg-gray-50 px-3 py-1.5 rounded-md">
                                                        <span class="text-gray-700 text-xs font-bold uppercase tracking-wider block">Total</span>
                                                        <span class="font-bold text-gray-900">{{ $submission->total_score }}</span>
                                                    </div>
                                                </div>

                                                <div class="text-xs font-medium {{ $canRevise ? 'text-gray-600' : 'text-red-600' }}">
                                                    Revisi {{ $submission->revision_count }}/{{ $maxRevisions }}
                                                    @if(!$canRevise)
                                                        — Batas revisi tercapai
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="md:ml-4 md:mt-1">
                                        @if($isOpen && $canRevise)
                                            <a href="{{ route('participant.submissions.create', [$competition, $round]) }}" class="btn btn-primary text-xs">
                                                {{ $submission ? 'Revisi' : 'Submit' }}
                                            </a>
                                        @elseif(!$canRevise)
                                            <span class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 text-red-600 rounded-md text-xs font-semibold uppercase tracking-widest cursor-not-allowed">
                                                Limit Revisi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-200 text-gray-500 rounded-md text-xs font-semibold uppercase tracking-widest cursor-not-allowed">
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

            <div class="flex flex-wrap gap-3">
                @if($competition->isTeamBased() && $competition->isAllMembersSubmit())
                    @php
                        $userTeam = auth()->user()->teams()->where('competition_id', $competition->id)->first();
                    @endphp
                    @if($userTeam)
                        <a href="{{ route('participant.contributions.show', [$competition, $userTeam]) }}" class="btn btn-secondary text-sm">
                            Statistik Kontribusi Tim
                        </a>
                    @endif
                @endif

                <a href="{{ route('participant.competitions.index') }}" class="btn btn-outline text-sm">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</x-app-layout>