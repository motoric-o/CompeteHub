@extends('layouts.app')

@section('title', 'Edit Kriteria Penilaian')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('committee.scoring-criteria.index', $competition) }}" class="btn btn-outline btn-sm mb-4">
            ← Kembali ke Kriteria
        </a>
        <h1 class="page-title">Edit Kriteria Penilaian</h1>
        <p class="page-subtitle">Kompetisi: {{ $competition->name }}</p>
    </div>
</div>

<div class="section">
    <div class="card max-w-2xl">
        <form action="{{ route('committee.scoring-criteria.update', [$competition, $scoringCriterion]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group mb-4">
                <label for="round_id" class="form-label">Ronde (Babak)</label>
                <select name="round_id" id="round_id" class="form-control @error('round_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Ronde --</option>
                    @foreach($rounds as $round)
                        <option value="{{ $round->id }}" {{ old('round_id', $scoringCriterion->round_id) == $round->id ? 'selected' : '' }}>
                            {{ $round->name }}
                        </option>
                    @endforeach
                </select>
                @error('round_id')
                    <div class="text-danger text-sm mt-1" style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="name" class="form-label">Nama Kriteria</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $scoringCriterion->name) }}" required>
                @error('name')
                    <div class="text-danger text-sm mt-1" style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="description" class="form-label">Deskripsi Kriteria</label>
                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $scoringCriterion->description) }}</textarea>
                @error('description')
                    <div class="text-danger text-sm mt-1" style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="max_score" class="form-label">Skor Maksimal (Batas atas nilai yang dapat diberikan)</label>
                <input type="number" name="max_score" id="max_score" class="form-control @error('max_score') is-invalid @enderror" value="{{ old('max_score', $scoringCriterion->max_score) }}" min="1" required>
                @error('max_score')
                    <div class="text-danger text-sm mt-1" style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-6">
                <label for="weight" class="form-label">Bobot (Weight multiplier)</label>
                <input type="number" step="0.01" name="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight', $scoringCriterion->weight) }}" min="0.01" required>
                <small class="text-muted block mt-1">Bobot ini akan dikalikan dengan skor yang diberikan juri (misal bobot 0.5 untuk 50%).</small>
                @error('weight')
                    <div class="text-danger text-sm mt-1" style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('committee.scoring-criteria.index', $competition) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
    .form-control { width: 100%; padding: 0.75rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); }
    .text-danger { color: #dc2626; }
</style>
@endsection
