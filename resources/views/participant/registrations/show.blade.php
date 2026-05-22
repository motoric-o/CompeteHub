<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Registration Status') }}</h2>
    </x-slot>

    @php
        $formTemplate = $competition->formTemplates()->latest()->first();

        $requiredDocuments = collect($formTemplate?->fields ?? [])
            ->filter(fn ($field) => ($field['type'] ?? null) === 'file' && ($field['required'] ?? false))
            ->values();

        $documentsByType = $registration->documents->keyBy('document_type');

        $timeline = [
            'pending' => [
                'label' => 'Submitted',
                'description' => 'Pendaftaran sudah dikirim dan menunggu pengecekan panitia.',
            ],
            'account_ok' => [
                'label' => 'Account Checked',
                'description' => 'Akun peserta sudah lolos pengecekan awal.',
            ],
            'documents_ok' => [
                'label' => 'Documents Checked',
                'description' => 'Dokumen wajib sudah lengkap dan diverifikasi.',
            ],
            'payment_ok' => [
                'label' => 'Payment Checked',
                'description' => 'Pembayaran sudah valid dan pendaftaran diterima.',
            ],
        ];

        $statusOrder = array_keys($timeline);
        $currentIndex = array_search($registration->status, $statusOrder, true);
        $currentIndex = $currentIndex === false ? -1 : $currentIndex;

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
    @endphp

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-6">
                        <div>
                            <h3 class="font-semibold text-lg text-gray-800">{{ $competition->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Registration submitted {{ $registration->created_at?->diffForHumans() }}.
                            </p>
                        </div>

                        <span class="inline-flex w-fit px-3 py-1 rounded-full text-xs font-semibold border {{ $statusBadge }}">
                            {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                        </span>
                    </div>

                    <h4 class="font-semibold text-gray-800 mb-3">Registration Status Timeline</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        @foreach($timeline as $key => $step)
                            @php
                                $idx = array_search($key, $statusOrder, true);
                                $isDone = $registration->status !== 'rejected' && $idx <= $currentIndex;
                                $isCurrent = $registration->status === $key;
                            @endphp

                            <div class="border rounded-xl p-4 {{ $isDone ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $isDone ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                        {{ $idx + 1 }}
                                    </div>

                                    <span class="text-sm font-semibold {{ $isDone ? 'text-green-700' : 'text-gray-500' }}">
                                        {{ $step['label'] }}
                                    </span>
                                </div>

                                <p class="text-xs {{ $isCurrent ? 'text-gray-700' : 'text-gray-500' }}">
                                    {{ $step['description'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    @if($registration->status === 'rejected')
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mt-4">
                            <div class="font-semibold">Registration Rejected</div>
                            <p class="text-sm mt-1">{{ $registration->rejection_reason ?? 'No reason provided.' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-800 mb-3">Document Checklist</h4>

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

                            <div class="flex items-center justify-between gap-3 border rounded-lg p-3">
                                <div>
                                    <div class="text-sm font-semibold text-gray-800">{{ $label }}</div>

                                    <div class="text-xs text-gray-500">
                                        @if($doc)
                                            Document uploaded and waiting for committee verification.
                                        @else
                                            Required document has not been uploaded.
                                        @endif
                                    </div>
                                </div>

                                <span class="px-2 py-1 rounded-full text-xs font-semibold border {{ $docBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $docStatus)) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No required document configured for this competition.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-800 mb-3">Uploaded Documents</h4>

                    @if($registration->documents->count())
                        <div class="space-y-3">
                            @foreach($registration->documents as $doc)
                                @php
                                    $uploadedDocBadge = match ($doc->status) {
                                        'verified' => 'bg-green-100 text-green-700 border-green-200',
                                        'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                        default => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    };
                                @endphp

                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border rounded-lg p-3">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-800">{{ $doc->document_type }}</div>

                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-xs text-indigo-600 hover:underline">
                                            View uploaded file
                                        </a>
                                    </div>

                                    <span class="w-fit px-2 py-1 rounded-full text-xs font-semibold border {{ $uploadedDocBadge }}">
                                        {{ ucfirst($doc->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400">No uploaded documents found.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-800">Payment Verification</h4>

                            <p class="text-sm text-gray-600 mt-1">
                                Amount: Rp {{ number_format($registration->payment?->amount ?? $competition->registration_fee, 0, ',', '.') }}
                            </p>

                            @if($registration->payment?->proof_path)
                                <a href="{{ asset('storage/' . $registration->payment->proof_path) }}" target="_blank" class="text-xs text-indigo-600 hover:underline mt-1 inline-block">
                                    View payment proof
                                </a>
                            @endif
                        </div>

                        <span class="inline-flex w-fit px-3 py-1 rounded-full text-xs font-semibold border {{ $paymentBadge }}">
                            {{ $paymentText }}
                        </span>
                    </div>
                </div>
            </div>

            @if(in_array($registration->status, ['verified', 'payment_ok']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-700 mb-4">Competition Actions</h4>

                        <p class="text-sm text-gray-600 mb-4">
                            Your registration is verified. You can now access the competition rounds, upload your submissions, or download your certificate of participation.
                        </p>

                        <div class="flex flex-wrap gap-4 mt-4">
                            <a href="{{ route('participant.submissions.index', $competition) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                View Submissions
                            </a>

                            <a href="{{ route('participant.registrations.certificate', ['competition' => $competition->id, 'registration' => $registration->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150" target="_blank">
                                Download Certificate
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>