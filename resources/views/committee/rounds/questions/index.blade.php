@extends('layouts.app')

@section('title', 'Kelola Soal - ' . $round->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('committee.rounds.index', $competition) }}" class="btn btn-outline btn-sm mb-4">
            ← Kembali ke Daftar Babak
        </a>
        <h1 class="page-title">Kelola Soal: {{ $round->name }}</h1>
        <p class="page-subtitle">Kompetisi: {{ $competition->name }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in">
    <!-- Questions List -->
    <div class="lg:col-span-2 space-y-4">
        <h2 class="text-xl font-bold mb-2">Daftar Soal</h2>
        
        @if(session('success'))
            <div class="alert alert-success p-3 rounded bg-green-100 text-green-800 border border-green-300 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @forelse($questions as $index => $q)
            <div class="card relative p-5" style="border-left: 6px solid var(--color-primary);">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="badge badge-primary mb-2" style="background-color: var(--primary); color: var(--primary-foreground); font-weight: bold; padding: 0.25rem 0.5rem; border-radius: 4px; border: 1px solid var(--border);">
                            Soal #{{ $index + 1 }} — {{ $q->question_type === 'multiple_choice' ? 'Pilihan Ganda' : 'Esai' }} ({{ $q->points }} Poin)
                        </span>
                        <p class="font-bold text-lg mt-2 mb-3">{{ $q->question_text }}</p>

                        @if($q->question_type === 'multiple_choice' && is_array($q->options))
                            <ul class="space-y-1 pl-4 list-disc mb-3">
                                @foreach($q->options as $optIndex => $option)
                                    <li class="{{ $q->correct_answer == $optIndex || $q->correct_answer == $option ? 'text-green-600 font-bold' : '' }}">
                                        {{ $option }} 
                                        @if($q->correct_answer == $optIndex || $q->correct_answer == $option)
                                            <span class="text-xs bg-green-100 px-1 rounded ml-1">✓ Jawaban Benar</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if($q->question_type === 'essay')
                            <p class="text-sm text-gray-500 italic mb-2">Kunci Jawaban / Kisi-kisi: {{ $q->correct_answer ?? '-' }}</p>
                        @endif
                    </div>
                    
                    <div class="flex gap-2">
                        <!-- Delete Form -->
                        <form action="{{ route('committee.rounds.questions.destroy', [$competition, $round, $q]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" style="background-color: var(--destructive); color: var(--destructive-foreground); border: 1px solid var(--border);">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="card text-center py-8 text-gray-500 border-dashed">
                Belum ada soal yang ditambahkan pada ronde ini. Silakan buat soal menggunakan form di sebelah kanan.
            </div>
        @endforelse
    </div>

    <!-- Create Question Form -->
    <div class="card h-fit">
        <h3 class="font-bold mb-4 text-lg border-b pb-2">Tambah Soal Baru</h3>
        
        @if ($errors->any())
            <div class="alert alert-danger p-3 bg-red-100 text-red-800 rounded border border-red-300 mb-4">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('committee.rounds.questions.store', [$competition, $round]) }}" method="POST">
            @csrf
            <div class="form-group mb-4">
                <label for="question_text" class="form-label font-bold mb-1 block">Pertanyaan</label>
                <textarea name="question_text" id="question_text" class="form-control w-full" rows="3" required placeholder="Tulis soal pertanyaan di sini...">{{ old('question_text') }}</textarea>
            </div>

            <div class="form-group mb-4">
                <label for="question_type" class="form-label font-bold mb-1 block">Tipe Pertanyaan</label>
                <select name="question_type" id="question_type" class="form-control w-full" required>
                    <option value="multiple_choice" {{ old('question_type') === 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda (MCQ)</option>
                    <option value="essay" {{ old('question_type') === 'essay' ? 'selected' : '' }}>Esai / Jawaban Singkat</option>
                </select>
            </div>

            <!-- Options (Only for Multiple Choice) -->
            <div id="options_container" class="form-group mb-4">
                <label for="options_input" class="form-label font-bold mb-1 block">Pilihan Jawaban (Satu opsi per baris)</label>
                <textarea name="options_input" id="options_input" class="form-control w-full" rows="4" placeholder="Opsi A&#10;Opsi B&#10;Opsi C&#10;Opsi D"></textarea>
                <span class="text-xs text-gray-500 block mt-1">Gunakan baris baru untuk memisahkan opsi pilihan ganda.</span>
            </div>

            <div class="form-group mb-4">
                <label for="correct_answer" class="form-label font-bold mb-1 block">Jawaban Benar</label>
                <input type="text" name="correct_answer" id="correct_answer" class="form-control w-full" value="{{ old('correct_answer') }}" placeholder="Contoh: Opsi A, atau ketik 0 untuk opsi baris pertama">
                <span class="text-xs text-gray-500 block mt-1">Bisa berupa teks opsi persis, atau index pilihan (0 untuk baris ke-1, 1 untuk baris ke-2, dst).</span>
            </div>

            <div class="form-group mb-4">
                <label for="points" class="form-label font-bold mb-1 block">Bobot Poin</label>
                <input type="number" name="points" id="points" class="form-control w-full" value="{{ old('points', 10) }}" min="1" required>
            </div>

            <button type="submit" class="btn btn-primary w-full justify-center mt-2">Tambah Pertanyaan</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('question_type');
        const optionsContainer = document.getElementById('options_container');
        const optionsInput = document.getElementById('options_input');
        
        function toggleOptions() {
            if (typeSelect.value === 'multiple_choice') {
                optionsContainer.style.display = 'block';
                optionsInput.setAttribute('required', 'required');
            } else {
                optionsContainer.style.display = 'none';
                optionsInput.removeAttribute('required');
            }
        }

        // Process options input text area into array format before form submission
        const form = typeSelect.closest('form');
        form.addEventListener('submit', function(e) {
            if (typeSelect.value === 'multiple_choice') {
                const text = optionsInput.value.trim();
                if (text) {
                    const lines = text.split('\n').map(line => line.trim()).filter(line => line.length > 0);
                    
                    // Create hidden inputs for options array
                    lines.forEach((line, index) => {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = `options[${index}]`;
                        hiddenInput.value = line;
                        form.appendChild(hiddenInput);
                    });
                }
            }
        });

        typeSelect.addEventListener('change', toggleOptions);
        toggleOptions();
    });
</script>
@endsection
