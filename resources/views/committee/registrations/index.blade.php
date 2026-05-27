<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">Daftar Registrasi</h2>
                <p class="text-sm text-muted-foreground mt-1">{{ $competition->name }}</p>
            </div>
            <a href="{{ route('committee.command-center.show', $competition) }}" class="btn btn-secondary text-sm">
                ← Command Center
            </a>
        </div>
    </x-slot>

    @php
        $stats = $bottleneckStats ?? [
            'total' => 0,
            'pending' => 0,
            'account_ok' => 0,
            'documents_ok' => 0,
            'payment_ok' => 0,
            'verified' => 0,
            'rejected' => 0,
            'required_document_count' => 0,
            'document_missing' => 0,
            'document_pending' => 0,
            'document_rejected' => 0,
            'total_documents_uploaded' => 0,
            'verified_documents_uploaded' => 0,
            'document_verification_rate' => 0,
            'average_documents_per_registration' => 0,
            'payment_missing' => 0,
            'payment_pending' => 0,
            'payment_unpaid' => 0,
            'payment_paid' => 0,
            'payment_free' => 0,
            'ready_to_validate' => 0,
            'main_bottleneck' => ['label' => 'Belum ada data registrasi', 'count' => 0, 'percentage' => 0],
            'bottleneck_items' => [],
        ];
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border-2 border-black rounded-xl p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-green-800 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-2 border-black rounded-xl p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-red-800 font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('bulk_result_details'))
                <div class="mb-6 p-4 bg-white border-2 border-black rounded-xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                    <h4 class="font-bold text-sm mb-3">📋Hasil Validasi Massal Terakhir</h4>
                    <div class="space-y-2 max-h-[220px] overflow-y-auto pr-1">
                        @foreach(session('bulk_result_details') as $detail)
                            <div class="p-3 bg-gray-50 border border-black rounded-lg flex items-center justify-between text-xs hover:bg-gray-100 transition">
                                <div>
                                    <span class="font-bold text-sm">{{ $detail['user'] }}</span>
                                    <span class="text-muted-foreground ml-2">ID: #{{ $detail['registration_id'] }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-0.5 rounded font-mono uppercase font-bold text-[10px] {{ $detail['passed'] ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300' }}">
                                        {{ $detail['passed'] ? 'Lolos' : 'Gagal' }}
                                    </span>
                                    <span class="text-muted-foreground font-mono">
                                        Status Baru: <strong class="text-foreground">{{ $detail['new_status'] }}</strong>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-6 bg-white border-2 border-black rounded-xl p-6 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Validation Bottleneck Dashboard</h3>
                        <p class="text-sm text-muted-foreground mt-1">
                            Statistik deskriptif penyebab registrasi tertahan pada event ini.
                        </p>
                    </div>

                    <div class="px-4 py-3 rounded-xl border-2 border-black bg-indigo-50 text-indigo-800 text-sm min-w-[260px]">
                        <div class="font-bold">Main Bottleneck</div>
                        <div class="mt-1">{{ $stats['main_bottleneck']['label'] }}</div>
                        <div class="text-xs mt-1">
                            {{ $stats['main_bottleneck']['count'] }} dari {{ $stats['total'] }} registrasi
                            ({{ $stats['main_bottleneck']['percentage'] }}%)
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="border-2 border-black rounded-xl p-4 bg-gray-50">
                        <div class="text-xs text-gray-500 uppercase font-bold">Total Registrations</div>
                        <div class="text-2xl font-black text-gray-900 mt-1">{{ $stats['total'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">Semua registrasi event ini</div>
                    </div>

                    <div class="border-2 border-black rounded-xl p-4 bg-yellow-50">
                        <div class="text-xs text-yellow-700 uppercase font-bold">Need Review</div>
                        <div class="text-2xl font-black text-yellow-700 mt-1">
                            {{ $stats['pending'] + $stats['account_ok'] + $stats['documents_ok'] }}
                        </div>
                        <div class="text-xs text-yellow-700 mt-1">Belum selesai sampai payment_ok</div>
                    </div>

                    <div class="border-2 border-black rounded-xl p-4 bg-green-50">
                        <div class="text-xs text-green-700 uppercase font-bold">Ready To Validate</div>
                        <div class="text-2xl font-black text-green-700 mt-1">{{ $stats['ready_to_validate'] }}</div>
                        <div class="text-xs text-green-700 mt-1">Dokumen dan payment sudah aman</div>
                    </div>

                    <div class="border-2 border-black rounded-xl p-4 bg-red-50">
                        <div class="text-xs text-red-700 uppercase font-bold">Rejected</div>
                        <div class="text-2xl font-black text-red-700 mt-1">{{ $stats['rejected'] }}</div>
                        <div class="text-xs text-red-700 mt-1">Registrasi gagal validasi</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="border border-black rounded-xl p-4">
                        <h4 class="font-bold text-gray-900 mb-3">Status Registrasi</h4>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span>Pending</span><strong>{{ $stats['pending'] }}</strong></div>
                            <div class="flex justify-between"><span>Account OK</span><strong>{{ $stats['account_ok'] }}</strong></div>
                            <div class="flex justify-between"><span>Documents OK</span><strong>{{ $stats['documents_ok'] }}</strong></div>
                            <div class="flex justify-between"><span>Payment OK</span><strong>{{ $stats['payment_ok'] }}</strong></div>
                            <div class="flex justify-between"><span>Verified</span><strong>{{ $stats['verified'] }}</strong></div>
                            <div class="flex justify-between"><span>Rejected</span><strong>{{ $stats['rejected'] }}</strong></div>
                        </div>
                    </div>

                    <div class="border border-black rounded-xl p-4">
                        <h4 class="font-bold text-gray-900 mb-3">Statistik Dokumen</h4>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span>Dokumen wajib</span><strong>{{ $stats['required_document_count'] }}</strong></div>
                            <div class="flex justify-between"><span>Belum upload</span><strong class="text-red-600">{{ $stats['document_missing'] }}</strong></div>
                            <div class="flex justify-between"><span>Pending</span><strong class="text-yellow-600">{{ $stats['document_pending'] }}</strong></div>
                            <div class="flex justify-between"><span>Rejected</span><strong class="text-red-600">{{ $stats['document_rejected'] }}</strong></div>
                            <div class="flex justify-between"><span>Rata-rata dokumen</span><strong>{{ $stats['average_documents_per_registration'] }}</strong></div>
                            <div class="flex justify-between"><span>Verification rate</span><strong>{{ $stats['document_verification_rate'] }}%</strong></div>
                        </div>
                    </div>

                    <div class="border border-black rounded-xl p-4">
                        <h4 class="font-bold text-gray-900 mb-3">Statistik Payment</h4>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span>Missing</span><strong class="text-red-600">{{ $stats['payment_missing'] }}</strong></div>
                            <div class="flex justify-between"><span>Pending verification</span><strong class="text-yellow-600">{{ $stats['payment_pending'] }}</strong></div>
                            <div class="flex justify-between"><span>Unpaid</span><strong class="text-red-600">{{ $stats['payment_unpaid'] }}</strong></div>
                            <div class="flex justify-between"><span>Paid</span><strong class="text-green-600">{{ $stats['payment_paid'] }}</strong></div>
                            <div class="flex justify-between"><span>Free</span><strong class="text-green-600">{{ $stats['payment_free'] }}</strong></div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border border-black rounded-xl p-4">
                    <h4 class="font-bold text-gray-900 mb-3">Bottleneck Ranking</h4>

                    <div class="space-y-3">
                        @forelse($stats['bottleneck_items'] as $item)
                            @php
                                $barWidth = $stats['total'] > 0
                                    ? min(100, round(($item['count'] / $stats['total']) * 100, 1))
                                    : 0;

                                $barClass = $item['type'] === 'danger'
                                    ? 'bg-red-500'
                                    : 'bg-yellow-500';
                            @endphp

                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700">{{ $item['label'] }}</span>
                                    <span class="font-bold">{{ $item['count'] }}</span>
                                </div>

                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="{{ $barClass }} h-2 rounded-full" style="width: {{ $barWidth }}%;"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">Belum ada bottleneck yang bisa dihitung.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card bg-white p-6">
                <form id="bulkForm" method="POST" action="{{ route('committee.registrations.bulk-validate', $competition) }}">
                    @csrf

                    <div class="mb-6 flex flex-wrap items-center justify-between gap-3 border-b pb-4">
                        <div class="flex items-center gap-2">
                            <button type="button" id="bulkValidateBtn" onclick="openBulkModal()" class="btn btn-primary btn-sm flex items-center gap-1 opacity-50 cursor-not-allowed" disabled>
                                Validasi Massal (<span id="selectedCount">0</span>)
                            </button>
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Centang pendaftar di bawah untuk melakukan validasi otomatis serentak.
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead class="text-xs text-gray-500 uppercase border-b-2 border-black">
                                <tr>
                                    <th class="py-3 px-4 w-12 text-center">
                                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" onclick="toggleSelectAll(this)" />
                                    </th>
                                    <th class="py-3 px-4 font-bold text-foreground">Peserta</th>
                                    <th class="py-3 px-4 font-bold text-foreground">Tipe</th>
                                    <th class="py-3 px-4 font-bold text-foreground">Status</th>
                                    <th class="py-3 px-4 font-bold text-foreground">Dokumen</th>
                                    <th class="py-3 px-4 font-bold text-foreground">Pembayaran</th>
                                    <th class="py-3 px-4 font-bold text-foreground">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $reg)
                                    @php
                                        $paymentStatus = $reg->payment?->status ?? 'missing';

                                        $paymentBadge = match ($paymentStatus) {
                                            'paid', 'free' => 'bg-green-100 text-green-700 border-green-300',
                                            'unpaid' => 'bg-red-100 text-red-700 border-red-300',
                                            'pending_verification' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                                            default => 'bg-gray-100 text-gray-600 border-gray-300',
                                        };

                                        $paymentText = match ($paymentStatus) {
                                            'paid' => 'Paid',
                                            'free' => 'Free',
                                            'unpaid' => 'Unpaid',
                                            'pending_verification' => 'Pending Verification',
                                            default => 'No Payment',
                                        };

                                        $statusBadge = match ($reg->status) {
                                            'verified', 'payment_ok' => 'bg-green-50 text-green-700 border-green-300',
                                            'documents_ok' => 'bg-blue-50 text-blue-700 border-blue-300',
                                            'account_ok' => 'bg-indigo-50 text-indigo-700 border-indigo-300',
                                            'rejected' => 'bg-red-50 text-red-700 border-red-300',
                                            default => 'bg-yellow-50 text-yellow-700 border-yellow-300',
                                        };
                                    @endphp

                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="py-3 px-4 text-center">
                                            <input type="checkbox" name="registration_ids[]" value="{{ $reg->id }}" class="row-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" onclick="updateBulkButton()" />
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="font-semibold">{{ $reg->user?->name ?? $reg->team?->name ?? '-' }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $reg->user?->email ?? ($reg->team ? 'Tim' : '') }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            {{ $reg->team_id ? 'Team' : 'Individual' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded border text-xs font-bold font-mono {{ $statusBadge }}">
                                                {{ strtoupper(str_replace('_', ' ', $reg->status)) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-mono text-xs">
                                                {{ $reg->documents->where('status', 'verified')->count() }} / {{ $reg->documents->count() }} verified
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded border text-xs font-bold {{ $paymentBadge }}">
                                                {{ $paymentText }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('committee.registrations.show', [$competition, $reg]) }}"
                                               class="btn btn-secondary btn-sm py-1 px-3">Review</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-gray-400">
                                            Belum ada pendaftaran masuk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="bulk-confirm-modal" class="hidden" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
        <div class="card bg-white p-6" style="max-width: 450px; width: 90%;">
            <h3 class="text-lg font-bold mb-2">Konfirmasi Validasi Massal</h3>
            <p class="text-sm text-muted-foreground mb-4">
                Anda akan menjalankan validasi otomatis Chain of Responsibility pada semua pendaftar yang dipilih.
                Sistem akan mengevaluasi status dokumen dan status pembayaran untuk memajukan state registrasi mereka.
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="closeBulkModal()" class="btn btn-secondary flex-1">Batal</button>
                <button type="button" onclick="submitBulk()" class="btn btn-primary flex-1">Ya, Validasi Sekarang</button>
            </div>
        </div>
    </div>

    <script>
        function toggleSelectAll(master) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = master.checked;
            });
            updateBulkButton();
        }

        function updateBulkButton() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const selected = Array.from(checkboxes).filter(cb => cb.checked);
            const btn = document.getElementById('bulkValidateBtn');
            const countSpan = document.getElementById('selectedCount');

            countSpan.innerText = selected.length;

            if (selected.length > 0) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                selectAll.checked = selected.length === checkboxes.length && checkboxes.length > 0;
            }
        }

        function openBulkModal() {
            document.getElementById('bulk-confirm-modal').classList.remove('hidden');
            document.getElementById('bulk-confirm-modal').style.display = 'flex';
        }

        function closeBulkModal() {
            document.getElementById('bulk-confirm-modal').classList.add('hidden');
            document.getElementById('bulk-confirm-modal').style.display = 'none';
        }

        function submitBulk() {
            document.getElementById('bulkForm').submit();
        }
    </script>
</x-app-layout>