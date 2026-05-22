<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">Buat Template Form</h2>
                <p class="text-sm text-muted-foreground mt-1">{{ $competition->name }}</p>
            </div>
            <a href="{{ route('committee.form-templates.index', $competition) }}" class="btn btn-secondary text-sm">
                ← Kembali ke List
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-2 border-black rounded-xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-red-700">
                    <h4 class="font-bold text-sm mb-2">❌ Perbaiki Masalah Berikut:</h4>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left Column: Form Builder -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="card bg-white p-6">
                        <form id="templateForm" method="POST"
                            action="{{ route('committee.form-templates.store', $competition) }}">
                            @csrf

                            <div class="mb-4">
                                <x-input-label for="name" :value="__('Nama Template')" />
                                <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
                                    value="{{ old('name') }}" required autofocus placeholder="Contoh: Form Registrasi Web Dev" />
                            </div>

                            <div class="mb-6">
                                <x-input-label for="clone_from" :value="__('Gunakan Ulang Template Lain')" />
                                <select id="clone_from" name="clone_from"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                    onchange="toggleBuilder()">
                                    <option value="">-- Buat Form Baru Secara Manual --</option>
                                    @forelse($existingTemplates as $template)
                                        <option value="{{ $template->id }}" {{ old('clone_from') == $template->id ? 'selected' : '' }}>
                                            {{ $template->name }} — {{ $template->competition?->name }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Tidak ada template lain yang tersedia</option>
                                    @endforelse
                                </select>
                                <p class="text-xs text-muted-foreground mt-1">
                                    Pilih opsi ini untuk menduplikat struktur form dari kompetisi lain.
                                </p>
                            </div>

                            <div id="manualBuilder">
                                <h3 class="font-bold text-gray-800 mb-3 border-b pb-2">📋 Fields Form</h3>

                                <div id="fieldsList" class="space-y-3 mb-4"></div>

                                <button type="button" onclick="addField()"
                                    class="w-full border-2 border-dashed border-gray-400 text-gray-600 px-4 py-3 rounded-lg hover:bg-gray-50 font-bold transition">
                                    + Tambah Field Baru
                                </button>
                            </div>

                            <input type="hidden" name="fields" id="fieldsJson" />

                            <div class="flex justify-end gap-3 mt-8 pt-4 border-t-2 border-black">
                                <a href="{{ route('committee.form-templates.index', $competition) }}"
                                    class="btn btn-ghost">
                                    Batal
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    Simpan Template
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Smart Preview & Analyzer -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Session / Post-save template warnings -->
                    @if(session('template_warnings'))
                        <div class="bg-yellow-50 border-2 border-black p-4 rounded-xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                            <h4 class="font-bold text-sm text-yellow-800 mb-2">⚠️ Peringatan Kualitas Template (Tersimpan)</h4>
                            <ul class="list-disc list-inside text-xs text-yellow-700 space-y-1">
                                @foreach(session('template_warnings') as $warning)
                                    <li>
                                        <strong>{{ $warning['field'] ? '['.$warning['field'].']' : '' }}</strong>
                                        {{ $warning['message'] }}
                                        @if($warning['suggestion'])
                                            <span class="block mt-0.5 text-gray-500 italic">Saran: {{ $warning['suggestion'] }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Dynamic Quality Warnings -->
                    <div id="qualityWarningsContainer" class="hidden">
                        <div class="p-3 bg-yellow-50 border-2 border-black rounded-xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center justify-between mb-3">
                            <span class="font-bold text-sm flex items-center gap-1">
                                ⚠️ Analisis Kualitas: <span id="warningSummary" class="text-danger">0 Masalah</span>
                            </span>
                            <span id="warningBadge" class="text-xs font-mono font-bold bg-white border border-black px-2 py-0.5 rounded">Aman</span>
                        </div>
                        <div id="warningsList" class="space-y-2 max-h-[250px] overflow-y-auto pr-1">
                            <!-- JS will render warnings dynamically -->
                        </div>
                    </div>

                    <!-- Simulator Mockup Panel -->
                    <div class="card bg-white p-6" id="simulatorPanel">
                        <div class="flex items-center justify-between mb-4 border-b-2 border-black pb-2">
                            <h3 class="font-bold text-lg">📱 Simulator Form</h3>
                            <div class="flex items-center border-2 border-black rounded-lg overflow-hidden bg-gray-100 font-mono text-xs">
                                <button type="button" id="toggleMobile" onclick="setViewport('mobile')" class="px-3 py-1 bg-black text-white font-bold transition">Mobile</button>
                                <button type="button" id="toggleDesktop" onclick="setViewport('desktop')" class="px-3 py-1 text-black transition">Desktop</button>
                            </div>
                        </div>

                        <!-- Mobile Mockup Frame -->
                        <div id="mobileMockup" class="phone-frame border-4 border-black rounded-[2rem] p-4 bg-gray-100 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] relative mx-auto" style="max-width: 340px; min-height: 520px;">
                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 h-5 w-32 bg-black rounded-b-xl z-10"></div>
                            <div class="bg-white rounded-[1.5rem] p-3 pt-6 min-h-[450px] max-h-[450px] border-2 border-black overflow-y-auto preview-content-area text-sm">
                                <!-- Rendered fields go here -->
                            </div>
                        </div>

                        <!-- Desktop Mockup Frame -->
                        <div id="desktopMockup" class="browser-mockup border-4 border-black rounded-xl bg-white shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] overflow-hidden hidden">
                            <div class="bg-gray-100 border-b-2 border-black p-2 flex items-center gap-2">
                                <div class="flex gap-1">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-400 border border-black"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 border border-black"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-green-400 border border-black"></span>
                                </div>
                                <div class="flex-1 bg-white border border-black rounded text-[10px] px-2 py-0.5 font-mono text-gray-500 select-none">
                                    http://competehub.test/competitions/{{ $competition->id }}/register
                                </div>
                            </div>
                            <div class="p-6 bg-white overflow-y-auto preview-content-area" style="max-height: 380px; min-height: 320px;">
                                <!-- Rendered fields go here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let fields = [];
        const fieldTypes = ['text', 'email', 'number', 'textarea', 'file', 'select', 'checkbox', 'date'];
        let updateTimeout;

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
                row.className = 'p-4 bg-gray-50 rounded-lg border-2 border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] relative transition mb-3';
                row.dataset.index = index;

                row.innerHTML = `
                    <div class="flex items-start gap-3">
                        <div class="flex-1">
                            <label class="block text-xs font-mono font-bold text-gray-700 mb-1">LABEL FIELD</label>
                            <input
                                type="text"
                                placeholder="Masukkan label field, misal: Link Portofolio"
                                value="${escapeHtml(field.label)}"
                                data-field="label"
                                oninput="fields[${index}].label = this.value; triggerPreviewUpdate();"
                                class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <div class="w-36">
                            <label class="block text-xs font-mono font-bold text-gray-700 mb-1">TIPE FIELD</label>
                            <select
                                data-field="type"
                                onchange="fields[${index}].type = this.value; syncFieldsFromDOM(); renderFields();"
                                class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-semibold"
                            >
                                ${fieldTypes.map((type) => `
                                    <option value="${type}" ${field.type === type ? 'selected' : ''}>${type.toUpperCase()}</option>
                                `).join('')}
                            </select>
                        </div>

                        <div class="pt-6 flex items-center">
                            <label class="flex items-center text-sm font-semibold text-gray-700 whitespace-nowrap cursor-pointer">
                                <input
                                    type="checkbox"
                                    data-field="required"
                                    ${field.required ? 'checked' : ''}
                                    onchange="fields[${index}].required = this.checked; triggerPreviewUpdate();"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-1.5"
                                />
                                Wajib diisi
                            </label>
                        </div>

                        <button
                            type="button"
                            onclick="removeField(${index})"
                            class="text-red-500 hover:text-red-700 text-2xl font-bold flex-shrink-0 pt-5 ml-2 hover:scale-110 transition"
                            title="Hapus Field"
                        >
                            &times;
                        </button>
                    </div>

                    ${field.type === 'select' ? `
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <label class="block text-xs font-mono font-bold text-gray-700 mb-1">PILIHAN (Pisahkan dengan koma)</label>
                            <input
                                type="text"
                                placeholder="Contoh: Pemula, Menengah, Profesional"
                                value="${escapeHtml((field.options || []).join(', '))}"
                                data-field="options"
                                oninput="fields[${index}].options = this.value.split(',').map(item => item.trim()).filter(item => item.length > 0); triggerPreviewUpdate();"
                                class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                    ` : ''}
                `;

                container.appendChild(row);
            });

            triggerPreviewUpdate();
        }

        function triggerPreviewUpdate() {
            clearTimeout(updateTimeout);
            updateTimeout = setTimeout(() => {
                syncFieldsFromDOM();

                const fieldsJson = JSON.stringify(fields);

                fetch("{{ route('committee.form-templates.preview', $competition) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ fields: fieldsJson })
                })
                .then(response => response.json())
                .then(data => {
                    // Update preview content areas
                    document.querySelectorAll('.preview-content-area').forEach(container => {
                        container.innerHTML = data.html;
                    });

                    // Render warnings
                    const warningsContainer = document.getElementById('qualityWarningsContainer');
                    const warningsList = document.getElementById('warningsList');
                    const warningSummary = document.getElementById('warningSummary');
                    const warningBadge = document.getElementById('warningBadge');

                    warningsList.innerHTML = '';

                    if (data.warnings.length > 0) {
                        warningsContainer.classList.remove('hidden');

                        let errorCount = data.summary.errors;
                        let warnCount = data.summary.warnings;
                        let infoCount = data.summary.infos;

                        let summaryText = [];
                        if (errorCount > 0) summaryText.push(`${errorCount} Error`);
                        if (warnCount > 0) summaryText.push(`${warnCount} Warning`);
                        if (infoCount > 0) summaryText.push(`${infoCount} Info`);

                        warningSummary.innerText = summaryText.join(', ');

                        if (errorCount > 0) {
                            warningBadge.className = "text-xs font-mono font-bold bg-red-500 text-white border border-black px-2 py-0.5 rounded";
                            warningBadge.innerText = "Error (Blokir Simpan)";
                        } else if (warnCount > 0) {
                            warningBadge.className = "text-xs font-mono font-bold bg-yellow-500 text-black border border-black px-2 py-0.5 rounded";
                            warningBadge.innerText = "Warning";
                        } else {
                            warningBadge.className = "text-xs font-mono font-bold bg-blue-500 text-white border border-black px-2 py-0.5 rounded";
                            warningBadge.innerText = "Info";
                        }

                        data.warnings.forEach(w => {
                            const warningDiv = document.createElement('div');
                            warningDiv.className = `p-3 rounded-lg border-2 border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-xs flex flex-col gap-1`;

                            let bg = 'bg-blue-50';
                            let emoji = '🔵';
                            if (w.severity === 'error') {
                                bg = 'bg-red-50';
                                emoji = '🔴';
                            } else if (w.severity === 'warning') {
                                bg = 'bg-yellow-50';
                                emoji = '🟡';
                            }

                            warningDiv.classList.add(bg);

                            warningDiv.innerHTML = `
                                <div class="flex items-start gap-2">
                                    <span>${emoji}</span>
                                    <div>
                                        <span class="font-bold">${w.field ? `[Field: ${w.field}] ` : ''}</span>
                                        <span>${w.message}</span>
                                    </div>
                                </div>
                                ${w.suggestion ? `<div class="mt-1 font-semibold text-gray-700">💡 Saran: ${w.suggestion}</div>` : ''}
                            `;

                            warningsList.appendChild(warningDiv);
                        });
                    } else {
                        warningsContainer.classList.add('hidden');
                    }
                })
                .catch(err => console.error('Error fetching preview:', err));
            }, 400); // 400ms debounce
        }

        function setViewport(mode) {
            const mobileBtn = document.getElementById('toggleMobile');
            const desktopBtn = document.getElementById('toggleDesktop');
            const mobileMock = document.getElementById('mobileMockup');
            const desktopMock = document.getElementById('desktopMockup');

            if (mode === 'mobile') {
                mobileBtn.className = 'px-3 py-1 bg-black text-white font-bold transition';
                desktopBtn.className = 'px-3 py-1 text-black transition';
                mobileMock.classList.remove('hidden');
                desktopMock.classList.add('hidden');
            } else {
                desktopBtn.className = 'px-3 py-1 bg-black text-white font-bold transition';
                mobileBtn.className = 'px-3 py-1 text-black transition';
                desktopMock.classList.remove('hidden');
                mobileMock.classList.add('hidden');
            }
        }

        function toggleBuilder() {
            const cloneValue = document.getElementById('clone_from').value;
            const builder = document.getElementById('manualBuilder');
            const simulatorPanel = document.getElementById('simulatorPanel');

            if (cloneValue) {
                builder.style.display = 'none';
                simulatorPanel.style.display = 'none';
            } else {
                builder.style.display = 'block';
                simulatorPanel.style.display = 'block';
                triggerPreviewUpdate();
            }
        }

        function prepareSubmit() {
            syncFieldsFromDOM();
            document.getElementById('fieldsJson').value = JSON.stringify(fields);
        }

        document.getElementById('templateForm').addEventListener('submit', function (e) {
            prepareSubmit();
        });

        // Initialize with one default field
        addField({
            label: 'Nama Lengkap',
            type: 'text',
            required: true,
            options: []
        });

        toggleBuilder();
    </script>
</x-app-layout>