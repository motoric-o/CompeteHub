<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Form Template') }} — {{ $competition->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Reuse existing template --}}
                    @if($existingTemplates->count() > 0)
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-semibold text-blue-700 mb-2">{{ __('Reuse an existing template') }}</h4>
                            <form method="POST" action="{{ route('committee.form-templates.store', $competition) }}">
                                @csrf
                                <div class="flex items-end gap-4">
                                    <div class="flex-1">
                                        <x-input-label for="clone_from" :value="__('Select Template')" />
                                        <select id="clone_from" name="clone_from"
                                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            @foreach($existingTemplates as $et)
                                                <option value="{{ $et->id }}">{{ $et->name }} ({{ $et->competition->name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <x-input-label for="clone_name" :value="__('New Name')" />
                                        <x-text-input id="clone_name" name="name" type="text" class="block mt-1 w-full" placeholder="My cloned template" required />
                                    </div>
                                    <input type="hidden" name="fields" value="[]" />
                                    <x-primary-button>{{ __('Clone') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                            <div class="relative flex justify-center text-sm"><span class="px-2 bg-white text-gray-500">or create from scratch</span></div>
                        </div>
                    @endif

                    {{-- Create new template with dynamic form builder --}}
                    <form method="POST" action="{{ route('committee.form-templates.store', $competition) }}" id="templateForm">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Template Name')" />
                            <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <input type="hidden" name="fields" id="fieldsJson" value="[]" />

                        {{-- Dynamic field builder --}}
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 mb-2">{{ __('Form Fields') }}</label>

                            <div id="fieldsList" class="space-y-3"></div>

                            <button type="button" onclick="addField()"
                                class="mt-3 inline-flex items-center px-3 py-1.5 border border-dashed border-gray-400 rounded-md text-sm text-gray-600 hover:border-indigo-500 hover:text-indigo-600 transition">
                                + Add Field
                            </button>
                        </div>

                        <x-input-error :messages="$errors->get('fields')" class="mt-2" />

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('committee.form-templates.index', $competition) }}" class="mr-3 text-gray-500 hover:text-gray-700 py-2 px-4">Cancel</a>
                            <x-primary-button onclick="prepareSubmit()">{{ __('Save Template') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let fields = [];
        const fieldTypes = ['text', 'email', 'number', 'textarea', 'file', 'select', 'checkbox', 'date'];

        function addField(preset = null) {
            const field = preset || { label: '', type: 'text', required: false, options: [] };
            fields.push(field);
            renderFields();
        }

        function removeField(index) {
            fields.splice(index, 1);
            renderFields();
        }

        function renderFields() {
            const container = document.getElementById('fieldsList');
            container.innerHTML = '';

            fields.forEach((field, i) => {
                const div = document.createElement('div');
                div.className = 'flex items-start gap-3 p-3 bg-gray-50 rounded-lg border';
                div.innerHTML = `
                    <div class="flex-1">
                        <input type="text" placeholder="Field label" value="${field.label}"
                            onchange="fields[${i}].label = this.value"
                            class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <div class="w-32">
                        <select onchange="fields[${i}].type = this.value; renderFields();"
                            class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            ${fieldTypes.map(t => `<option value="${t}" ${field.type === t ? 'selected' : ''}>${t}</option>`).join('')}
                        </select>
                    </div>
                    <label class="flex items-center text-sm text-gray-600 whitespace-nowrap">
                        <input type="checkbox" ${field.required ? 'checked' : ''}
                            onchange="fields[${i}].required = this.checked"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-1" />
                        Required
                    </label>
                    <button type="button" onclick="removeField(${i})" class="text-red-500 hover:text-red-700 text-lg font-bold">&times;</button>
                `;

                // Options for select type
                if (field.type === 'select') {
                    const optDiv = document.createElement('div');
                    optDiv.className = 'mt-2 col-span-full w-full';
                    optDiv.innerHTML = `
                        <input type="text" placeholder="Options (comma-separated)" value="${(field.options || []).join(', ')}"
                            onchange="fields[${i}].options = this.value.split(',').map(s => s.trim()).filter(s => s)"
                            class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    `;
                    div.appendChild(optDiv);
                }

                container.appendChild(div);
            });
        }

        function prepareSubmit() {
            // Sync labels from inputs before submit
            const inputs = document.querySelectorAll('#fieldsList input[type="text"]');
            document.getElementById('fieldsJson').value = JSON.stringify(fields);
        }

        // Init with at least one field
        addField({ label: 'Full Name', type: 'text', required: true, options: [] });
    </script>
</x-app-layout>
