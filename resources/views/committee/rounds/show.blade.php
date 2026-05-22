@extends('layouts.app')

@section('title', ($round->is_bracket ? 'Bagan - ' : 'Detail Babak - ') . $round->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('committee.rounds.index', $competition) }}" class="btn btn-outline btn-sm mb-4">
            ← Kembali ke Daftar Babak
        </a>
        <h1 class="page-title">{{ $round->is_bracket ? 'Bagan:' : 'Detail Babak:' }} {{ $round->name }}</h1>
        <p class="page-subtitle">Kompetisi: {{ $competition->name }}</p>
    </div>
    @if($round->is_bracket)
    <div class="flex gap-2">
        <form action="{{ route('committee.rounds.brackets.auto-generate', [$competition, $round]) }}" method="POST" onsubmit="return confirm('Peringatan: Membuat bagan otomatis akan menghapus bagan yang sudah ada di babak ini. Lanjutkan?');">
            @csrf
            <button type="submit" class="btn btn-primary">⚡ Auto Generate Bagan</button>
        </form>
    </div>
    @endif
</div>

@if($round->is_bracket)
<div class="section grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        <h2 class="text-xl font-bold mb-4">Daftar Pertandingan</h2>
        
        @if(session('success'))
            <div class="alert alert-success p-3 rounded bg-green-100 text-green-800 mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger p-3 rounded bg-red-100 text-red-800 mb-4">
                {{ session('error') }}
            </div>
        @endif

        @forelse($round->brackets as $bracket)
            <div class="card relative p-0 overflow-hidden" style="border-left: 4px solid var(--color-primary);">
                <div class="p-4 grid grid-cols-1 md:grid-cols-3 items-center gap-4">
                    <!-- Participant A -->
                    <div class="text-center md:text-right font-semibold {{ $bracket->winner_id && $bracket->winner_id == $bracket->participant_a ? 'text-green-600' : '' }}">
                        {{ $bracket->getParticipantAName() }}
                        @if($bracket->winner_id == $bracket->participant_a)
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded ml-2">Winner</span>
                        @endif
                        @if($bracket->participant_a && isset($submissions[$bracket->participant_a]))
                            <div class="text-xs text-gray-500 font-normal mt-1">
                                Score: {{ floatval($submissions[$bracket->participant_a]->total_score) }} pts
                            </div>
                        @elseif($bracket->participant_a)
                            <div class="text-xs text-gray-400 font-normal mt-1 italic">
                                Belum ada submisi
                            </div>
                        @endif
                    </div>
                    
                    <!-- VS -->
                    <div class="text-center">
                        <span class="text-gray-400 font-bold text-sm bg-gray-100 px-3 py-1 rounded-full">VS</span>
                    </div>

                    <!-- Participant B -->
                    <div class="text-center md:text-left font-semibold {{ $bracket->winner_id && $bracket->winner_id == $bracket->participant_b ? 'text-green-600' : '' }}">
                        {{ $bracket->getParticipantBName() }}
                        @if($bracket->winner_id == $bracket->participant_b)
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded mr-2">Winner</span>
                        @endif
                        @if(!$bracket->participant_b)
                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">BYE</span>
                        @endif
                        @if($bracket->participant_b && isset($submissions[$bracket->participant_b]))
                            <div class="text-xs text-gray-500 font-normal mt-1">
                                Score: {{ floatval($submissions[$bracket->participant_b]->total_score) }} pts
                            </div>
                        @elseif($bracket->participant_b)
                            <div class="text-xs text-gray-400 font-normal mt-1 italic">
                                Belum ada submisi
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 border-t border-gray-200 p-3 flex justify-between items-center">
                    <div class="text-xs text-gray-500">
                        Match ID: #{{ $bracket->id }}
                    </div>
                    <div class="flex gap-2">
                        @if(!$bracket->winner_id && $bracket->participant_a && $bracket->participant_b)
                            <!-- Set Winner -->
                            <form action="{{ route('committee.rounds.brackets.winner', [$competition, $round, $bracket]) }}" method="POST" class="flex gap-1">
                                @csrf
                                <select name="winner_id" class="form-input text-xs py-1" required>
                                    <option value="">-- Pilih Pemenang --</option>
                                    <option value="{{ $bracket->participant_a }}">{{ $bracket->getParticipantAName() }}</option>
                                    <option value="{{ $bracket->participant_b }}">{{ $bracket->getParticipantBName() }}</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary py-1 px-2 text-xs">Set</button>
                            </form>
                        @endif
                        <form action="{{ route('committee.rounds.brackets.destroy', [$competition, $round, $bracket]) }}" method="POST" onsubmit="return confirm('Hapus pertandingan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline py-1 px-2 text-xs text-red-500 border-red-200">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="card text-center py-8 text-gray-500 border-dashed">
                Belum ada bagan pertandingan di babak ini. Gunakan fitur "Auto Generate" atau tambahkan secara manual.
            </div>
        @endforelse
    </div>

    <!-- Manual Add Form -->
    <div class="card h-fit sticky top-4">
        <h3 class="font-bold mb-4 text-lg border-b pb-2">Tambah Manual</h3>
        <form action="{{ route('committee.rounds.brackets.store', [$competition, $round]) }}" method="POST">
            @csrf
            <div class="form-group mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Peserta 1</label>
                <select name="participant_a" class="form-input w-full" required>
                    <option value="">-- Pilih Peserta --</option>
                    @foreach($participants as $participant)
                        <option value="{{ $participant->id }}">{{ $participant->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Peserta 2 (Kosongkan jika BYE)</label>
                <select name="participant_b" class="form-input w-full">
                    <option value="">-- Tidak Ada / BYE --</option>
                    @foreach($participants as $participant)
                        <option value="{{ $participant->id }}">{{ $participant->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-full justify-center">Tambah Pertandingan</button>
        </form>
    </div>
</div>
@else
<div class="section">
    @if(session('success'))
        <div class="alert alert-success p-3 rounded bg-green-100 text-green-800 mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger p-3 rounded bg-red-100 text-red-800 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="card p-0">
        <table class="table w-full">
            <thead>
                <tr>
                    <th class="w-16">No</th>
                    <th>Nama Peserta</th>
                    <th>Status Submisi</th>
                    <th>Lampiran Submisi</th>
                    <th>Waktu Kirim</th>
                    <th class="text-right">Skor Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants as $idx => $participant)
                    @php
                        $sub = $submissions[$participant->id] ?? null;
                    @endphp
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td class="font-bold">{{ $participant->name }}</td>
                        <td>
                            @if($sub)
                                <span class="badge {{ $sub->status === 'scored' ? 'badge-primary' : 'badge-secondary' }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            @else
                                <span class="badge" style="background: #fee2e2; color: #991b1b;">Belum Mengirim</span>
                            @endif
                        </td>
                        <td>
                            @if($sub)
                                @if($sub->file_path)
                                    <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="text-primary hover:underline font-medium">
                                        📁 Lihat File ({{ strtoupper($sub->file_type ?? 'file') }})
                                    </a>
                                @elseif($sub->submission_url)
                                    <a href="{{ $sub->submission_url }}" target="_blank" class="text-primary hover:underline font-medium">
                                        🔗 Link Submisi
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td>
                            {{ $sub ? $sub->submitted_at->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="text-right font-black text-lg">
                            {{ $sub && $sub->final_score !== null ? floatval($sub->final_score) . ' pts' : 'Belum Dinilai' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-muted">Belum ada peserta terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

<style>
    .form-input {
        padding: 0.5rem;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        background: var(--color-bg-primary);
        color: var(--color-text);
    }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 1rem; border-bottom: 1px solid var(--color-border); text-align: left; }
    .table th { background: var(--color-bg-elevated); font-weight: 600; color: var(--color-text-muted); }
    .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .badge-primary { background: var(--color-primary-light); color: var(--color-primary-dark); }
    .badge-secondary { background: var(--color-border); color: var(--color-text-muted); }
</style>
@endsection
