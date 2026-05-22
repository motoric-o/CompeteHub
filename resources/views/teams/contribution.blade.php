@extends('layouts.app')

@section('title', 'Statistik Kontribusi — ' . $team->name)
@section('description', 'Dashboard statistik kontribusi anggota tim ' . $team->name)

@php
    $chartColors = ['#FFED35', '#14b8a6', '#f59e0b', '#ec4899', '#22c55e', '#8b5cf6', '#06b6d4', '#f97316'];
@endphp

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Statistik Kontribusi</h1>
        <p class="page-subtitle">{{ $team->name }} — {{ $team->competition->name ?? 'N/A' }}</p>
    </div>
    <div class="flex gap-1">
        <a href="{{ route('teams.show', $team) }}" class="btn btn-secondary">Kembali ke Tim</a>
    </div>
</div>

{{-- Live Indicator --}}
<div class="flex items-center gap-1" style="margin-bottom: 1.5rem;">
    <span class="live-dot"></span>
    <span class="text-muted" style="font-size: 0.8rem;" id="contribution-last-updated">Live — updating every 5s</span>
</div>

{{-- Summary Cards --}}
<div class="grid contrib-summary-grid" style="margin-bottom: 2rem;" id="summary-cards">
    <div class="card contrib-summary-card animate-in">
        <div class="contrib-summary-label">Total Submisi Tim</div>
        <div class="contrib-summary-value font-mono" id="summary-total-submissions">{{ $totalSubmissions }}</div>
        <div class="contrib-summary-sub">dari semua anggota</div>
    </div>
    <div class="card contrib-summary-card animate-in" style="animation-delay: 0.05s;">
        <div class="contrib-summary-label">Rata-Rata Skor Tim</div>
        <div class="contrib-summary-value font-mono" id="summary-team-avg">
            {{ $teamAvgScore !== null ? number_format($teamAvgScore, 1) : '—' }}
        </div>
        <div class="contrib-summary-sub">dari submisi yang dinilai</div>
    </div>
    <div class="card contrib-summary-card animate-in" style="animation-delay: 0.1s;">
        <div class="contrib-summary-label">Jumlah Anggota</div>
        <div class="contrib-summary-value font-mono" id="summary-member-count">{{ $memberCount }}</div>
        <div class="contrib-summary-sub">aktif dalam tim</div>
    </div>
    <div class="card contrib-summary-card animate-in" style="animation-delay: 0.15s;">
        <div class="contrib-summary-label">Status Kompetisi</div>
        <div class="contrib-summary-value" style="font-size: 1.1rem;">
            <span class="badge badge-{{ ($team->competition->status ?? '') === 'open' ? 'success' : (($team->competition->status ?? '') === 'ongoing' ? 'warning' : 'muted') }}"
                  id="summary-comp-status">
                {{ ucfirst($team->competition->status ?? 'N/A') }}
            </span>
        </div>
        <div class="contrib-summary-sub">
            @if($team->competition->end_date)
                berakhir {{ $team->competition->end_date->format('d M Y') }}
            @endif
        </div>
    </div>
</div>

{{-- Contribution Chart + Detail Table --}}
<div class="grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 2rem;" id="contrib-main-content">

    {{-- Bar Chart --}}
    <div class="card animate-in" style="animation-delay: 0.2s;">
        <h3 class="card-title" style="margin-bottom: 1.25rem;">Distribusi Kontribusi</h3>
        <div id="contrib-chart-container">
            @if($stats->count() > 0 && $stats->where('contribution_pct', '>', 0)->count() > 0)
                @foreach($stats as $index => $stat)
                    <div class="contrib-bar-row">
                        <div class="contrib-bar-label">
                            <div class="contrib-bar-avatar" style="background: {{ $chartColors[$index % count($chartColors)] }};">
                                {{ strtoupper(substr($stat->user->name ?? '?', 0, 1)) }}
                            </div>
                            <span class="contrib-bar-name">{{ $stat->user->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="contrib-bar-track">
                            <div class="contrib-bar-fill"
                                 style="width: {{ $stat->contribution_pct ?? 0 }}%; background: {{ $chartColors[$index % count($chartColors)] }};"
                                 data-pct="{{ $stat->contribution_pct ?? 0 }}">
                            </div>
                        </div>
                        <div class="contrib-bar-value font-mono">{{ number_format($stat->contribution_pct ?? 0, 1) }}%</div>
                    </div>
                @endforeach
            @else
                <div class="contrib-empty-state">
                    <p style="font-weight: 600;">Belum ada data kontribusi</p>
                    <p class="text-muted" style="font-size: 0.85rem;">Data akan muncul setelah submisi dinilai oleh juri.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Submission Distribution (per member) --}}
    <div class="card animate-in" style="animation-delay: 0.25s;">
        <h3 class="card-title" style="margin-bottom: 1.25rem;">Submisi per Anggota</h3>
        <div id="submission-chart-container">
            @if($stats->count() > 0 && $stats->sum('submission_count') > 0)
                @php $maxSubmissions = collect($stats)->max('submission_count') ?: 1; @endphp
                @foreach($stats as $index => $stat)
                    <div class="contrib-bar-row">
                        <div class="contrib-bar-label">
                            <div class="contrib-bar-avatar" style="background: {{ $chartColors[$index % count($chartColors)] }};">
                                {{ strtoupper(substr($stat->user->name ?? '?', 0, 1)) }}
                            </div>
                            <span class="contrib-bar-name">{{ $stat->user->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="contrib-bar-track">
                            <div class="contrib-bar-fill"
                                 style="width: {{ ($stat->submission_count / $maxSubmissions) * 100 }}%; background: {{ $chartColors[$index % count($chartColors)] }};"
                                 data-count="{{ $stat->submission_count }}">
                            </div>
                        </div>
                        <div class="contrib-bar-value font-mono">{{ $stat->submission_count }}</div>
                    </div>
                @endforeach
            @else
                <div class="contrib-empty-state">
                    <p style="font-weight: 600;">Belum ada submisi</p>
                    <p class="text-muted" style="font-size: 0.85rem;">Submisi anggota akan muncul di sini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Detail Table --}}
<div class="card animate-in" style="animation-delay: 0.3s; margin-bottom: 2rem;">
    <div class="card-header">
        <h3 class="card-title">Detail Kontribusi Anggota</h3>
    </div>

    <div class="table-wrapper">
        <table id="contribution-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Anggota</th>
                    <th class="text-center">Submisi</th>
                    <th class="text-center">Rata-Rata Skor</th>
                    <th>Kontribusi</th>
                    <th>Waktu Aktif</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats as $index => $stat)
                    <tr id="contrib-row-{{ $stat->user_id }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="flex items-center gap-1">
                                <div class="contrib-table-avatar" style="background: {{ $chartColors[$index % count($chartColors)] }};">
                                    {{ strtoupper(substr($stat->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <span style="font-weight: 600; display: block;">{{ $stat->user->name ?? 'Unknown' }}</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">Terakhir: {{ $stat->last_updated ? \Carbon\Carbon::parse($stat->last_updated)->diffForHumans() : '—' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="font-mono" style="font-weight: 700;">{{ $stat->submission_count }}</span>
                        </td>
                        <td class="text-center">
                            @if($stat->avg_score !== null)
                                <span class="font-mono" style="font-weight: 700;">{{ number_format($stat->avg_score, 1) }}</span>
                                <span class="text-muted" style="font-size: 0.75rem;">/100</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="contrib-pct-cell">
                                <div class="contrib-pct-bar-track">
                                    <div class="contrib-pct-bar-fill"
                                         style="width: {{ $stat->contribution_pct ?? 0 }}%; background: {{ $chartColors[$index % count($chartColors)] }};">
                                    </div>
                                </div>
                                <span class="font-mono contrib-pct-value">{{ number_format($stat->contribution_pct ?? 0, 1) }}%</span>
                            </div>
                        </td>
                        <td class="text-muted" style="font-size: 0.85rem;">
                            {{ $stat->active_time }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 2rem;">
                            Belum ada data anggota.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Legend --}}
<div class="card animate-in" style="animation-delay: 0.35s; padding: 1rem 1.5rem;">
    <div style="display: flex; flex-direction: column; gap: 0.35rem; font-size: 0.8rem;" class="text-muted">
        <p><strong>Submisi:</strong> Jumlah file submisi yang dikirimkan oleh anggota untuk tim ini.</p>
        <p><strong>Rata-Rata Skor:</strong> Rata-rata skor dari semua submisi anggota yang sudah dinilai juri (max 100).</p>
        <p><strong>Kontribusi (%):</strong> Proporsi total skor anggota terhadap total skor tim secara keseluruhan.</p>
        <p><strong>Waktu Aktif:</strong> Lama waktu sejak anggota bergabung dengan tim.</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const CONTRIB_API = "{{ route('teams.contributions.api', $team) }}";
    let contribPollTimer = null;
    const chartColors = @json($chartColors);

    function renderContribChart(stats) {
        const container = document.getElementById('contrib-chart-container');
        if (!stats.length || !stats.some(s => s.contribution_pct > 0)) {
            container.innerHTML = '<div class="contrib-empty-state"><p style="font-weight:600;">Belum ada data kontribusi</p><p class="text-muted" style="font-size:0.85rem;">Data akan muncul setelah submisi dinilai oleh juri.</p></div>';
            return;
        }
        container.innerHTML = stats.map((s, i) => `
            <div class="contrib-bar-row">
                <div class="contrib-bar-label">
                    <div class="contrib-bar-avatar" style="background:${chartColors[i % chartColors.length]}">${s.name.charAt(0).toUpperCase()}</div>
                    <span class="contrib-bar-name">${s.name}</span>
                </div>
                <div class="contrib-bar-track">
                    <div class="contrib-bar-fill" style="width:${s.contribution_pct ?? 0}%;background:${chartColors[i % chartColors.length]}"></div>
                </div>
                <div class="contrib-bar-value font-mono">${(s.contribution_pct ?? 0).toFixed(1)}%</div>
            </div>
        `).join('');
    }

    function renderSubmissionChart(stats) {
        const container = document.getElementById('submission-chart-container');
        const maxCount = Math.max(...stats.map(s => s.submission_count), 1);
        if (!stats.length || !stats.some(s => s.submission_count > 0)) {
            container.innerHTML = '<div class="contrib-empty-state"><p style="font-weight:600;">Belum ada submisi</p><p class="text-muted" style="font-size:0.85rem;">Submisi anggota akan muncul di sini.</p></div>';
            return;
        }
        container.innerHTML = stats.map((s, i) => `
            <div class="contrib-bar-row">
                <div class="contrib-bar-label">
                    <div class="contrib-bar-avatar" style="background:${chartColors[i % chartColors.length]}">${s.name.charAt(0).toUpperCase()}</div>
                    <span class="contrib-bar-name">${s.name}</span>
                </div>
                <div class="contrib-bar-track">
                    <div class="contrib-bar-fill" style="width:${(s.submission_count / maxCount) * 100}%;background:${chartColors[i % chartColors.length]}"></div>
                </div>
                <div class="contrib-bar-value font-mono">${s.submission_count}</div>
            </div>
        `).join('');
    }

    function renderTable(stats) {
        const tbody = document.querySelector('#contribution-table tbody');
        if (!stats.length) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted" style="padding:2rem;">Belum ada data anggota.</td></tr>';
            return;
        }
        tbody.innerHTML = stats.map((s, i) => `
            <tr id="contrib-row-${s.user_id}">
                <td>${i + 1}</td>
                <td>
                    <div class="flex items-center gap-1">
                        <div class="contrib-table-avatar" style="background:${chartColors[i % chartColors.length]}">${s.name.charAt(0).toUpperCase()}</div>
                        <div>
                            <span style="font-weight:600; display:block;">${s.name}</span>
                            <span class="text-muted" style="font-size:0.75rem;">Terakhir: ${s.last_updated}</span>
                        </div>
                    </div>
                </td>
                <td class="text-center"><span class="font-mono" style="font-weight:700;">${s.submission_count}</span></td>
                <td class="text-center">
                    ${s.avg_score !== null
                        ? `<span class="font-mono" style="font-weight:700;">${s.avg_score.toFixed(1)}</span><span class="text-muted" style="font-size:0.75rem;">/100</span>`
                        : '<span class="text-muted">—</span>'}
                </td>
                <td>
                    <div class="contrib-pct-cell">
                        <div class="contrib-pct-bar-track">
                            <div class="contrib-pct-bar-fill" style="width:${s.contribution_pct ?? 0}%;background:${chartColors[i % chartColors.length]}"></div>
                        </div>
                        <span class="font-mono contrib-pct-value">${(s.contribution_pct ?? 0).toFixed(1)}%</span>
                    </div>
                </td>
                <td class="text-muted" style="font-size:0.85rem;">
                    ${s.active_time}
                </td>
            </tr>
        `).join('');
    }

    function updateSummaryCards(data) {
        document.getElementById('summary-total-submissions').textContent = data.total_submissions;
        document.getElementById('summary-team-avg').textContent = data.team_avg_score !== null ? data.team_avg_score.toFixed(1) : '—';
        document.getElementById('summary-member-count').textContent = data.member_count;
    }

    async function fetchContributions() {
        try {
            const res = await fetch(CONTRIB_API, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error();
            const data = await res.json();

            renderContribChart(data.stats);
            renderSubmissionChart(data.stats);
            renderTable(data.stats);
            updateSummaryCards(data);

            document.getElementById('contribution-last-updated').textContent =
                `Live — updated ${new Date().toLocaleTimeString()}`;
        } catch (e) {
            document.getElementById('contribution-last-updated').textContent =
                '⚠️ Connection error — retrying...';
        }
    }

    // Initial load delay, then poll
    setTimeout(() => {
        fetchContributions();
        contribPollTimer = setInterval(fetchContributions, 5000);
    }, 2000);

    // Pause polling when tab is hidden
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            clearInterval(contribPollTimer);
        } else {
            fetchContributions();
            contribPollTimer = setInterval(fetchContributions, 5000);
        }
    });
</script>
@endpush
