@extends('layouts.app')

@section('title', 'Kirim Broadcast Email — CompeteHub')
@section('description', 'Kirim email notifikasi secara manual ke peserta kompetisi')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Broadcast Email</h1>
        <p class="page-subtitle">Kirim pengumuman penting atau instruksi dadakan ke seluruh peserta lomba</p>
    </div>
</div>

<div class="card animate-in" style="max-width: 600px;">
    <form action="{{ route('broadcast.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="competition_id" class="form-label">Pilih Kompetisi</label>
            <select name="competition_id" id="competition_id" class="form-control" required>
                <option value="">-- Pilih kompetisi --</option>
                @foreach($competitions as $comp)
                    <option value="{{ $comp->id }}" {{ old('competition_id') == $comp->id ? 'selected' : '' }}>
                        {{ $comp->name }} ({{ ucfirst($comp->type) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="subject" class="form-label">Subjek Email</label>
            <input 
                type="text" 
                name="subject" 
                id="subject" 
                class="form-control" 
                value="{{ old('subject') }}" 
                placeholder="Contoh: Perubahan Jadwal Technical Meeting" 
                required
            >
        </div>

        <div class="form-group">
            <label for="body" class="form-label">Isi Pesan</label>
            <textarea 
                name="body" 
                id="body" 
                class="form-control" 
                rows="6" 
                placeholder="Tuliskan detail pengumuman di sini..." 
                required
            >{{ old('body') }}</textarea>
            <p class="text-muted" style="font-size: 0.75rem; margin-top: 0.5rem;">
                Sistem otomatis menambahkan "Halo [Nama Peserta]" di awal email dan TTD Panitia di akhir email.
            </p>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">
                Kirim Broadcast Sekarang
            </button>
        </div>
    </form>
</div>
@endsection
