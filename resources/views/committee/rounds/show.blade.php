@extends('layouts.app')

@section('title', 'Bagan - ' . $round->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('committee.rounds.index', $competition) }}" class="btn btn-outline btn-sm mb-4">
            ← Kembali ke Daftar Babak
        </a>
        <h1 class="page-title">Bagan: {{ $round->name }}</h1>
        <p class="page-subtitle">Kompetisi: {{ $competition->name }}</p>
    </div>
    <div class="flex gap-2">
        <form action="{{ route('committee.rounds.brackets.auto-generate', [$competition, $round]) }}" method="POST" onsubmit="return confirm('Peringatan: Membuat bagan otomatis akan menghapus bagan yang sudah ada di babak ini. Lanjutkan?');">
            @csrf
            <button type="submit" class="btn btn-primary">⚡ Auto Generate Bagan</button>
        </form>
    </div>
</div>

<div class="section grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        <h2 class="text-xl font-bold mb-4">Daftar Pertandingan</h2>
        
        @if(session('success'))
            <div class="alert alert-success p-3 rounded bg-green-100 text-green-800 mb-4">
                {{ session('success') }}
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
