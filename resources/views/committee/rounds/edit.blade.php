@extends('layouts.app')

@section('title', 'Edit Babak')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('committee.rounds.index', $competition) }}" class="btn btn-outline btn-sm mb-4">
            ← Kembali ke Daftar Babak
        </a>
        <h1 class="page-title">Edit Babak</h1>
        <p class="page-subtitle">Kompetisi: {{ $competition->name }}</p>
    </div>
</div>

<div class="section">
    <div class="card" style="max-width: 600px;">
        <form action="{{ route('committee.rounds.update', [$competition, $round]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Babak</label>
                <input type="text" name="name" id="name" class="form-input w-full" value="{{ old('name', $round->name) }}" required>
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="form-group mb-4">
                <label for="round_order" class="block text-sm font-medium text-gray-700 mb-1">Urutan Babak</label>
                <input type="number" name="round_order" id="round_order" class="form-input w-full" value="{{ old('round_order', $round->round_order) }}" required min="1">
                @error('round_order') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-input w-full" value="{{ old('start_date', $round->start_date ? $round->start_date->format('Y-m-d\TH:i') : '') }}">
                    @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-input w-full" value="{{ old('end_date', $round->end_date ? $round->end_date->format('Y-m-d\TH:i') : '') }}">
                    @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="form-input w-full" required>
                    <option value="pending" {{ old('status', $round->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ old('status', $round->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="finished" {{ old('status', $round->status) == 'finished' ? 'selected' : '' }}>Finished</option>
                </select>
                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('committee.rounds.index', $competition) }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-input {
        padding: 0.5rem;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        background: var(--color-bg-primary);
        color: var(--color-text);
    }
</style>
@endsection
