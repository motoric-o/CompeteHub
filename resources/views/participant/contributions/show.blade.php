@extends('layouts.app')

@section('title', 'Statistik Kontribusi — ' . $team->name)
@section('description', 'Statistik kontribusi anggota tim ' . $team->name)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Statistik Kontribusi: {{ $team->name }}</h1>
        <p class="page-subtitle">
            {{ $competition->name }}
            <span class="badge badge-success" style="margin-left: 0.5rem;">All Members Submit</span>
        </p>
    </div>
    @if($isCommittee)
        <a href="{{ route('committee.management.competitions.show', $competition) }}" class="btn btn-secondary">Kembali ke Kompetisi</a>
    @else
        <a href="{{ route('teams.show', $team) }}" class="btn btn-secondary">Kembali ke Tim</a>
    @endif
</div>

<div class="mb-6 animate-in" style="animation-delay: 0.05s;">
    <div style="display: flex; gap: 1rem; align-items: flex-end; justify-content: center; height: 160px; padding-top: 1rem;">
        @php
            $topMembers = $stats->sortByDesc('contribution_pct')->take(3)->values();
        @endphp
        
        {{-- Rank 2 --}}
        @if(isset($topMembers[1]))
            <div style="flex: 1; max-width: 150px; text-align: center; display: flex; flex-direction: column; align-items: center;">
                <div style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.5rem;" class="truncate w-full" title="{{ $topMembers[1]->user->name }}">{{ $topMembers[1]->user->name }}</div>
                <div style="width: 100%; height: 80px; background: linear-gradient(to top, #e5e7eb, #f3f4f6); border-top-left-radius: 8px; border-top-right-radius: 8px; border: 1px solid #d1d5db; border-bottom: none; display: flex; justify-content: center; padding-top: 0.5rem;">
                    <span style="font-size: 1.5rem;">🥈</span>
                </div>
                <div style="font-size: 0.8rem; font-weight: bold; background: #e5e7eb; width: 100%; padding: 0.25rem 0; border: 1px solid #d1d5db; border-top: none;">
                    {{ number_format($topMembers[1]->contribution_pct, 1) }}%
                </div>
            </div>
        @endif

        {{-- Rank 1 --}}
        @if(isset($topMembers[0]))
            <div style="flex: 1; max-width: 150px; text-align: center; display: flex; flex-direction: column; align-items: center;">
                <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" class="truncate w-full" title="{{ $topMembers[0]->user->name }}">{{ $topMembers[0]->user->name }}</div>
                <div style="width: 100%; height: 110px; background: linear-gradient(to top, #fef08a, #fef9c3); border-top-left-radius: 8px; border-top-right-radius: 8px; border: 1px solid #fde047; border-bottom: none; display: flex; justify-content: center; padding-top: 0.5rem;">
                    <span style="font-size: 2rem;">🥇</span>
                </div>
                <div style="font-size: 0.85rem; font-weight: bold; background: #fef08a; width: 100%; padding: 0.25rem 0; border: 1px solid #fde047; border-top: none;">
                    {{ number_format($topMembers[0]->contribution_pct, 1) }}%
                </div>
            </div>
        @endif

        {{-- Rank 3 --}}
        @if(isset($topMembers[2]))
            <div style="flex: 1; max-width: 150px; text-align: center; display: flex; flex-direction: column; align-items: center;">
                <div style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.5rem;" class="truncate w-full" title="{{ $topMembers[2]->user->name }}">{{ $topMembers[2]->user->name }}</div>
                <div style="width: 100%; height: 60px; background: linear-gradient(to top, #ffedd5, #fff7ed); border-top-left-radius: 8px; border-top-right-radius: 8px; border: 1px solid #fdba74; border-bottom: none; display: flex; justify-content: center; padding-top: 0.5rem;">
                    <span style="font-size: 1.2rem;">🥉</span>
                </div>
                <div style="font-size: 0.8rem; font-weight: bold; background: #ffedd5; width: 100%; padding: 0.25rem 0; border: 1px solid #fdba74; border-top: none;">
                    {{ number_format($topMembers[2]->contribution_pct, 1) }}%
                </div>
            </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-3 mb-2 animate-in" style="gap: 1.5rem; animation-delay: 0.1s;">
    <div class="card" style="text-align: center;">
        <div class="text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Total Submisi Tim</div>
        <div style="font-size: 2.5rem; font-weight: 800; font-family: var(--font-mono); line-height: 1;">
            {{ $totalSubmissions }}
        </div>
    </div>
    <div class="card" style="text-align: center;">
        <div class="text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Total Skor Tim</div>
        <div style="font-size: 2.5rem; font-weight: 800; font-family: var(--font-mono); line-height: 1; color: var(--chart-2);">
            {{ number_format($totalTeamScore, 2) }}
        </div>
    </div>
    <div class="card" style="text-align: center;">
        <div class="text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Anggota Aktif Submit</div>
        <div style="font-size: 2.5rem; font-weight: 800; font-family: var(--font-mono); line-height: 1; color: var(--chart-5);">
            {{ $activeMembersCount }} <span style="font-size: 1.2rem; color: var(--muted-foreground);">/ {{ $stats->count() }}</span>
        </div>
    </div>
</div>

<div class="card animate-in" style="animation-delay: 0.2s;">
    <div class="card-header" style="margin-bottom: 1.5rem;">
        <h3 class="card-title">Rincian Kontribusi per Anggota</h3>
        <p class="text-muted" style="font-size: 0.9rem;">
            Statistik ini membantu melihat seberapa aktif masing-masing anggota tim berkontribusi terhadap skor akhir tim. 
        </p>
    </div>

    @if($stats->isEmpty())
        <div style="padding: 3rem; text-align: center; border: 2px dashed var(--border); border-radius: var(--radius); background: var(--muted);">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
            <h3 style="margin-bottom: 0.5rem;">Belum ada anggota di tim ini</h3>
        </div>
    @else
        <div class="table-wrapper">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0 0.5rem;">
                <thead>
                    <tr>
                        <th style="padding: 0.75rem; text-align: left; font-size: 0.85rem; color: var(--muted-foreground); text-transform: uppercase;">Anggota</th>
                        <th style="padding: 0.75rem; text-align: left; font-size: 0.85rem; color: var(--muted-foreground); text-transform: uppercase;">Peran</th>
                        <th style="padding: 0.75rem; text-align: center; font-size: 0.85rem; color: var(--muted-foreground); text-transform: uppercase;">Submisi</th>
                        <th style="padding: 0.75rem; text-align: center; font-size: 0.85rem; color: var(--muted-foreground); text-transform: uppercase;">Avg Skor</th>
                        <th style="padding: 0.75rem; text-align: left; font-size: 0.85rem; color: var(--muted-foreground); text-transform: uppercase; width: 40%;">Kontribusi (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats->sortByDesc('contribution_pct') as $stat)
                        @php
                            $isCaptain = $team->user_id === $stat->user_id;
                            $hasSubmitted = $stat->submission_count > 0;
                            // Color rotation for bars based on position
                            $barColors = ['var(--chart-1)', 'var(--chart-2)', 'var(--chart-3)', 'var(--chart-4)', 'var(--chart-5)'];
                            $colorIndex = $loop->index % count($barColors);
                            $barColor = $barColors[$colorIndex];
                            
                            $pct = $stat->contribution_pct ?? 0;
                        @endphp
                        <tr style="background: var(--background); transition: all 0.2s;" class="hover:-translate-y-[2px]">
                            <td style="padding: 1rem; border-top-left-radius: var(--radius-sm); border-bottom-left-radius: var(--radius-sm); border: 1px solid var(--border); border-right: none;">
                                <div style="font-weight: 600;">
                                    {{ $stat->user->name }}
                                    @if($userAccess ?? false)
                                        @if(auth()->id() === $stat->user_id)
                                            <span style="font-size: 0.7rem; color: var(--muted-foreground); margin-left: 0.25rem;">(Anda)</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 1rem; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
                                @if($isCaptain)
                                    <span style="font-weight: 600; color: var(--foreground);">Kapten</span>
                                @else
                                    <span style="font-weight: 400; color: var(--foreground);">Anggota</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: center; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); font-family: var(--font-mono); font-weight: 600;">
                                {{ $stat->submission_count }}
                            </td>
                            <td style="padding: 1rem; text-align: center; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); font-family: var(--font-mono);">
                                {{ $hasSubmitted ? number_format($stat->avg_score, 2) : '-' }}
                            </td>
                            <td style="padding: 1rem; border-top-right-radius: var(--radius-sm); border-bottom-right-radius: var(--radius-sm); border: 1px solid var(--border); border-left: none;">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="font-family: var(--font-mono); font-weight: 700; min-width: 45px; text-align: right;">
                                        {{ number_format($pct, 1) }}%
                                    </div>
                                    <div style="flex: 1; height: 12px; background: var(--muted); border-radius: 6px; overflow: hidden; border: 1px solid rgba(0,0,0,0.1);">
                                        <div style="height: 100%; width: {{ $pct }}%; background: {{ $barColor }}; transition: width 1s cubic-bezier(0.4, 0, 0.2, 1); border-right: 1px solid rgba(0,0,0,0.2);"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
