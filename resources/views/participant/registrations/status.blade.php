<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registration Status') }} — {{ $competition->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-blue-700 font-medium">
                                You have already registered for this competition.
                            </p>
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="font-semibold text-gray-800">{{ $competition->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $competition->description }}</p>
                    </div>

                    @php
                        $steps = [
                            'pending'       => 'Submitted',
                            'account_ok'    => 'Account Verified',
                            'documents_ok'  => 'Documents Verified',
                            'payment_ok'    => 'Payment Verified',
                        ];
                        $currentStep = $existing->status;
                        $stepKeys    = array_keys($steps);
                        $currentIndex = array_search($currentStep, $stepKeys);
                        if ($currentStep === 'rejected') $currentIndex = -1;
                    @endphp

                    <h4 class="font-semibold text-gray-700 mb-3">Current Status</h4>

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
                            <strong>Registration Rejected:</strong> {{ $existing->rejection_reason ?? 'No reason provided.' }}
                        </div>
                    @endif
                    
                    <div class="flex items-center gap-3 mt-6">
                        <a href="{{ route('participant.registrations.show', [$competition, $existing]) }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150">
                            View Full Details
                        </a>
                        <a href="{{ route('participant.competitions.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition ease-in-out duration-150">
                            Back to Competitions
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
