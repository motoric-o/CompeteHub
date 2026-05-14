@extends('layouts.app')

@section('title', $team->name . ' — CompeteHub')
@section('description', 'Detail tim ' . $team->name . ' di CompeteHub')

@php
    $isCaptain = auth()->check() && $team->user_id === auth()->id();
    $isMember  = auth()->check() && $team->members->contains('id', auth()->id());
@endphp

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $team->name }}</h1>
        <p class="page-subtitle">
            {{ $team->competition->name ?? 'N/A' }}
            @if($isCaptain)
                <span class="badge badge-captain" style="margin-left: 0.5rem;">Kapten</span>
            @elseif($isMember)
                <span class="badge badge-primary" style="margin-left: 0.5rem;">Anggota</span>
            @endif
        </p>
    </div>
    <a href="{{ route('teams.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="grid grid-cols-3" style="grid-template-columns: 2fr 1fr;">

    <!-- Kolom Kiri: Daftar Anggota -->
    <div>
        <div class="card animate-in">
            <div class="card-header">
                <h3 class="card-title">Anggota Tim ({{ $team->members->count() }})</h3>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Bergabung</th>
                            @if($isCaptain)
                                <th class="text-right">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($team->members as $index => $member)
                            <tr id="member-row-{{ $member->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td style="font-weight: 600;">
                                    {{ $member->name }}
                                </td>
                                <td class="text-muted">{{ $member->email }}</td>
                                <td>
                                    @if($team->user_id === $member->id)
                                        <span class="badge badge-captain">Kapten</span>
                                    @else
                                        <span class="badge badge-primary">Anggota</span>
                                    @endif
                                </td>
                                <td class="text-muted" style="font-size: 0.85rem;">
                                    {{ $member->pivot->joined_at
                                        ? \Carbon\Carbon::parse($member->pivot->joined_at)->format('d M Y, H:i')
                                        : '-'
                                    }}
                                </td>
                                @if($isCaptain)
                                    <td class="text-right">
                                        @if($team->user_id !== $member->id)
                                            <form action="{{ route('teams.kick', [$team, $member]) }}" method="POST"
                                                  onsubmit="return confirm('Yakin ingin mengeluarkan {{ $member->name }}?')"
                                                  style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" id="btn-kick-{{ $member->id }}">
                                                    Keluarkan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted" style="font-size: 0.8rem;">—</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol Leave (untuk anggota non-kapten) -->
        @if($isMember && !$isCaptain)
            <div class="mt-2">
                <form action="{{ route('teams.leave', $team) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin keluar dari tim {{ $team->name }}?')">
                    @csrf
                    <button type="submit" class="btn btn-danger" id="btn-leave-team">
                        Keluar dari Tim
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Kolom Kanan: Info Tim -->
    <div>
        <!-- Kode Undangan -->
        <div class="card animate-in" style="margin-bottom: 1.5rem;">
            <h3 class="card-title" style="margin-bottom: 1rem;">Kode Undangan</h3>
            <div class="invite-code-box" style="flex-direction: column; text-align: center;">
                <div style="font-size: 0.75rem; color: var(--color-text-dim);">BAGIKAN KODE INI</div>
                <div class="invite-code" id="invite-code-display">{{ $team->invite_code }}</div>
            </div>

            <button class="btn btn-secondary btn-sm mt-1" style="width: 100%;" onclick="copyInviteCode()" id="btn-copy-code">
                Salin Kode
            </button>

            @if($isCaptain)
                <form action="{{ route('teams.regenerateCode', $team) }}" method="POST" class="mt-1"
                      onsubmit="return confirm('Kode undangan lama akan tidak berlaku lagi. Lanjutkan?')">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm" style="width: 100%;" id="btn-regenerate-code">
                        Generate Kode Baru
                    </button>
                </form>
            @endif
        </div>

        <!-- Info Kompetisi -->
        <div class="card animate-in">
            <h3 class="card-title" style="margin-bottom: 1rem;">Detail Kompetisi</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.875rem;">
                <div>
                    <span class="text-muted">Nama:</span><br>
                    <span style="font-weight: 600;">{{ $team->competition->name ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-muted">Tipe:</span><br>
                    <span class="badge badge-success">{{ ucfirst($team->competition->type ?? 'N/A') }}</span>
                </div>
                <div>
                    <span class="text-muted">Biaya:</span><br>
                    <span style="font-weight: 600;">
                        Rp {{ number_format($team->competition->registration_fee ?? 0, 0, ',', '.') }}
                    </span>
                </div>
                <div>
                    <span class="text-muted">Status:</span><br>
                    <span class="badge badge-{{ ($team->competition->status ?? '') === 'open' ? 'success' : 'warning' }}">
                        {{ ucfirst($team->competition->status ?? 'N/A') }}
                    </span>
                </div>
                @if($team->competition->start_date && $team->competition->end_date)
                <div>
                    <span class="text-muted">Periode:</span><br>
                    <span style="font-size: 0.85rem;">
                        {{ $team->competition->start_date->format('d M Y') }} —
                        {{ $team->competition->end_date->format('d M Y') }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyInviteCode() {
        const code = document.getElementById('invite-code-display').textContent;
        navigator.clipboard.writeText(code).then(() => {
            const btn = document.getElementById('btn-copy-code');
            const original = btn.innerHTML;
            btn.innerHTML = 'Tersalin!';
            btn.style.background = 'rgba(16, 185, 129, 0.2)';
            setTimeout(() => {
                btn.innerHTML = original;
                btn.style.background = '';
            }, 2000);
        });
    }
</script>
@endpush
