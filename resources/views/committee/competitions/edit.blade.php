@extends('layouts.app')

@section('title', 'Edit Kompetisi — CompeteHub')
@section('description', 'Perbarui detail kompetisi Anda.')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Kompetisi</h1>
        <p class="page-subtitle">Perbarui informasi untuk kompetisi: <strong>{{ $competition->name }}</strong></p>
    </div>
    <form action="{{ route('committee.management.competitions.destroy', $competition) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kompetisi ini? Semua data terkait akan hilang.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            Hapus Kompetisi
        </button>
    </form>
</div>

<div class="card animate-in" style="max-width: 800px;">
    <form action="{{ route('committee.management.competitions.update', $competition) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="user_id" value="{{ $competition->user_id }}">

        <div class="grid grid-cols-2">
            <div class="form-group" style="grid-column: span 2;">
                <label for="name" class="form-label">Nama Kompetisi</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $competition->name) }}" required>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label for="description" class="form-label">Deskripsi Singkat</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $competition->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="type" class="form-label">Tipe Peserta</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="individual" {{ old('type', $competition->type) == 'individual' ? 'selected' : '' }}>Individu</option>
                    <option value="team" {{ old('type', $competition->type) == 'team' ? 'selected' : '' }}>Tim / Kelompok</option>
                </select>
            </div>

            <div class="form-group" id="submission_mode_group">
                <label for="submission_mode" class="form-label">Mode Pengumpulan (Untuk Tim)</label>
                <select name="submission_mode" id="submission_mode" class="form-control">
                    <option value="captain_only" {{ old('submission_mode', $competition->submission_mode) == 'captain_only' ? 'selected' : '' }}>Hanya Ketua Tim</option>
                    <option value="all_members" {{ old('submission_mode', $competition->submission_mode) == 'all_members' ? 'selected' : '' }}>Semua Anggota Tim</option>
                </select>
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Kategori Kompetisi</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="Web Development" {{ old('category', $competition->category) == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                    <option value="Capture The Flag" {{ old('category', $competition->category) == 'Capture The Flag' ? 'selected' : '' }}>Capture The Flag (CTF)</option>
                    <option value="UI/UX Design" {{ old('category', $competition->category) == 'UI/UX Design' ? 'selected' : '' }}>UI/UX Design</option>
                    <option value="Competitive Programming" {{ old('category', $competition->category) == 'Competitive Programming' ? 'selected' : '' }}>Competitive Programming</option>
                    <option value="Other" {{ old('category', $competition->category) == 'Other' ? 'selected' : '' }}>Lainnya (Other)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="scoring_type_id" class="form-label">Metode Penilaian</label>
                <select name="scoring_type_id" id="scoring_type_id" class="form-control" required>
                    @foreach($scoringTypes as $st)
                        <option value="{{ $st->id }}" {{ old('scoring_type_id', $competition->scoring_type_id) == $st->id ? 'selected' : '' }}>
                            {{ $st->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="competition_system" class="form-label">Sistem Kompetisi</label>
                <select name="competition_system" id="competition_system" class="form-control" required>
                    <option value="submission" {{ old('competition_system', $competition->competition_system) == 'submission' ? 'selected' : '' }}>Pengumpulan File (Submission)</option>
                    <option value="quiz" {{ old('competition_system', $competition->competition_system) == 'quiz' ? 'selected' : '' }}>Quiz / Tanya Jawab Online (Interactive Q&A)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="registration_fee" class="form-label">Biaya Pendaftaran (Rp)</label>
                <input type="number" name="registration_fee" id="registration_fee" class="form-control" value="{{ old('registration_fee', $competition->registration_fee) }}" min="0">
            </div>

            <div class="form-group">
                <label for="quota" class="form-label">Kuota Peserta</label>
                <input type="number" name="quota" id="quota" class="form-control" value="{{ old('quota', $competition->quota) }}">
            </div>

            <div class="section-title" style="grid-column: span 2; margin-top: 1rem; margin-bottom: 0.5rem; font-size: 0.9rem;">
                JADWAL PENDAFTARAN
            </div>

            <div class="form-group">
                <label for="registration_start" class="form-label">Mulai Pendaftaran</label>
                <input type="date" name="registration_start" id="registration_start" class="form-control" value="{{ old('registration_start', $competition->registration_start ? $competition->registration_start->format('Y-m-d') : '') }}">
            </div>

            <div class="form-group">
                <label for="registration_end" class="form-label">Selesai Pendaftaran</label>
                <input type="date" name="registration_end" id="registration_end" class="form-control" value="{{ old('registration_end', $competition->registration_end ? $competition->registration_end->format('Y-m-d') : '') }}">
            </div>

            <div class="section-title" style="grid-column: span 2; margin-top: 1rem; margin-bottom: 0.5rem; font-size: 0.9rem;">
                PELAKSANAAN LOMBA
            </div>

            <div class="form-group">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $competition->start_date ? $competition->start_date->format('Y-m-d') : '') }}">
            </div>

            <div class="form-group">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $competition->end_date ? $competition->end_date->format('Y-m-d') : '') }}">
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label for="rules" class="form-label">Aturan & Ketentuan</label>
                <textarea name="rules" id="rules" class="form-control" rows="5" placeholder="Tuliskan aturan main, kriteria penilaian, dll...">{{ old('rules', $competition->rules) }}</textarea>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="draft" {{ old('status', $competition->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="open" {{ old('status', $competition->status) == 'open' ? 'selected' : '' }}>Buka Pendaftaran</option>
                    <option value="ongoing" {{ old('status', $competition->status) == 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                    <option value="finished" {{ old('status', $competition->status) == 'finished' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>


            <div class="form-group" style="grid-column: span 2;">
                <label class="form-label">Tipe File Submisi yang Diizinkan</label>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    @php $oldTypes = old('allowed_file_types', $competition->allowed_file_types ?: ['pdf', 'zip', 'mp4']); @endphp
                    @foreach(['pdf', 'zip', 'mp4', 'png', 'jpg', 'fig', 'csv'] as $ext)
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <input type="checkbox" name="allowed_file_types[]" id="ext_{{ $ext }}" value="{{ $ext }}" {{ in_array($ext, $oldTypes) ? 'checked' : '' }}>
                        <label for="ext_{{ $ext }}" style="margin-bottom: 0;">.{{ strtoupper($ext) }}</label>
                    </div>
                    @endforeach
                </div>
                <small class="text-gray-500">Selain file, peserta juga bisa mengirim URL.</small>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--color-border);">
            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>
            <a href="{{ route('committee.management.competitions.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const modeGroup = document.getElementById('submission_mode_group');
        
        function toggleMode() {
            if (typeSelect.value === 'team') {
                modeGroup.style.display = 'block';
            } else {
                modeGroup.style.display = 'none';
            }
        }
        
        typeSelect.addEventListener('change', toggleMode);
        toggleMode();
    });
</script>
@endsection
