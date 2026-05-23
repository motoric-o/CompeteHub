@extends('layouts.app')

@section('title', 'Manajemen Juri - ' . $competition->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('committee.management.competitions.index') }}" class="btn btn-outline btn-sm mb-4">
            ← Kembali ke Kompetisi
        </a>
        <h1 class="page-title">Manajemen Juri</h1>
        <p class="page-subtitle">Kompetisi: {{ $competition->name }}</p>
    </div>
</div>

<div class="section grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2">
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

        <div class="card p-0">
            <div style="padding: 1rem; border-bottom: 1px solid var(--color-border); background: var(--color-bg-elevated);">
                <h3 class="font-bold">Daftar Juri yang Ditugaskan</h3>
            </div>
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Nama Juri</th>
                        <th>Email</th>
                        <th>Tanggal Ditugaskan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                        <tr>
                            <td class="font-bold">{{ $assignment->user->name }}</td>
                            <td>{{ $assignment->user->email }}</td>
                            <td>{{ $assignment->assigned_at->format('d M Y, H:i') }}</td>
                            <td class="text-right">
                                <form action="{{ route('committee.juries.destroy', [$competition, $assignment]) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin mencabut tugas juri ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline" style="color: red; border-color: red;">Cabut Tugas</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-muted">Belum ada juri yang ditugaskan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <div class="card">
            <h3 class="font-bold mb-4">Tambahkan Juri</h3>
            <form action="{{ route('committee.juries.store', $competition) }}" method="POST">
                @csrf
                <div class="form-group mb-4">
                    <label for="user_id" class="form-label">Pilih Juri</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">-- Pilih Juri --</option>
                        @foreach($availableJudges as $judge)
                            <option value="{{ $judge->id }}">{{ $judge->name }} ({{ $judge->email }})</option>
                        @endforeach
                    </select>
                    @if($availableJudges->isEmpty())
                        <small class="text-muted block mt-2">Tidak ada juri tersedia yang dapat ditugaskan.</small>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary w-full" {{ $availableJudges->isEmpty() ? 'disabled' : '' }}>
                    Tambahkan Juri
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 1rem; border-bottom: 1px solid var(--color-border); text-align: left; }
    .table th { background: var(--color-bg-elevated); font-weight: 600; color: var(--color-text-muted); }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
    .form-control { width: 100%; padding: 0.75rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); }
    .w-full { width: 100%; }
</style>
@endsection
