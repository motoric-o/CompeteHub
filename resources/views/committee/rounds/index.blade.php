@extends('layouts.app')

@section('title', 'Manajemen Babak - ' . $competition->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('committee.management.competitions.index') }}" class="btn btn-outline btn-sm mb-4">
            ← Kembali ke Kompetisi
        </a>
        <h1 class="page-title">Manajemen Babak & Bagan</h1>
        <p class="page-subtitle">Kompetisi: {{ $competition->name }}</p>
    </div>
    <div>
        <a href="{{ route('committee.rounds.create', $competition) }}" class="btn btn-primary">
            + Tambah Babak
        </a>
    </div>
</div>

<div class="section">
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem; padding: 1rem; background: var(--color-success-light); color: var(--color-success-dark); border-radius: var(--radius-md);">
            {{ session('success') }}
        </div>
    @endif

    <div class="card p-0">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Urutan</th>
                    <th>Nama Babak</th>
                    <th>Status</th>
                    <th>Tanggal Pelaksanaan</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rounds as $round)
                    <tr>
                        <td>{{ $round->round_order }}</td>
                        <td class="font-bold">{{ $round->name }}</td>
                        <td>
                            <span class="badge {{ $round->status === 'active' ? 'badge-primary' : ($round->status === 'finished' ? 'badge-secondary' : '') }}">
                                {{ ucfirst($round->status) }}
                            </span>
                        </td>
                        <td>
                            {{ $round->start_date ? $round->start_date->format('d M Y') : '-' }} s/d 
                            {{ $round->end_date ? $round->end_date->format('d M Y') : '-' }}
                        </td>
                        <td class="text-right flex gap-2 justify-end">
                            <a href="{{ route('committee.rounds.show', [$competition, $round]) }}" class="btn btn-sm btn-primary">Atur Bagan</a>
                            <a href="{{ route('committee.rounds.edit', [$competition, $round]) }}" class="btn btn-sm btn-outline">Edit</a>
                            <form action="{{ route('committee.rounds.destroy', [$competition, $round]) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus babak ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline" style="color: red; border-color: red;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-muted">Belum ada babak yang dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 1rem; border-bottom: 1px solid var(--color-border); text-align: left; }
    .table th { background: var(--color-bg-elevated); font-weight: 600; color: var(--color-text-muted); }
    .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .badge-primary { background: var(--color-primary-light); color: var(--color-primary-dark); }
    .badge-secondary { background: var(--color-border); color: var(--color-text-muted); }
</style>
@endsection
