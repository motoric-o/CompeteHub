<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Form Template — {{ $competition->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
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
                    <form id="templateForm" method="POST"
                        action="{{ route('committee.form-templates.store', $competition) }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Template Name')" />
                            <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
                                value="{{ old('name') }}" required autofocus />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="clone_from" :value="__('Reuse Existing Template')" />
                            <select id="clone_from" name="clone_from"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                onchange="toggleBuilder()">
                                <option value="">-- Create new form manually --</option>
                                @forelse($existingTemplates as $template)
                                    <option value="{{ $template->id }}" {{ old('clone_from') == $template->id ? 'selected' : '' }}>
                                        {{ $template->name }} — {{ $template->competition?->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>No reusable template available</option>
                                @endforelse
                            </select>

                            <p class="text-xs text-gray-500 mt-1">
                                Pilih template lama kalau ingin menggunakan ulang field yang sudah pernah dibuat.
                            </p>
                        </div>

                        <div id="manualBuilder">
                            <h3 class="font-semibold text-gray-800 mb-3">Form Fields</h3>

                            <div id="fieldsList" class="space-y-3 mb-4"></div>

                            <button type="button" onclick="addField()"
                                class="border border-dashed border-gray-400 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50">
                                + Add Field
                            </button>
                        </div>

                        <input type="hidden" name="fields" id="fieldsJson" />

                        <div class="flex justify-end gap-3 mt-6">
                            <a href="{{ route('committee.form-templates.index', $competition) }}"
                                class="text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>

                            <x-primary-button>
                                {{ __('Save Template') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let fields = [];
        const fieldTypes = ['text', 'email', 'number', 'textarea', 'file', 'select', 'checkbox', 'date'];

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;');
        }

        function syncFieldsFromDOM() {
            document.querySelectorAll('#fieldsList [data-index]').forEach((row) => {
                const index = Number(row.dataset.index);

                if (!fields[index]) {
                    return;
                }

                const labelInput = row.querySelector('[data-field="label"]');
                const typeSelect = row.querySelector('[data-field="type"]');
                const requiredInput = row.querySelector('[data-field="required"]');
                const optionsInput = row.querySelector('[data-field="options"]');

                fields[index].label = labelInput ? labelInput.value : '';
                fields[index].type = typeSelect ? typeSelect.value : 'text';
                fields[index].required = requiredInput ? requiredInput.checked : false;

                if (optionsInput) {
                    fields[index].options = optionsInput.value
                        .split(',')
                        .map((item) => item.trim())
                        .filter((item) => item.length > 0);
                } else if (!fields[index].options) {
                    fields[index].options = [];
                }
            });
        }

        function addField(preset = null) {
            syncFieldsFromDOM();

            const field = preset || {
                label: '',
                type: 'text',
                required: false,
                options: []
            };

            fields.push(field);
            renderFields();
        }

        function removeField(index) {
            syncFieldsFromDOM();
            fields.splice(index, 1);
            renderFields();
        }

        function renderFields() {
            const container = document.getElementById('fieldsList');
            container.innerHTML = '';

            fields.forEach((field, index) => {
                const row = document.createElement('div');
                row.className = 'p-3 bg-gray-50 rounded-lg border';
                row.dataset.index = index;

                row.innerHTML = `
                    <div class="flex items-start gap-3">
                        <div class="flex-1">
                            <input
                                type="text"
                                placeholder="Field label"
                                value="${escapeHtml(field.label)}"
                                data-field="label"
                                oninput="fields[${index}].label = this.value"
                                class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <div class="w-32">
                            <select
                                data-field="type"
                                onchange="fields[${index}].type = this.value; syncFieldsFromDOM(); renderFields();"
                                class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                ${fieldTypes.map((type) => `
                                    <option value="${type}" ${field.type === type ? 'selected' : ''}>${type}</option>
                                `).join('')}
                            </select>
                        </div>

                        <label class="flex items-center text-sm text-gray-600 whitespace-nowrap">
                            <input
                                type="checkbox"
                                data-field="required"
                                ${field.required ? 'checked' : ''}
                                onchange="fields[${index}].required = this.checked"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-1"
                            />
                            Required
                        </label>

                        <button
                            type="button"
                            onclick="removeField(${index})"
                            class="text-red-500 hover:text-red-700 text-lg font-bold"
                        >
                            &times;
                        </button>
                    </div>

                    ${field.type === 'select' ? `
                        <div class="mt-3">
                            <input
                                type="text"
                                placeholder="Options, contoh: Beginner, Intermediate, Advanced"
                                value="${escapeHtml((field.options || []).join(', '))}"
                                data-field="options"
                                oninput="fields[${index}].options = this.value.split(',').map(item => item.trim()).filter(item => item.length > 0)"
                                class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                    ` : ''}
                `;

                container.appendChild(row);
            });
        }

        function toggleBuilder() {
            const cloneValue = document.getElementById('clone_from').value;
            const builder = document.getElementById('manualBuilder');

            if (cloneValue) {
                builder.style.display = 'none';
            } else {
                builder.style.display = 'block';
            }
        }

        function prepareSubmit() {
            syncFieldsFromDOM();
            document.getElementById('fieldsJson').value = JSON.stringify(fields);
        }

        document.getElementById('templateForm').addEventListener('submit', function () {
            prepareSubmit();
        });

        addField({
            label: 'Full Name',
            type: 'text',
            required: true,
            options: []
        });

        toggleBuilder();
    </script>
</x-app-layout>