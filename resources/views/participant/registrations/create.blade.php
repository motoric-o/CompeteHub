<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Register for') }} — {{ $competition->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="font-semibold text-gray-800">{{ $competition->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $competition->description }}</p>
                        <div class="mt-2 flex gap-4 text-xs text-gray-500">
                            <span>Type: <strong>{{ ucfirst($competition->type) }}</strong></span>
                            <span>Fee: <strong>Rp {{ number_format($competition->registration_fee, 0, ',', '.') }}</strong></span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('participant.registrations.store', $competition) }}" enctype="multipart/form-data">
                        @csrf

                        @if($formTemplate && is_array($formTemplate->fields))
                            @foreach($formTemplate->fields as $index => $field)
                                @php
                                    $label = $field['label'] ?? 'Field';
                                    $type = $field['type'] ?? 'text';
                                    $required = $field['required'] ?? false;
                                @endphp

                                <div class="mb-4">
                                    <x-input-label :for="'field_' . $index" :value="$label" />

                                    @if($type === 'file')
                                        <input
                                            type="file"
                                            id="field_{{ $index }}"
                                            name="documents[{{ $label }}]"
                                            class="block mt-1 w-full text-sm"
                                            {{ $required ? 'required' : '' }}
                                        />

                                    @elseif($type === 'textarea')
                                        <textarea
                                            id="field_{{ $index }}"
                                            name="form_data[{{ $label }}]"
                                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                            rows="3"
                                            {{ $required ? 'required' : '' }}
                                        >{{ old('form_data.' . $label) }}</textarea>

                                    @elseif($type === 'select')
                                        <select
                                            id="field_{{ $index }}"
                                            name="form_data[{{ $label }}]"
                                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                            {{ $required ? 'required' : '' }}
                                        >
                                            <option value="">-- Select --</option>
                                            @foreach(($field['options'] ?? []) as $opt)
                                                <option value="{{ $opt }}" {{ old('form_data.' . $label) == $opt ? 'selected' : '' }}>
                                                    {{ $opt }}
                                                </option>
                                            @endforeach
                                        </select>

                                    @elseif($type === 'checkbox')
                                        <label class="inline-flex items-center mt-2">
                                            <input
                                                type="checkbox"
                                                id="field_{{ $index }}"
                                                name="form_data[{{ $label }}]"
                                                value="1"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                {{ old('form_data.' . $label) ? 'checked' : '' }}
                                                {{ $required ? 'required' : '' }}
                                            />
                                            <span class="ml-2 text-sm text-gray-600">Yes</span>
                                        </label>

                                    @else
                                        <x-text-input
                                            :id="'field_' . $index"
                                            :name="'form_data[' . $label . ']'"
                                            :type="$type"
                                            class="block mt-1 w-full"
                                            :value="old('form_data.' . $label)"
                                            :required="$required"
                                        />
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 text-sm mb-4">No form configured yet.</p>
                        @endif

                        @if($competition->registration_fee > 0)
                            <div class="mb-4 p-4 border rounded-lg bg-yellow-50">
                                <x-input-label for="payment_proof" :value="__('Payment Proof')" />
                                <input
                                    type="file"
                                    id="payment_proof"
                                    name="payment_proof"
                                    class="block mt-1 w-full text-sm"
                                    required
                                />
                                <p class="text-xs text-gray-500 mt-1">
                                    Upload bukti pembayaran agar dapat diverifikasi oleh panitia.
                                </p>
                            </div>
                        @endif

                        <div class="flex justify-end mt-6">
                            <x-primary-button>{{ __('Submit Registration') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>