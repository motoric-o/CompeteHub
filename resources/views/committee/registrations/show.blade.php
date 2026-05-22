<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Registration') }} — {{ $registration->user?->name ?? $registration->team?->name }}
        </h2>
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

        $explanations = [];

        if (! $registration->user && ! $registration->team) {
            $explanations[] = ['type' => 'danger', 'text' => 'Registration tidak memiliki user atau team yang valid.'];
        }

        foreach ($requiredDocuments as $field) {
            $label = $field['label'] ?? 'Document';
            $doc = $documentsByType->get($label);

            if (! $doc) {
                $explanations[] = ['type' => 'danger', 'text' => "Dokumen {$label} belum diupload."];
            } elseif ($doc->status === 'pending') {
                $explanations[] = ['type' => 'warning', 'text' => "Dokumen {$label} masih pending dan perlu diverifikasi."];
            } elseif ($doc->status === 'rejected') {
                $explanations[] = ['type' => 'danger', 'text' => "Dokumen {$label} ditolak, sehingga validasi akan gagal."];
            }
        }

        if ($competition->registration_fee > 0) {
            if (! $registration->payment) {
                $explanations[] = ['type' => 'danger', 'text' => 'Payment record belum ada untuk kompetisi berbayar.'];
            } elseif ($registration->payment->status === 'pending_verification') {
                $explanations[] = ['type' => 'warning', 'text' => 'Pembayaran masih pending verification dan perlu dicek panitia.'];
            } elseif ($registration->payment->status === 'unpaid') {
                $explanations[] = ['type' => 'danger', 'text' => 'Pembayaran ditandai unpaid, sehingga registrasi belum bisa lolos.'];
            }
        }

        if (empty($explanations)) {
            $explanations[] = ['type' => 'success', 'text' => 'Semua syarat utama sudah aman. Registration siap dijalankan melalui Run Validation Chain.'];
        }
    @endphp

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Registration Status</h3>

                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold border {{ $statusBadge }}">
                            {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                        </span>

                        @if($registration->rejection_reason)
                            <p class="mt-2 text-sm text-red-600">Reason: {{ $registration->rejection_reason }}</p>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('committee.registrations.validate', [$competition, $registration]) }}">
                        @csrf
                        <x-primary-button>{{ __('Run Validation Chain (CoR)') }}</x-primary-button>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Validation Explanation Panel</h3>

                <div class="space-y-2">
                    @foreach($explanations as $item)
                        <div class="px-4 py-3 rounded-lg border text-sm
                            {{ $item['type'] === 'success' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                            {{ $item['type'] === 'warning' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                            {{ $item['type'] === 'danger' ? 'bg-red-50 text-red-700 border-red-200' : '' }}">
                            {{ $item['text'] }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Form Answers</h3>

                @if($registration->form_data)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($registration->form_data as $label => $answer)
                            <div class="text-sm border rounded-lg p-3 bg-gray-50">
                                <div class="font-medium text-gray-700">{{ $label }}</div>

                                <div class="text-gray-600 mt-1">
                                    @if(is_array($answer))
                                        {{ implode(', ', $answer) }}
                                    @elseif($answer === '1' || $answer === 1)
                                        Yes
                                    @else
                                        {{ $answer }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400">No form answers submitted.</p>
                @endif
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Document Checklist</h3>

                <div class="space-y-3">
                    @forelse($requiredDocuments as $field)
                        @php
                            $label = $field['label'] ?? 'Document';
                            $doc = $documentsByType->get($label);
                            $docStatus = $doc?->status ?? 'missing';

                            $docBadge = match ($docStatus) {
                                'verified' => 'bg-green-100 text-green-700 border-green-200',
                                'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                default => 'bg-gray-100 text-gray-600 border-gray-200',
                            };
                        @endphp

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between py-3 border-b last:border-0">
                            <div>
                                <div class="text-sm font-medium text-gray-700">{{ $label }}</div>

                                @if($doc)
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-xs text-indigo-600 hover:underline">
                                        View file
                                    </a>
                                @else
                                    <p class="text-xs text-gray-400">Required document has not been uploaded.</p>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded-full font-semibold border {{ $docBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $docStatus)) }}
                                </span>

                                @if($doc)
                                    <form method="POST" action="{{ route('committee.documents.verify', $doc) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="verified" />
                                        <button type="submit" class="text-xs text-green-600 hover:underline">Approve</button>
                                    </form>

                                    <form method="POST" action="{{ route('committee.documents.verify', $doc) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected" />
                                        <button type="submit" class="text-xs text-red-600 hover:underline">Reject</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No required document configured for this competition.</p>
                    @endforelse

                    @foreach($registration->documents->whereNotIn('document_type', $requiredDocuments->pluck('label')) as $doc)
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between py-3 border-b last:border-0">
                            <div>
                                <div class="text-sm font-medium text-gray-700">{{ $doc->document_type }}</div>

                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-xs text-indigo-600 hover:underline">
                                    View file
                                </a>
                            </div>

                            <span class="text-xs px-2 py-1 rounded-full font-semibold border
                                {{ $doc->status === 'verified' ? 'bg-green-100 text-green-700 border-green-200' : ($doc->status === 'rejected' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-yellow-100 text-yellow-700 border-yellow-200') }}">
                                {{ ucfirst($doc->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Payment Verification</h3>

                @if($registration->payment)
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-600">
                                Amount: <strong>Rp {{ number_format($registration->payment->amount, 0, ',', '.') }}</strong>
                            </p>

                            <div class="mt-2">
                                <span class="text-xs px-3 py-1 rounded-full font-semibold border {{ $paymentBadge }}">
                                    {{ $paymentText }}
                                </span>
                            </div>

                            @if($registration->payment->proof_path)
                                <a href="{{ asset('storage/' . $registration->payment->proof_path) }}" target="_blank" class="text-xs text-indigo-600 hover:underline mt-2 inline-block">
                                    View proof
                                </a>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('committee.payments.verify', $registration->payment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="paid" />
                                <button type="submit" class="text-xs text-green-600 hover:underline">Approve</button>
                            </form>

                            <form method="POST" action="{{ route('committee.payments.verify', $registration->payment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="unpaid" />
                                <button type="submit" class="text-xs text-red-600 hover:underline">Reject</button>
                            </form>
                        </div>
                    </div>
                @else
                    <span class="text-xs px-3 py-1 rounded-full font-semibold border {{ $paymentBadge }}">
                        {{ $paymentText }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>