<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Registration Status') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">{{ $competition->name }}</h3>

                    {{-- Status stepper --}}
                    @php
                        $steps = ['pending' => 'Submitted', 'account_ok' => 'Account Verified', 'documents_ok' => 'Documents Verified', 'payment_ok' => 'Payment Verified'];
                        $currentStep = $registration->status;
                        $stepKeys = array_keys($steps);
                        $currentIndex = array_search($currentStep, $stepKeys);
                        if ($currentStep === 'rejected') $currentIndex = -1;
                    @endphp

                    <div class="flex items-center mb-6">
                        @foreach($steps as $key => $label)
                            @php $idx = array_search($key, $stepKeys); @endphp
                            <div class="flex-1 text-center">
                                <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center text-sm font-bold
                                    {{ $idx <= $currentIndex ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                    {{ $idx + 1 }}
                                </div>
                                <p class="text-xs mt-1 {{ $idx <= $currentIndex ? 'text-green-700 font-semibold' : 'text-gray-400' }}">{{ $label }}</p>
                            </div>
                            @if(!$loop->last)
                                <div class="w-12 h-0.5 {{ $idx < $currentIndex ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                            @endif
                        @endforeach
                    </div>

                    @if($currentStep === 'rejected')
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                            <strong>Rejected:</strong> {{ $registration->rejection_reason ?? 'No reason provided.' }}
                        </div>
                    @endif

                    {{-- Documents --}}
                    @if($registration->documents->count())
                        <h4 class="font-semibold text-gray-700 mt-6 mb-2">Uploaded Documents</h4>
                        <ul class="space-y-1">
                            @foreach($registration->documents as $doc)
                                <li class="text-sm text-gray-600 flex justify-between">
                                    <span>{{ $doc->document_type }}</span>
                                    <span class="px-2 py-0.5 rounded text-xs {{ $doc->status === 'verified' ? 'bg-green-100 text-green-700' : ($doc->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($doc->status) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- Payment --}}
                    @if($registration->payment)
                        <h4 class="font-semibold text-gray-700 mt-6 mb-2">Payment</h4>
                        <p class="text-sm text-gray-600">
                            Amount: Rp {{ number_format($registration->payment->amount, 0, ',', '.') }} —
                            Status: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $registration->payment->status)) }}</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
