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

            {{-- Bulk Validation Output Log --}}
            @if(session('bulk_result_details'))
                <div class="mb-6 p-4 bg-white border-2 border-black rounded-xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                    <h4 class="font-bold text-sm mb-3">📋 Hasil Validasi Massal Terakhir</h4>
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
                                    <span class="text-muted-foreground font-mono">Status Baru: <strong class="text-foreground">{{ $detail['new_status'] }}</strong></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase border-b">
                            <tr>
                                <th class="py-3 px-4">Participant</th>
                                <th class="py-3 px-4">Type</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Documents</th>
                                <th class="py-3 px-4">Payment</th>
                                <th class="py-3 px-4">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($registrations as $reg)
                                @php
                                    $paymentStatus = $reg->payment?->status ?? 'missing';

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
                                        default => 'No Payment',
                                    };

                                    $statusBadge = match ($reg->status) {
                                        'payment_ok', 'verified' => 'bg-green-100 text-green-700 border-green-200',
                                        'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                        'documents_ok' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'account_ok' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                        default => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    };
                                @endphp

                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        {{ $reg->user?->name ?? $reg->team?->name ?? '-' }}
                                    </td>

                                    <td class="py-3 px-4">
                                        {{ $reg->team_id ? 'Team' : 'Individual' }}
                                    </td>

                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold border {{ $statusBadge }}">
                                            {{ ucfirst(str_replace('_', ' ', $reg->status)) }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4">
                                        <span class="text-gray-700 font-medium">
                                            {{ $reg->documents->where('status', 'verified')->count() }}
                                        </span>
                                        <span class="text-gray-400">
                                            / {{ $reg->documents->count() }} verified
                                        </span>
                                    </td>

                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold border {{ $paymentBadge }}">
                                            {{ $paymentText }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4">
                                        <a href="{{ route('committee.registrations.show', [$competition, $reg]) }}"
                                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-gray-400">No registrations yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="card bg-white p-6">
                <form id="bulkForm" method="POST" action="{{ route('committee.registrations.bulk-validate', $competition) }}">
                    @csrf

                    {{-- Bulk Actions Bar --}}
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-3 border-b pb-4">
                        <div class="flex items-center gap-2">
                            <button type="button" id="bulkValidateBtn" onclick="openBulkModal()" class="btn btn-primary btn-sm flex items-center gap-1 opacity-50 cursor-not-allowed" disabled>
                                ⚡ Validasi Massal (<span id="selectedCount">0</span>)
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
                                            <span class="px-2 py-0.5 rounded border text-xs font-bold font-mono
                                                {{ $reg->status === 'verified' ? 'bg-green-50 text-green-700 border-green-300' :
                                                   ($reg->status === 'payment_ok' ? 'bg-blue-50 text-blue-700 border-blue-300' :
                                                   ($reg->status === 'rejected' ? 'bg-red-50 text-red-700 border-red-300' :
                                                    'bg-yellow-50 text-yellow-700 border-yellow-300')) }}">
                                                {{ strtoupper(str_replace('_', ' ', $reg->status)) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-mono text-xs">{{ $reg->documents->count() }} file</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($reg->payment)
                                                <span class="text-xs font-semibold {{ $reg->payment->status === 'verified' ? 'text-green-600' : 'text-yellow-600' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $reg->payment->status)) }}
                                                </span>
                                            @else
                                                <span class="text-xs text-muted-foreground">-</span>
                                            @endif
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

    {{-- Bulk Validation Confirmation Modal --}}
    <div id="bulk-confirm-modal" class="hidden" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
        <div class="card bg-white p-6" style="max-width: 450px; width: 90%;">
            <h3 class="text-lg font-bold mb-2">Konfirmasi Validasi Massal</h3>
            <p class="text-sm text-muted-foreground mb-4">
                Anda akan menjalankan validasi otomatis (Chain of Responsibility) pada semua pendaftar yang dipilih.
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
                selectAll.checked = (selected.length === checkboxes.length && checkboxes.length > 0);
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
