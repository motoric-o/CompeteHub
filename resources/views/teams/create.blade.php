@extends('layouts.app')

@section('title', 'Buat Tim — CompeteHub')
@section('description', 'Buat tim baru untuk kompetisi di CompeteHub')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Buat Tim Baru</h1>
        <p class="page-subtitle">Buat tim dan undang anggota untuk mengikuti kompetisi</p>
    </div>
    <a href="{{ route('teams.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card animate-in" style="max-width: 600px;">
    <form action="{{ route('teams.store') }}" method="POST" id="form-create-team">
        @csrf

        <div class="form-group">
            <label for="competition_id" class="form-label">Kompetisi</label>
            <select name="competition_id" id="competition_id" class="form-control" required>
                <option value="">-- Pilih kompetisi --</option>
                @foreach($competitions as $comp)
                    <option
                        value="{{ $comp->id }}"
                        {{ (old('competition_id', $selectedCompetitionId) == $comp->id) ? 'selected' : '' }}
                    >
                        {{ $comp->name }}
                        (Rp {{ number_format($comp->registration_fee, 0, ',', '.') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="name" class="form-label">Nama Tim</label>
            <input
                type="text"
                name="name"
                id="name"
                class="form-control"
                placeholder="Contoh: Tim Alpha"
                value="{{ old('name') }}"
                required
                minlength="3"
                maxlength="150"
            >
            <p class="text-muted mt-1" style="font-size: 0.8rem;">Minimal 3 karakter, maksimal 150 karakter</p>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary" id="btn-submit-team">
                Buat Tim
            </button>
            <a href="{{ route('teams.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<div class="mt-2" style="max-width: 600px;">
    <x-alert type="info" title="Informasi">
        <ul style="font-size: 0.875rem; list-style: none; display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem;">
            <li>• Kode undangan 8 karakter akan di-generate secara otomatis</li>
            <li>• Anda otomatis menjadi <strong>kapten tim</strong></li>
            <li>• Bagikan kode undangan ke anggota tim untuk bergabung</li>
            <li>• Kapten bisa mengeluarkan anggota kapan saja</li>
        </ul>
    </x-alert>
</div>
@endsection
