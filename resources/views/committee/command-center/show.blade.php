<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">Command Center</h2>
                <p class="text-sm text-muted-foreground mt-1">{{ $competition->name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('committee.notification-log.index', $competition) }}" class="btn btn-secondary text-sm">
                    📋 Log Notifikasi
                </a>
                <a href="{{ route('committee.registrations.index', $competition) }}" class="btn btn-secondary text-sm">
                    👥 Semua Pendaftar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">

        {{-- ── READINESS SCORE ───────────────────────────────────────────── --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">Readiness Score</h3>
                <span class="text-3xl font-black" style="color: {{ $dashboard->readinessScore >= 80 ? 'var(--success)' : ($dashboard->readinessScore >= 50 ? 'var(--warning, #f59e0b)' : 'var(--destructive)') }}">
                    {{ $dashboard->readinessScore }}/100
                </span>
            </div>

            {{-- Progress Bar --}}
            <div style="height: 12px; background: var(--muted); border-radius: 6px; overflow: hidden; border: 1px solid var(--border);">
                <div style="height: 100%; width: {{ $dashboard->readinessScore }}%; background: {{ $dashboard->readinessScore >= 80 ? 'var(--success)' : ($dashboard->readinessScore >= 50 ? '#f59e0b' : 'var(--destructive)') }}; border-radius: 6px; transition: width 0.5s ease;"></div>
            </div>

            {{-- Breakdown --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                <div class="flex items-center gap-2 text-sm">
                    <span>{{ $dashboard->readinessBreakdown['has_form_template'] ? '✅' : '❌' }}</span>
                    <span class="text-muted-foreground">Form Template</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span>{{ $dashboard->readinessBreakdown['has_registrations'] ? '✅' : '⭕' }}</span>
                    <span class="text-muted-foreground">Ada Pendaftar</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span>{{ $dashboard->readinessBreakdown['pending_payments'] === 0 ? '✅' : '⚠️' }}</span>
                    <span class="text-muted-foreground">{{ $dashboard->readinessBreakdown['pending_payments'] }} Payment Pending</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span>{{ $dashboard->readinessBreakdown['pending_docs'] === 0 ? '✅' : '⚠️' }}</span>
                    <span class="text-muted-foreground">{{ $dashboard->readinessBreakdown['pending_docs'] }} Dokumen Pending</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span>🏆</span>
                    <span class="text-muted-foreground">{{ $dashboard->readinessBreakdown['verified_count'] }}/{{ $dashboard->readinessBreakdown['total_active'] }} Verified</span>
                </div>
                @if($dashboard->quota)
                <div class="flex items-center gap-2 text-sm">
                    <span>{{ $dashboard->quotaFillPercent >= 90 ? '🔴' : ($dashboard->quotaFillPercent >= 70 ? '🟡' : '🟢') }}</span>
                    <span class="text-muted-foreground">Kuota: {{ $dashboard->quotaFillPercent }}%</span>
                </div>
                @endif
            </div>
        </div>

        {{-- ── WARNINGS ──────────────────────────────────────────────────── --}}
        @if(count($dashboard->warnings) > 0)
        <div class="space-y-3">
            <h3 class="text-lg font-bold">⚠️ Perhatian</h3>
            @foreach($dashboard->warnings as $warning)
            <div class="flex items-start gap-3 p-4 rounded-lg border" style="
                background: {{ $warning['severity'] === 'error' ? 'rgba(239,68,68,0.05)' : 'rgba(245,158,11,0.05)' }};
                border-color: {{ $warning['severity'] === 'error' ? 'rgba(239,68,68,0.3)' : 'rgba(245,158,11,0.3)' }};
            ">
                <span class="text-xl flex-shrink-0">{{ $warning['severity'] === 'error' ? '🔴' : '🟡' }}</span>
                <p class="text-sm m-0">{{ $warning['message'] }}</p>
            </div>
            @endforeach
        </div>
        @endif

        {{-- ── STATS GRID ────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Pending Registrations --}}
            <div class="card text-center">
                <div class="text-3xl font-black mb-1" style="color: {{ $dashboard->newRegistrationsCount > 0 ? 'var(--warning, #f59e0b)' : 'var(--muted-foreground)' }}">
                    {{ $dashboard->newRegistrationsCount }}
                </div>
                <div class="text-xs text-muted-foreground font-medium">Pendaftaran Baru</div>
                @if($dashboard->newRegistrationsCount > 0)
                <div class="mt-2 text-xs font-bold" style="color: var(--warning, #f59e0b)">⏳ Perlu Proses</div>
                @endif
            </div>

            {{-- Pending Payments --}}
            <div class="card text-center">
                <div class="text-3xl font-black mb-1" style="color: {{ $dashboard->pendingPaymentsCount > 0 ? '#3b82f6' : 'var(--muted-foreground)' }}">
                    {{ $dashboard->pendingPaymentsCount }}
                </div>
                <div class="text-xs text-muted-foreground font-medium">Payment Pending</div>
                @if($dashboard->pendingPaymentsCount > 0)
                <div class="mt-2 text-xs font-bold" style="color: #3b82f6">💳 Verifikasi</div>
                @endif
            </div>

            {{-- Pending Docs --}}
            <div class="card text-center">
                <div class="text-3xl font-black mb-1" style="color: {{ $dashboard->pendingDocumentsCount > 0 ? '#8b5cf6' : 'var(--muted-foreground)' }}">
                    {{ $dashboard->pendingDocumentsCount }}
                </div>
                <div class="text-xs text-muted-foreground font-medium">Dokumen Pending</div>
                @if($dashboard->pendingDocumentsCount > 0)
                <div class="mt-2 text-xs font-bold" style="color: #8b5cf6">📄 Review</div>
                @endif
            </div>

            {{-- Overdue --}}
            <div class="card text-center">
                <div class="text-3xl font-black mb-1" style="color: {{ $dashboard->overdueCount > 0 ? 'var(--destructive)' : 'var(--muted-foreground)' }}">
                    {{ $dashboard->overdueCount }}
                </div>
                <div class="text-xs text-muted-foreground font-medium">Overdue</div>
                @if($dashboard->overdueCount > 0)
                <div class="mt-2 text-xs font-bold" style="color: var(--destructive)">🚨 Lewat Deadline</div>
                @endif
            </div>
        </div>

        {{-- ── NEW REGISTRATIONS ─────────────────────────────────────────── --}}
        @if($dashboard->newRegistrationsCount > 0)
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">⏳ Pendaftaran Baru ({{ $dashboard->newRegistrationsCount }})</h3>
                @if($dashboard->newRegistrationsCount > 1)
                <form method="POST" action="{{ route('committee.registrations.bulk-validate', $competition) }}"
                      id="bulk-validate-form">
                    @csrf
                    @foreach($dashboard->newRegistrations as $reg)
                    <input type="hidden" name="registration_ids[]" value="{{ $reg->id }}">
                    @endforeach
                    <button type="button" onclick="confirmBulkValidate()"
                            class="btn btn-primary text-sm">
                        ⚡ Validasi Semua ({{ $dashboard->newRegistrationsCount }})
                    </button>
                </form>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border);">
                            <th class="text-left py-2 px-3 text-sm font-semibold text-muted-foreground">Peserta</th>
                            <th class="text-left py-2 px-3 text-sm font-semibold text-muted-foreground">Daftar</th>
                            <th class="text-left py-2 px-3 text-sm font-semibold text-muted-foreground">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dashboard->newRegistrations as $reg)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td class="py-3 px-3">
                                <div class="font-semibold text-sm">
                                    {{ $reg->user?->name ?? $reg->team?->name ?? 'Unknown' }}
                                </div>
                                <div class="text-xs text-muted-foreground">
                                    {{ $reg->user?->email ?? ($reg->team ? 'Tim · ' . $reg->team->members->count() . ' anggota' : '') }}
                                </div>
                            </td>
                            <td class="py-3 px-3 text-sm text-muted-foreground">
                                {{ $reg->created_at->diffForHumans() }}
                            </td>
                            <td class="py-3 px-3">
                                <a href="{{ route('committee.registrations.show', [$competition, $reg]) }}"
                                   class="btn btn-secondary text-xs">
                                    Review →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- ── PENDING PAYMENTS ──────────────────────────────────────────── --}}
        @if($dashboard->pendingPaymentsCount > 0)
        <div class="card">
            <h3 class="text-lg font-bold mb-4">💳 Payment Menunggu Verifikasi ({{ $dashboard->pendingPaymentsCount }})</h3>
            <div class="space-y-2">
                @foreach($dashboard->pendingPayments as $reg)
                <div class="flex items-center justify-between py-2 px-3 rounded-lg" style="background: var(--muted); border: 1px solid var(--border);">
                    <div>
                        <span class="font-semibold text-sm">{{ $reg->user?->name ?? $reg->team?->name ?? 'Unknown' }}</span>
                        <span class="text-xs text-muted-foreground ml-2">
                            Rp {{ number_format($reg->payment->amount ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <a href="{{ route('committee.registrations.show', [$competition, $reg]) }}"
                       class="btn btn-secondary text-xs">
                        Verifikasi →
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── OVERDUE ───────────────────────────────────────────────────── --}}
        @if($dashboard->overdueCount > 0)
        <div class="card" style="border-color: rgba(239,68,68,0.3);">
            <h3 class="text-lg font-bold mb-4" style="color: var(--destructive);">🚨 Overdue ({{ $dashboard->overdueCount }})</h3>
            <p class="text-sm text-muted-foreground mb-3">Registrasi berikut melewati deadline tapi belum diverifikasi.</p>
            <div class="space-y-2">
                @foreach($dashboard->overdueRegistrations as $reg)
                <div class="flex items-center justify-between py-2 px-3 rounded-lg" style="background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.2);">
                    <div>
                        <span class="font-semibold text-sm">{{ $reg->user?->name ?? $reg->team?->name ?? 'Unknown' }}</span>
                        <span class="text-xs ml-2 px-2 py-0.5 rounded-full font-medium" style="background: var(--muted);">
                            {{ $reg->status }}
                        </span>
                    </div>
                    <a href="{{ route('committee.registrations.show', [$competition, $reg]) }}"
                       class="btn btn-secondary text-xs">
                        Tindak Lanjut →
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── ALL CLEAR ─────────────────────────────────────────────────── --}}
        @if($dashboard->newRegistrationsCount === 0 && $dashboard->pendingPaymentsCount === 0 && $dashboard->overdueCount === 0)
        <div class="card text-center py-8">
            <div class="text-4xl mb-3">✅</div>
            <h3 class="text-lg font-bold mb-1">Semua Beres!</h3>
            <p class="text-muted-foreground text-sm">Tidak ada tindakan mendesak yang diperlukan saat ini.</p>
        </div>
        @endif

    </div>

    {{-- Bulk Validate Confirmation Modal --}}
    <div id="bulk-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;"
        onclick="closeBulkModalOnBackdrop(event)">
        <div class="card" style="max-width: 400px; width: 90%;">
            <h3 class="text-lg font-bold mb-2">Konfirmasi Validasi Massal</h3>

            @if($dashboard->newRegistrationsCount > 0)
                <p class="text-sm text-muted-foreground mb-4">
                    Anda akan menjalankan validasi otomatis pada {{ $dashboard->newRegistrationsCount }} pendaftaran.
                    Sistem akan memeriksa kelengkapan dokumen, akun, dan pembayaran untuk setiap pendaftaran.
                </p>
            @else
                <p class="text-sm text-muted-foreground mb-4">
                    Tidak ada pendaftaran baru yang perlu divalidasi saat ini.
                </p>
            @endif

            <div class="flex gap-3">
                <button type="button" onclick="closeBulkModal()"
                        class="btn btn-secondary flex-1">
                    Batal
                </button>

                @if($dashboard->newRegistrationsCount > 0)
                    <button type="button" id="bulk-submit-button" onclick="submitBulkValidate()"
                            class="btn btn-primary flex-1">
                        Ya, Validasi Sekarang
                    </button>
                @else
                    <button type="button" onclick="closeBulkModal()"
                            class="btn btn-primary flex-1">
                        Oke
                    </button>
                @endif
            </div>
        </div>
    </div>

    <script>
        function confirmBulkValidate() {
            const modal = document.getElementById('bulk-modal');
            if (!modal) return;

            modal.style.display = 'flex';
        }

        function closeBulkModal() {
            const modal = document.getElementById('bulk-modal');
            if (!modal) return;

            modal.style.display = 'none';
        }

        function closeBulkModalOnBackdrop(event) {
            if (event.target.id === 'bulk-modal') {
                closeBulkModal();
            }
        }

        function submitBulkValidate() {
            const form = document.getElementById('bulk-validate-form');
            const button = document.getElementById('bulk-submit-button');

            if (!form) {
                closeBulkModal();
                alert('Tidak ada pendaftaran baru yang perlu divalidasi.');
                return;
            }

            if (button) {
                button.disabled = true;
                button.innerText = 'Memvalidasi...';
            }

            form.submit();
        }
    </script>

</x-app-layout>
