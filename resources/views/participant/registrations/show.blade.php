<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">{{ __('Detail Registrasi') }}</h2>
                <p class="text-sm text-muted-foreground mt-1">{{ $competition->name }}</p>
            </div>
            <a href="{{ route('participant.registrations.index') }}" class="btn btn-secondary text-sm">
                ← Kembali ke Pendaftaranku
            </a>
        </div>
    </x-slot>

    @php
        $formTemplate = $competition->formTemplates()->latest()->first();

        $requiredDocuments = collect($formTemplate?->fields ?? [])
            ->filter(fn ($field) => ($field['type'] ?? null) === 'file' && ($field['required'] ?? false))
            ->values();

        $documentsByType = $registration->documents->keyBy('document_type');

        $statusBadge = match ($registration->status) {
            'payment_ok', 'verified' => 'bg-green-100 text-green-700 border-green-200',
            'rejected' => 'bg-red-100 text-red-700 border-red-200',
            'documents_ok' => 'bg-blue-100 text-blue-700 border-blue-200',
            'account_ok' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            default => 'bg-yellow-100 text-yellow-700 border-yellow-200',
        };

        $paymentStatus = $registration->payment?->status ?? 'missing';

        $paymentBadge = match ($paymentStatus) {
            'paid', 'free' => 'bg-green-100 text-green-700 border-green-200',
            'unpaid' => 'bg-red-100 text-red-700 border-red-200',
            'pending_verification' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            default => 'bg-gray-100 text-gray-600 border-gray-200',
        };

        $paymentText = match ($paymentStatus) {
            'paid' => 'Paid',
            'free' => 'Free',
            'unpaid' => 'Unpaid',
            'pending_verification' => 'Pending Verification',
            default => 'No Payment Record',
        };

        // Konfigurasi warna Next Action Card dari branch workflow-intelligence
        $bgColors = [
            'success' => 'rgba(34,197,94,0.1)',
            'warning' => 'rgba(245,158,11,0.1)',
            'error'   => 'rgba(239,68,68,0.1)',
            'info'    => 'rgba(59,130,246,0.1)',
            'neutral' => 'var(--muted)',
        ];
        $borderColors = [
            'success' => 'var(--success)',
            'warning' => '#f59e0b',
            'error'   => 'var(--destructive)',
            'info'    => '#3b82f6',
            'neutral' => 'var(--border)',
        ];
        $textColors = [
            'success' => 'var(--success)',
            'warning' => '#d97706',
            'error'   => 'var(--destructive)',
            'info'    => '#2563eb',
            'neutral' => 'var(--foreground)',
        ];
        $severity = $nextAction->severity ?? 'info';
        $bg = $bgColors[$severity] ?? $bgColors['info'];
        $borderColor = $borderColors[$severity] ?? $borderColors['info'];
        $textColor = $textColors[$severity] ?? $textColors['info'];
    @endphp

    <div class="py-6 space-y-6">

        {{-- ── NEXT ACTION CARD (Feature 2) ────────────────────────────── --}}
        <div class="card animate-in" style="
            background: {{ $bg }};
            border: 2px solid {{ $borderColor }};
            box-shadow: 4px 4px 0px 0px {{ $borderColor }};
        ">
            <div class="flex items-start gap-4">
                <div class="text-4xl flex-shrink-0" style="padding-top: 2px;">
                    {{ $nextAction->icon }}
                </div>
                <div class="space-y-2 flex-1">
                    <h3 class="text-xl font-black" style="color: {{ $textColor }};">
                        {{ $nextAction->title }}
                    </h3>
                    <p class="text-sm font-medium" style="color: var(--foreground); line-height: 1.5;">
                        {{ $nextAction->description }}
                    </p>

                    @if($nextAction->deadlineNote)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(239,68,68,0.1); color: var(--destructive); border: 1px solid rgba(239,68,68,0.3);">
                            {{ $nextAction->deadlineNote }}
                        </div>
                    @endif

                    @if($nextAction->isActionable && $nextAction->actionUrl)
                        <div class="pt-2">
                            <a href="{{ $nextAction->actionUrl }}" class="btn btn-primary text-sm">
                                {{ $nextAction->actionLabel }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── TIMELINE PROGRESS (Feature 2) ───────────────────────────── --}}
        <div class="card">
            <h4 class="font-bold text-sm text-muted-foreground uppercase tracking-wider mb-4">Langkah Registrasi</h4>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                @foreach($nextAction->progressSteps as $index => $step)
                    <div class="flex-1 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center font-bold text-sm flex-shrink-0" style="
                            background: {{ $step['status'] === 'done' ? 'var(--primary)' : ($step['status'] === 'current' ? 'var(--secondary)' : 'var(--card)') }};
                            border-color: var(--border);
                            box-shadow: 1px 1px 0px 0px var(--border);
                        ">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-sm font-bold m-0" style="
                                color: {{ $step['status'] === 'pending' ? 'var(--muted-foreground)' : 'var(--foreground)' }};
                            ">
                                {{ $step['label'] }}
                            </p>
                            <span class="text-xs font-semibold uppercase tracking-widest" style="
                                color: {{ $step['status'] === 'done' ? 'var(--success)' : ($step['status'] === 'current' ? 'var(--secondary)' : 'var(--muted-foreground)') }};
                            ">
                                {{ $step['status'] === 'done' ? 'Selesai' : ($step['status'] === 'current' ? 'Aktif' : 'Menunggu') }}
                            </span>
                        </div>
                    </div>
                    @if(!$loop->last)
                        <div class="hidden md:block w-8 h-0.5" style="background: var(--border);"></div>
                    @endif
                @endforeach
            </div>
        </div>

        @if($registration->status === 'rejected')
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="font-semibold">Registration Rejected</div>
                <p class="text-sm mt-1">{{ $registration->rejection_reason ?? 'No reason provided.' }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── LEFT COLUMN: DETAIL & DOKUMEN ─────────────────────────── --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Form Answers --}}
                <div class="card">
                    <h3 class="text-lg font-bold mb-4">📄 Data Formulir Pendaftaran</h3>
                    @if($registration->form_data && count($registration->form_data) > 0)
                        <div class="space-y-3">
                            @foreach($registration->form_data as $label => $answer)
                                <div class="flex flex-col sm:flex-row sm:items-center py-2 border-b border-dashed border-border/50">
                                    <span class="text-sm font-semibold text-muted-foreground sm:w-1/3">{{ $label }}</span>
                                    <span class="text-sm font-medium sm:w-2/3 mt-1 sm:mt-0">
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
                        <p class="text-sm text-muted-foreground">Tidak ada data formulir pendaftaran.</p>
                    @endif
                </div>

                {{-- Documents Checklist section --}}
                <div class="card" id="documents-section">
                    <h3 class="text-lg font-bold mb-4">📂 Dokumen Persyaratan</h3>
                    <div class="space-y-4">
                        @forelse($requiredDocuments as $field)
                            @php
                                $label = $field['label'] ?? 'Document';
                                $doc = $documentsByType->get($label);
                                $docStatus = $doc?->status ?? 'missing';

                                $docBadgeStyle = match ($docStatus) {
                                    'verified' => 'background: rgba(34,197,94,0.1); color: var(--success);',
                                    'rejected' => 'background: rgba(239,68,68,0.1); color: var(--destructive);',
                                    'pending' => 'background: rgba(245,158,11,0.1); color: #f59e0b;',
                                    default => 'background: rgba(156,163,175,0.1); color: #6b7280;',
                                };
                            @endphp

                            <div class="p-4 rounded-lg border border-border bg-muted/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <div class="font-bold text-sm">{{ $label }}</div>
                                    <div class="text-xs text-muted-foreground mt-0.5">
                                        @if($doc)
                                            Uploaded {{ $doc->created_at?->diffForHumans() ?? '-' }}
                                        @else
                                            Dokumen wajib pendaftaran belum diupload.
                                        @endif
                                    </div>
                                    @if($doc)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                               class="text-xs font-bold hover:underline" style="color: var(--secondary);">
                                                Lihat File Saat Ini →
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    <span class="text-xs px-2.5 py-1 rounded-full font-bold border" style="{{ $docBadgeStyle }}">
                                        {{ $docStatus === 'verified' ? 'Diverifikasi' : ($docStatus === 'rejected' ? 'Ditolak' : ($docStatus === 'pending' ? 'Menunggu Review' : 'Missing')) }}
                                    </span>

                                    {{-- Form Reupload if Rejected --}}
                                    @if($doc && $doc->status === 'rejected')
                                        <form method="POST" action="{{ route('participant.registrations.reupload-document', [$competition, $registration]) }}"
                                              class="mt-2 flex items-center gap-2" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="document_type" value="{{ $doc->document_type }}">
                                            <input type="file" name="file" required class="text-xs" style="max-width: 150px;">
                                            <button type="submit" class="btn btn-secondary btn-sm">
                                                Upload Ulang
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">Kompetisi ini tidak membutuhkan dokumen persyaratan khusus.</p>
                        @endforelse

                        {{-- Render other uploaded documents non-required --}}
                        @foreach($registration->documents->whereNotIn('document_type', $requiredDocuments->pluck('label')) as $doc)
                            <div class="p-4 rounded-lg border border-border bg-muted/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <div class="font-bold text-sm">{{ $doc->document_type }}</div>
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-xs font-bold hover:underline" style="color: var(--secondary);">
                                        Lihat File →
                                    </a>
                                </div>
                                <span class="text-xs px-2.5 py-1 rounded-full font-bold border"
                                      style="background: {{ $doc->status === 'verified' ? 'rgba(34,197,94,0.1)' : ($doc->status === 'rejected' ? 'rgba(239,68,68,0.1)' : 'rgba(245,158,11,0.1)') }};
                                             color: {{ $doc->status === 'verified' ? 'var(--success)' : ($doc->status === 'rejected' ? 'var(--destructive)' : '#f59e0b') }};
                                             border: 1px solid currentColor;">
                                    {{ ucfirst($doc->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ── RIGHT COLUMN: PEMBAYARAN & AKSES ─────────────────────────── --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Payment section --}}
                <div class="card" id="payment-section">
                    <h3 class="text-lg font-bold mb-4">💳 Rincian Pembayaran</h3>
                    @if($registration->payment)
                        <div class="space-y-4">
                            <div class="p-4 rounded-lg border border-border" style="background: var(--muted);">
                                <div class="text-xs text-muted-foreground font-semibold uppercase tracking-wider">Total Biaya</div>
                                <div class="text-2xl font-black mt-1">
                                    Rp {{ number_format($registration->payment->amount, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-muted-foreground mt-1">
                                    Status: 
                                    <span class="font-bold text-sm inline-block ml-1" style="
                                        color: {{ match($registration->payment->status) {
                                            'paid' => 'var(--success)',
                                            'pending_verification' => '#f59e0b',
                                            'free' => 'var(--secondary)',
                                            default => 'var(--destructive)',
                                        } }}
                                    ">
                                        {{ match($registration->payment->status) {
                                            'paid' => 'Lunas',
                                            'pending_verification' => 'Verifikasi Pending',
                                            'free' => 'Gratis',
                                            'unpaid' => 'Belum Bayar',
                                            default => $registration->payment->status,
                                        } }}
                                    </span>
                                </div>
                            </div>

                            @if($competition->registration_fee > 0 && in_array($registration->payment->status, ['unpaid', 'pending_verification']))
                                {{-- Rekening Panitia --}}
                                <div class="p-3 rounded-lg border border-dashed border-border text-xs space-y-1">
                                    <div class="font-bold">Instruksi Transfer:</div>
                                    <div>Bank Mandiri: <strong>123-456789-001</strong></div>
                                    <div>Atas Nama: <strong>CompeteHub Admin</strong></div>
                                    <div class="text-muted-foreground">Pastikan nominal transfer sesuai dan upload bukti di bawah.</div>
                                </div>

                                {{-- Proof Image Preview if exists --}}
                                @if($registration->payment->proof_path)
                                    <div>
                                        <div class="text-xs font-semibold text-muted-foreground mb-1">Bukti Transfer Saat Ini:</div>
                                        <a href="{{ asset('storage/' . $registration->payment->proof_path) }}" target="_blank" class="block border border-border rounded-lg overflow-hidden hover:opacity-90">
                                            <img src="{{ asset('storage/' . $registration->payment->proof_path) }}" alt="Bukti Pembayaran" style="max-height: 150px; width: 100%; object-fit: cover;">
                                        </a>
                                    </div>
                                @endif

                                {{-- Form Re-upload Proof --}}
                                <form method="POST" action="{{ route('participant.registrations.reupload-payment', [$competition, $registration]) }}"
                                      enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="form-label text-xs">Unggah Bukti Transfer Baru</label>
                                        <input type="file" name="payment_proof" required class="form-control text-sm">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-full text-sm">
                                        Kirim Bukti Pembayaran
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-muted-foreground">Kompetisi ini gratis.</p>
                    @endif
                </div>

                {{-- Competition Access --}}
                @if(in_array($registration->status, ['verified', 'payment_ok']))
                    <div class="card" style="border: 2px solid var(--secondary); box-shadow: 4px 4px 0px 0px var(--secondary);">
                        <h3 class="text-lg font-bold mb-2">🎉 Akses Kompetisi</h3>
                        <p class="text-xs text-muted-foreground mb-4">
                            Pendaftaran Anda sudah aman. Anda dapat mengunduh sertifikat partisipasi atau melihat daftar submisi tugas/lomba.
                        </p>
                        <div class="space-y-2">
                            <a href="{{ route('participant.submissions.index', $competition) }}" class="btn btn-secondary w-full text-sm">
                                🚀 Lihat Submisi Lomba
                            </a>
                            <a href="{{ route('participant.registrations.certificate', [$competition, $registration]) }}"
                               class="btn btn-outline w-full text-sm text-center" target="_blank" id="download-certificate-btn">
                                🏆 Unduh Sertifikat
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>
</x-app-layout>