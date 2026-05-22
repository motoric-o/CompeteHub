@forelse($fields as $index => $field)
    <div class="form-group mb-4 p-4 bg-white border border-black rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
        <label class="form-label font-bold flex items-center justify-between">
            <span>
                {{ $field['label'] ?: '(Tanpa Label)' }}
                @if($field['required'] ?? false)
                    <span class="text-danger">*</span>
                @endif
            </span>
            <span class="text-xs text-muted-foreground uppercase font-mono bg-gray-100 px-2 py-0.5 rounded border border-gray-300">
                {{ $field['type'] }}
            </span>
        </label>

        @if(($field['type'] ?? 'text') === 'text')
            <input type="text" placeholder="Masukkan {{ $field['label'] }}" class="form-control" disabled />
        @elseif(($field['type'] ?? 'text') === 'email')
            <input type="email" placeholder="contoh@email.com" class="form-control" disabled />
        @elseif(($field['type'] ?? 'text') === 'number')
            <input type="number" placeholder="0" class="form-control" disabled />
        @elseif(($field['type'] ?? 'text') === 'textarea')
            <textarea placeholder="Masukkan {{ $field['label'] }}" class="form-control" rows="3" disabled></textarea>
        @elseif(($field['type'] ?? 'text') === 'date')
            <input type="date" class="form-control" disabled />
        @elseif(($field['type'] ?? 'file') === 'file')
            <div class="border border-dashed border-gray-400 p-4 rounded text-center bg-gray-50">
                <span class="text-sm text-gray-500">Pilih berkas untuk diunggah</span>
            </div>
        @elseif(($field['type'] ?? 'select') === 'select')
            <select class="form-control" disabled>
                <option value="">-- Pilih --</option>
                @forelse($field['options'] ?? [] as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @empty
                    <option value="" disabled>(Belum ada opsi)</option>
                @endforelse
            </select>
        @elseif(($field['type'] ?? 'checkbox') === 'checkbox')
            <div class="flex items-center gap-2">
                <input type="checkbox" class="rounded border-gray-300 mr-2" disabled />
                <span class="text-sm text-gray-700">{{ $field['label'] }}</span>
            </div>
        @endif
    </div>
@empty
    <div class="text-center py-8 text-gray-500">
        Belum ada field ditambahkan. Form kosong.
    </div>
@endforelse
