<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrations') }} — {{ $competition->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

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
            </div>
        </div>
    </div>
</x-app-layout>