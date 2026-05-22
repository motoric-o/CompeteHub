<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('committee.registrations.index', $competition) }}"
                   class="text-sm text-muted-foreground hover:underline">← Semua Pendaftar</a>
                <h2 class="page-title mt-1">
                    Review: {{ $registration->user?->name ?? $registration->team?->name ?? 'Unknown' }}
                </h2>
                <p class="text-sm text-muted-foreground">{{ $competition->name }}</p>
            </div>
            <a href="{{ route('committee.command-center.show', $competition) }}"
               class="btn btn-secondary text-sm">⚡ Command Center</a>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">

        {{-- ── SESSION MESSAGES ─────────────────────────────────────────── --}}
        @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-lg" style="background: rgba(34,197,94,0.05); border: 1px solid rgba(34,197,94,0.3);">
            <span class="text-lg">✅</span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 p-4 rounded-lg" style="background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.3);">
            <span class="text-lg">❌</span>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
        @endif

        {{-- ── ONE-CLICK REVIEW ACTIONS (Feature 7 & 8) ────────────────── --}}
        @if(!in_array($registration->status, ['verified', 'rejected']))
        <div class="card" style="border: 2px solid var(--border);">
            <h3 class="text-base font-bold mb-4">⚡ Tindakan Cepat</h3>
            <div class="flex flex-wrap gap-3">

                {{-- Run CoR Validation --}}
                <form method="POST" action="{{ route('committee.registrations.validate', [$competition, $registration]) }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary text-sm">
                        🔍 Jalankan Validasi CoR
                    </button>
                </form>

                {{-- Approve Final (only if payment_ok) --}}
                @if($registration->status === 'payment_ok')
                <form method="POST" action="{{ route('committee.registrations.approve', [$competition, $registration]) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary text-sm"
                            onclick="return confirm('Setujui registrasi ini sebagai VERIFIED (approved final)?')">
                        ✅ Setujui (Final)
                    </button>
                </form>
                @endif

                {{-- Send Reminder --}}
                <button type="button" onclick="document.getElementById('reminder-modal').classList.remove('hidden')"
                        class="btn btn-secondary text-sm">
                    📬 Kirim Reminder
                </button>

                {{-- Reject --}}
                <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                        class="btn btn-secondary text-sm" style="color: var(--destructive); border-color: rgba(239,68,68,0.3);">
                    🚫 Tolak Registrasi
                </button>

            </div>
        </div>
        @endif

        {{-- ── STATUS CARD ──────────────────────────────────────────────── --}}
        <div class="card">
            <h3 class="text-base font-bold mb-3">Status Registrasi</h3>
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-lg px-4 py-2 rounded-full font-bold text-sm" style="
                    background: {{ match($registration->status) {
                        'verified'     => 'rgba(34,197,94,0.1)',
                        'rejected'     => 'rgba(239,68,68,0.1)',
                        'payment_ok'   => 'rgba(59,130,246,0.1)',
                        'documents_ok' => 'rgba(139,92,246,0.1)',
                        default        => 'rgba(245,158,11,0.1)',
                    } }};
                    color: {{ match($registration->status) {
                        'verified'     => 'var(--success)',
                        'rejected'     => 'var(--destructive)',
                        'payment_ok'   => '#3b82f6',
                        'documents_ok' => '#8b5cf6',
                        default        => '#f59e0b',
                    } }};
                    border: 1px solid currentColor;
                ">
                    {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                </span>
                <span class="text-sm text-muted-foreground">
                    Mendaftar {{ $registration->created_at->diffForHumans() }}
                    ({{ $registration->created_at->format('d M Y, H:i') }})
                </span>
            </div>

            @if($registration->rejection_reason)
            <div class="mt-3 p-3 rounded-lg text-sm" style="background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.2);">
                <strong>Alasan Penolakan:</strong> {{ $registration->rejection_reason }}
            </div>
            @endif
        </div>

        {{-- ── FORM ANSWERS ─────────────────────────────────────────────── --}}
        <div class="card">
            <h3 class="text-base font-bold mb-3">Jawaban Form</h3>
            @if($registration->form_data && count($registration->form_data) > 0)
            <div class="space-y-2">
                @foreach($registration->form_data as $label => $answer)
                <div class="flex gap-3 py-2" style="border-bottom: 1px solid var(--border);">
                    <span class="text-sm font-semibold text-muted-foreground min-w-32">{{ $label }}</span>
                    <span class="text-sm">
                        @if(is_array($answer))
                            {{ implode(', ', $answer) }}
                        @elseif($answer === '1' || $answer === 1 || $answer === true)
                            ✅ Ya
                        @else
                            {{ $answer ?: '—' }}
                        @endif
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-muted-foreground">Tidak ada jawaban form.</p>
            @endif
        </div>

        {{-- ── DOCUMENTS ────────────────────────────────────────────────── --}}
        <div class="card">
            <h3 class="text-base font-bold mb-3">Dokumen</h3>
            @forelse($registration->documents as $doc)
            <div class="flex items-center justify-between py-3" style="border-bottom: 1px solid var(--border);">
                <div>
                    <span class="text-sm font-semibold">{{ $doc->document_type }}</span>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                       class="ml-3 text-xs font-medium hover:underline" style="color: var(--primary);">
                        Lihat File →
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-2 py-1 rounded-full font-semibold"
                          style="background: {{ $doc->status === 'verified' ? 'rgba(34,197,94,0.1)' : ($doc->status === 'rejected' ? 'rgba(239,68,68,0.1)' : 'rgba(245,158,11,0.1)') }};
                                 color: {{ $doc->status === 'verified' ? 'var(--success)' : ($doc->status === 'rejected' ? 'var(--destructive)' : '#f59e0b') }};">
                        {{ ucfirst($doc->status) }}
                    </span>
                    @if($doc->status !== 'verified')
                    <form method="POST" action="{{ route('committee.documents.verify', $doc) }}" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="verified">
                        <button type="submit" class="text-xs font-semibold hover:underline" style="color: var(--success);">Approve</button>
                    </form>
                    @endif
                    @if($doc->status !== 'rejected')
                    <form method="POST" action="{{ route('committee.documents.verify', $doc) }}" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="text-xs font-semibold hover:underline" style="color: var(--destructive);">Reject</button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-muted-foreground">Tidak ada dokumen yang diupload.</p>
            @endforelse
        </div>

        {{-- ── PAYMENT ──────────────────────────────────────────────────── --}}
        <div class="card">
            <h3 class="text-base font-bold mb-3">Pembayaran</h3>
            @if($registration->payment)
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-sm">
                        Nominal: <strong>Rp {{ number_format($registration->payment->amount, 0, ',', '.') }}</strong>
                    </div>
                    <div class="text-sm">
                        Status: <strong>{{ ucfirst(str_replace('_', ' ', $registration->payment->status)) }}</strong>
                    </div>
                    @if($registration->payment->proof_path)
                    <a href="{{ asset('storage/' . $registration->payment->proof_path) }}" target="_blank"
                       class="text-sm font-medium hover:underline" style="color: var(--primary);">
                        Lihat Bukti Pembayaran →
                    </a>
                    @endif
                </div>
                @if($registration->payment->status === 'pending_verification')
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('committee.payments.verify', $registration->payment) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="paid">
                        <button type="submit" class="btn btn-primary text-xs">✅ Verifikasi</button>
                    </form>
                    <form method="POST" action="{{ route('committee.payments.verify', $registration->payment) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="unpaid">
                        <button type="submit" class="btn btn-secondary text-xs" style="color: var(--destructive);">❌ Tolak</button>
                    </form>
                </div>
                @endif
            </div>
            @else
            <p class="text-sm text-muted-foreground">Tidak ada data pembayaran.</p>
            @endif
        </div>

    </div>

    {{-- ── REJECT MODAL ─────────────────────────────────────────────── --}}
    <div id="reject-modal" class="hidden" style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:100; display:flex; align-items:center; justify-content:center;">
        <div class="card" style="max-width: 480px; width: 90%;">
            <h3 class="text-lg font-bold mb-2">Tolak Registrasi</h3>
            <p class="text-sm text-muted-foreground mb-4">
                Berikan alasan penolakan yang jelas. Peserta akan melihat alasan ini.
            </p>
            <form method="POST" action="{{ route('committee.registrations.reject', [$competition, $registration]) }}">
                @csrf
                <textarea name="reason" rows="3" required minlength="10" maxlength="500" placeholder="Contoh: Dokumen KTP tidak terbaca, foto buram..."
                    style="width:100%; padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--card); color: var(--foreground); font-size: 0.875rem; margin-bottom: 12px; resize: vertical;"></textarea>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')"
                            class="btn btn-secondary flex-1">Batal</button>
                    <button type="submit" class="btn btn-primary flex-1" style="background: var(--destructive);">Tolak Registrasi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── REMINDER MODAL ───────────────────────────────────────────── --}}
    <div id="reminder-modal" class="hidden" style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:100; display:flex; align-items:center; justify-content:center;">
        <div class="card" style="max-width: 480px; width: 90%;">
            <h3 class="text-lg font-bold mb-2">Kirim Reminder</h3>
            <p class="text-sm text-muted-foreground mb-4">
                Kirim pesan pengingat kepada peserta.
                Penerima: <strong>{{ $registration->user?->email ?? $registration->team?->captain?->email ?? '—' }}</strong>
            </p>
            <form method="POST" action="{{ route('committee.registrations.reminder', [$competition, $registration]) }}">
                @csrf
                <textarea name="message" rows="3" required minlength="10" maxlength="1000"
                    placeholder="Contoh: Mohon segera melengkapi dokumen yang diperlukan sebelum tanggal 30 Mei 2025..."
                    style="width:100%; padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--card); color: var(--foreground); font-size: 0.875rem; margin-bottom: 12px; resize: vertical;"></textarea>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('reminder-modal').classList.add('hidden')"
                            class="btn btn-secondary flex-1">Batal</button>
                    <button type="submit" class="btn btn-primary flex-1">📬 Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Close modals when clicking backdrop
        ['reject-modal', 'reminder-modal'].forEach(id => {
            document.getElementById(id)?.addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });
        });
    </script>

</x-app-layout>
