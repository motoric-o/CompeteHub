@extends('layouts.app')

@section('title', 'Manajemen Kriteria Penilaian - ' . $competition->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('committee.management.competitions.index') }}" class="btn btn-outline btn-sm mb-4">
            ← Kembali ke Kompetisi
        </a>
        <h1 class="page-title">Manajemen Kriteria Penilaian</h1>
        <p class="page-subtitle">Kompetisi: {{ $competition->name }}</p>
    </div>
    <div>
        <a href="{{ route('committee.scoring-criteria.create', $competition) }}" class="btn btn-primary">
            + Tambah Kriteria
        </a>
    </div>
</div>

<div class="section">
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem; padding: 1rem; background: var(--color-success-light); color: var(--color-success-dark); border-radius: var(--radius-md);">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 1rem; padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: var(--radius-md);">
            {{ session('error') }}
        </div>
    @endif

    @php
        $totalMaxScore = $criteria->sum(fn($c) => $c->max_score * $c->weight);
    @endphp

    <div style="margin-bottom: 1.5rem; padding: 1rem; border: 1px solid #bfdbfe; background: #eff6ff; color: #1e40af; border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-weight: bold; margin-bottom: 0.25rem;">Total Maksimal Skor Penilaian</h3>
            <p style="font-size: 0.85rem; margin: 0; opacity: 0.8;">Berdasarkan akumulasi (Skor Maksimal × Bobot) seluruh kriteria.</p>
        </div>
        <div style="font-size: 1.5rem; font-weight: bold;">
            {{ floatval($totalMaxScore) }} pts
        </div>
    </div>

    <div class="card p-0">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Nama Kriteria</th>
                    <th>Deskripsi</th>
                    <th>Skor Maksimal</th>
                    <th>Bobot (Weight)</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($criteria as $criterion)
                    <tr>
                        <td class="font-bold">{{ $criterion->name }}</td>
                        <td>{{ $criterion->description ?: '-' }}</td>
                        <td>{{ $criterion->max_score }}</td>
                        <td>{{ $criterion->weight }}</td>
                        <td class="text-right flex gap-2 justify-end">
                            <a href="{{ route('committee.scoring-criteria.edit', [$competition, $criterion]) }}" class="btn btn-sm btn-outline">Edit</a>
                            <form action="{{ route('committee.scoring-criteria.destroy', [$competition, $criterion]) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kriteria ini? Semua data nilai terkait mungkin akan terpengaruh.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline" style="color: red; border-color: red;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-muted">Belum ada kriteria penilaian. Silakan tambah kriteria baru.</td>
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
</style>
@endsection
