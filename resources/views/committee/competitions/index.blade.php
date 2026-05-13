@extends('layouts.app')

@section('title', 'Manajemen Kompetisi — CompeteHub')
@section('description', 'Kelola kompetisi Anda, buat formulir pendaftaran, dan verifikasi peserta.')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Kompetisi</h1>
        <p class="page-subtitle">Kelola seluruh aspek kompetisi yang Anda selenggarakan</p>
    </div>
    <div>
        <a href="{{ route('committee.management.competitions.create') }}" class="btn btn-primary" id="btn-create-competition">
            + Kompetisi Baru
        </a>
    </div>
</div>

<div class="section animate-in">
    <div class="grid grid-cols-2">
        @forelse($competitions as $competition)
            <div class="card" id="competition-card-{{ $competition->id }}">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div>
                        <h3 class="card-title" style="margin-bottom: 0.25rem;">{{ $competition->name }}</h3>
                        <div class="flex gap-1 items-center">
                            <span class="badge {{ $competition->type === 'team' ? 'badge-primary' : 'badge-secondary' }}" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                                {{ ucfirst($competition->type) }}
                            </span>
                            <span class="badge" style="font-size: 0.7rem; padding: 0.2rem 0.5rem; background: var(--color-bg-elevated); color: var(--color-text-muted);">
                                {{ ucfirst($competition->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--color-primary-dark);">
                            Rp {{ number_format($competition->registration_fee, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 0.7rem; color: var(--color-text-dim);">
                            Kuota: {{ $competition->quota ?? '∞' }}
                        </div>
                    </div>
                </div>

                <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $competition->description ?? 'Tidak ada deskripsi.' }}
                </p>

                <div class="flex gap-1" style="flex-wrap: wrap;">
                    <a href="{{ route('committee.form-templates.index', $competition) }}" class="btn btn-outline btn-sm" style="flex: 1; justify-content: center;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Formulir
                    </a>
                    <a href="{{ route('committee.registrations.index', $competition) }}" class="btn btn-outline btn-sm" style="flex: 1; justify-content: center;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Peserta
                    </a>
                    <a href="{{ route('committee.management.competitions.edit', $competition) }}" class="btn btn-secondary btn-sm" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="card" style="grid-column: span 2; text-align: center; padding: 4rem 2rem; border-style: dashed;">
                <div class="empty-state">
                    <p class="empty-state-text" style="font-size: 1.1rem; font-weight: 600;">Belum ada kompetisi yang dibuat</p>
                    <p class="text-muted" style="margin-bottom: 1.5rem;">Mulai selenggarakan event pertama Anda sekarang juga!</p>
                    <a href="{{ route('committee.management.competitions.create') }}" class="btn btn-primary">
                        Buat Kompetisi Pertama
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .badge {
        display: inline-flex;
        align-items: center;
        border-radius: var(--radius-sm);
        font-weight: 600;
        line-height: 1;
    }
    .badge-primary { background: var(--color-primary-light); color: var(--primary-foreground); }
    .badge-secondary { background: var(--muted); color: var(--color-text-muted); }
    
    .btn-outline {
        background: transparent;
        border: 1px solid var(--color-border);
        color: var(--color-text);
    }
    .btn-outline:hover {
        border-color: var(--color-primary);
        color: var(--color-primary-dark);
        background: var(--color-primary-light);
    }
</style>
@endsection

