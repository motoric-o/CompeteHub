@extends('layouts.app')

@section('title', 'Buat Kompetisi Baru — CompeteHub')
@section('description', 'Selenggarakan kompetisi baru dan mulai terima pendaftaran peserta.')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Buat Kompetisi Baru</h1>
        <p class="page-subtitle">Isi detail di bawah untuk meluncurkan kompetisi Anda</p>
    </div>
</div>

<div class="card animate-in" style="max-width: 800px;">
    <form action="{{ route('committee.management.competitions.store') }}" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

        <div class="grid grid-cols-2">
            <div class="form-group" style="grid-column: span 2;">
                <label for="name" class="form-label">Nama Kompetisi</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Hackathon Nasional 2026" required>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label for="description" class="form-label">Deskripsi Singkat</label>
                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Jelaskan tentang kompetisi ini...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="type" class="form-label">Tipe Peserta</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="individual" {{ old('type') == 'individual' ? 'selected' : '' }}>Individu</option>
                    <option value="team" {{ old('type') == 'team' ? 'selected' : '' }}>Tim / Kelompok</option>
                </select>
            </div>

            <div class="form-group">
                <label for="scoring_type_id" class="form-label">Metode Penilaian</label>
                <select name="scoring_type_id" id="scoring_type_id" class="form-control" required>
                    @foreach($scoringTypes as $st)
                        <option value="{{ $st->id }}" {{ old('scoring_type_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="registration_fee" class="form-label">Biaya Pendaftaran (Rp)</label>
                <input type="number" name="registration_fee" id="registration_fee" class="form-control" value="{{ old('registration_fee', 0) }}" min="0">
            </div>

            <div class="form-group">
                <label for="quota" class="form-label">Kuota Peserta</label>
                <input type="number" name="quota" id="quota" class="form-control" value="{{ old('quota') }}" placeholder="Kosongkan jika tidak terbatas">
            </div>

            <div class="section-title" style="grid-column: span 2; margin-top: 1rem; margin-bottom: 0.5rem; font-size: 0.9rem;">
                JADWAL PENDAFTARAN
            </div>

            <div class="form-group">
                <label for="registration_start" class="form-label">Mulai Pendaftaran</label>
                <input type="date" name="registration_start" id="registration_start" class="form-control" value="{{ old('registration_start') }}">
            </div>

            <div class="form-group">
                <label for="registration_end" class="form-label">Selesai Pendaftaran</label>
                <input type="date" name="registration_end" id="registration_end" class="form-control" value="{{ old('registration_end') }}">
            </div>

            <div class="section-title" style="grid-column: span 2; margin-top: 1rem; margin-bottom: 0.5rem; font-size: 0.9rem;">
                PELAKSANAAN LOMBA
            </div>

            <div class="form-group">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
            </div>

            <div class="form-group">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label for="rules" class="form-label">Aturan & Ketentuan</label>
                <textarea name="rules" id="rules" class="form-control" rows="5" placeholder="Tuliskan aturan main, kriteria penilaian, dll...">{{ old('rules') }}</textarea>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status Awal</label>
                <select name="status" id="status" class="form-control">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Langsung Buka</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--color-border);">
            <button type="submit" class="btn btn-primary">
                Simpan & Publikasikan
            </button>
            <a href="{{ route('committee.management.competitions.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
