<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Registration') }} — {{ $registration->user?->name ?? $registration->team?->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

            {{-- Status --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-2">Registration Status</h3>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $registration->status === 'payment_ok' ? 'bg-green-100 text-green-700' :
                       ($registration->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                    {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                </span>

                @if($registration->rejection_reason)
                    <p class="mt-2 text-sm text-red-600">Reason: {{ $registration->rejection_reason }}</p>
                @endif

                {{-- Run validation chain button --}}
                <form method="POST" action="{{ route('committee.registrations.validate', [$competition, $registration]) }}" class="mt-4">
                    @csrf
                    <x-primary-button>{{ __('Run Validation Chain (CoR)') }}</x-primary-button>
                </form>
            </div>
            
            {{-- Form Answers --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Form Answers</h3>

                @if($registration->form_data)
                    <div class="space-y-2">
                        @foreach($registration->form_data as $label => $answer)
                            <div class="text-sm">
                                <span class="font-medium text-gray-700">{{ $label }}:</span>
                                <span class="text-gray-600">
                                    @if(is_array($answer))
                                        {{ implode(', ', $answer) }}
                                    @elseif($answer === '1' || $answer === 1)
                                        Yes
                                    @else
                                        {{ $answer }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400">No form answers submitted.</p>
                @endif
            </div>
            
            {{-- Documents (CoR tahap 2) --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Documents</h3>
                @forelse($registration->documents as $doc)
                    <div class="flex items-center justify-between py-2 border-b last:border-0">
                        <div>
                            <span class="text-sm font-medium text-gray-700">{{ $doc->document_type }}</span>
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                               class="ml-2 text-xs text-indigo-600 hover:underline">View file</a>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2 py-0.5 rounded
                                {{ $doc->status === 'verified' ? 'bg-green-100 text-green-700' :
                                   ($doc->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($doc->status) }}
                            </span>
                            <form method="POST" action="{{ route('committee.documents.verify', $doc) }}" class="inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="verified" />
                                <button type="submit" class="text-xs text-green-600 hover:underline">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('committee.documents.verify', $doc) }}" class="inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected" />
                                <button type="submit" class="text-xs text-red-600 hover:underline">Reject</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">No documents uploaded.</p>
                @endforelse
            </div>

            {{-- Payment (CoR tahap 3) --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Payment</h3>
                @if($registration->payment)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">
                                Amount: <strong>Rp {{ number_format($registration->payment->amount, 0, ',', '.') }}</strong>
                            </p>
                            <p class="text-sm text-gray-600">
                                Status: <strong>{{ ucfirst(str_replace('_', ' ', $registration->payment->status)) }}</strong>
                            </p>
                            @if($registration->payment->proof_path)
                                <a href="{{ asset('storage/' . $registration->payment->proof_path) }}" target="_blank"
                                   class="text-xs text-indigo-600 hover:underline">View proof</a>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('committee.payments.verify', $registration->payment) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="paid" />
                                <button type="submit" class="text-xs text-green-600 hover:underline">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('committee.payments.verify', $registration->payment) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="unpaid" />
                                <button type="submit" class="text-xs text-red-600 hover:underline">Reject</button>
                            </form>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-400">No payment record.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
