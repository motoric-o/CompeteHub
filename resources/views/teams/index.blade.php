@extends('layouts.app')

@section('title', 'Tim Saya — CompeteHub')
@section('description', 'Kelola tim kompetisi Anda di CompeteHub')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tim Saya</h1>
        <p class="page-subtitle">Kelola tim dan anggota untuk semua kompetisi</p>
    </div>
    <div class="flex gap-1">
        <a href="{{ route('teams.create') }}" class="btn btn-primary" id="btn-create-team">
            Buat Tim Baru
        </a>
    </div>
</div>

<!-- Join Tim via Kode Undangan -->
<div class="section animate-in">
    <div class="card" style="border-color: var(--color-secondary); border-style: dashed;">
        <div class="card-header">
            <h3 class="card-title">Gabung Tim via Kode Undangan</h3>
        </div>
        <form action="{{ route('teams.join') }}" method="POST" class="flex gap-1 items-center" style="flex-wrap: wrap;">
            @csrf
            <div style="flex: 1; min-width: 200px;">
                <input
                    type="text"
                    name="invite_code"
                    class="form-control"
                    placeholder="Masukkan kode 8 karakter (contoh: A1B2C3D4)"
                    maxlength="8"
                    style="text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600;"
                    id="input-invite-code"
                    value="{{ old('invite_code') }}"
                >
            </div>
            <button type="submit" class="btn btn-success" id="btn-join-team">
                Gabung
            </button>
        </form>
    </div>
</div>

<!-- Tim yang Dikaptenin -->
<div class="section animate-in">
    <h2 class="section-title">Tim yang Saya Pimpin</h2>

    @if($captainedTeams->isEmpty())
        <div class="card">
            <div class="empty-state">
                <p class="empty-state-text">Belum ada tim yang Anda pimpin</p>
                <a href="{{ route('teams.create') }}" class="btn btn-primary">Buat Tim Pertama</a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-2">
            @foreach($captainedTeams as $team)
                <div class="card animate-in" id="team-card-{{ $team->id }}">
                    <div class="card-header">
                        <h3 class="card-title">{{ $team->name }}</h3>
                        <span class="badge badge-captain">Kapten</span>
                    </div>
                    <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 0.5rem;">
                        {{ $team->competition->name ?? 'N/A' }}
                    </p>
                    <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1rem;">
                        {{ $team->members->count() }} anggota
                    </p>

                    <div class="invite-code-box">
                        <div>
                            <div style="font-size: 0.75rem; color: var(--color-text-dim); margin-bottom: 0.25rem;">KODE UNDANGAN</div>
                            <div class="invite-code">{{ $team->invite_code }}</div>
                        </div>
                    </div>

                    <a href="{{ route('teams.show', $team) }}" class="btn btn-outline btn-sm mt-1">
                        Lihat Detail
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Tim yang Diikuti sebagai Anggota -->
<div class="section animate-in">
    <h2 class="section-title">Tim yang Saya Ikuti</h2>

    @if($memberTeams->isEmpty())
        <div class="card">
            <div class="empty-state">
                <p class="empty-state-text">Belum bergabung ke tim manapun</p>
                <p class="text-muted" style="font-size: 0.85rem;">Masukkan kode undangan di atas untuk bergabung</p>
            </div>
        </div>
    @else
        <div class="grid grid-cols-2">
            @foreach($memberTeams as $team)
                <div class="card animate-in" id="member-team-card-{{ $team->id }}">
                    <div class="card-header">
                        <h3 class="card-title">{{ $team->name }}</h3>
                        <span class="badge badge-primary">Anggota</span>
                    </div>
                    <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 0.5rem;">
                        {{ $team->competition->name ?? 'N/A' }}
                    </p>
                    <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 0.5rem;">
                        Kapten: {{ $team->captain->name ?? 'N/A' }}
                    </p>
                    <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1rem;">
                        {{ $team->members->count() }} anggota
                    </p>

                    <div class="flex gap-1">
                        <a href="{{ route('teams.show', $team) }}" class="btn btn-outline btn-sm">
                            Lihat Detail
                        </a>
                        <form action="{{ route('teams.leave', $team) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin keluar dari tim {{ $team->name }}?')">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Keluar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Kompetisi Tim yang Tersedia -->
@if($availableCompetitions->isNotEmpty())
<div class="section animate-in">
    <h2 class="section-title">Kompetisi Tim yang Tersedia</h2>
    <div class="grid grid-cols-3">
        @foreach($availableCompetitions as $comp)
            @php
                $isOpen = $comp->isRegistrationOpen();
            @endphp
            <div class="card" id="competition-card-{{ $comp->id }}" style="{{ !$isOpen ? 'opacity: 0.75;' : '' }}">
                <h3 class="card-title">{{ $comp->name }}</h3>
                <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 0.5rem;">
                    {{ Str::limit($comp->description, 80) }}
                </p>
                <div style="font-size: 0.8rem; color: var(--color-text-dim); margin-bottom: 1rem;">
                    <div style="margin-bottom: 0.25rem;">
                        <strong>Pendaftaran:</strong> 
                        {{ $comp->registration_start ? $comp->registration_start->format('d M') : '?' }} - 
                        {{ $comp->registration_end ? $comp->registration_end->format('d M Y') : '?' }}
                    </div>
                    <div class="flex gap-1 items-center" style="font-size: 0.85rem; font-weight: 500;">
                        <span style="color: var(--chart-5); font-weight: 700;">Rp {{ number_format($comp->registration_fee, 0, ',', '.') }}</span>
                        <span style="color: var(--color-text-dim);">·</span>
                        <span style="color: var(--color-text-dim);">Kuota: {{ $comp->quota ?? '∞' }}</span>
                    </div>
                </div>
                
                @if($isOpen)
                    <a href="{{ route('teams.create', ['competition_id' => $comp->id]) }}" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center;">
                        Buat Tim
                    </a>
                @else
                    <button disabled class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center; cursor: not-allowed;">
                        Pendaftaran Ditutup
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif
@endsection
