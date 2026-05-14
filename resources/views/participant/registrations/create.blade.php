<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Register for') }} — {{ $competition->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
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
                                <div class="mb-4">
                                    <x-input-label :for="'field_' . $index" :value="$field['label'] ?? 'Field'" />
                                    @if(($field['type'] ?? 'text') === 'file')
                                        <input type="file" id="field_{{ $index }}" name="documents[{{ $field['label'] }}]"
                                            class="block mt-1 w-full text-sm" {{ ($field['required'] ?? false) ? 'required' : '' }} />
                                    @elseif(($field['type'] ?? 'text') === 'textarea')
                                        <textarea id="field_{{ $index }}" name="form_data[{{ $field['label'] }}]"
                                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="3"
                                            {{ ($field['required'] ?? false) ? 'required' : '' }}>{{ old('form_data.' . $field['label']) }}</textarea>
                                    @elseif(($field['type'] ?? 'text') === 'select')
                                        <select id="field_{{ $index }}" name="form_data[{{ $field['label'] }}]"
                                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                            <option value="">-- Select --</option>
                                            @foreach(($field['options'] ?? []) as $opt)
                                                <option value="{{ $opt }}">{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <x-text-input :id="'field_' . $index" :name="'form_data[' . $field['label'] . ']'"
                                            :type="$field['type'] ?? 'text'" class="block mt-1 w-full"
                                            :value="old('form_data.' . $field['label'])" :required="$field['required'] ?? false" />
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 text-sm">No form configured yet.</p>
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
