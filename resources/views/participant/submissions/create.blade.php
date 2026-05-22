<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $submission ? __('Revisi Submission') : __('Upload Submission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800">{{ $competition->name }}</h3>
                        <p class="text-sm text-gray-500">Round: {{ $round->name }}</p>
                    </div>

                    {{-- Time Bonus Info --}}
                    @if($bonusPreview['is_revision'])
                        <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <h4 class="font-semibold text-gray-900">Revisi — Time Bonus Dipertahankan</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Revisi tidak akan mereset atau mengubah time bonus yang sudah Anda dapatkan di awal.
                            </p>
                            <div class="mt-4 grid grid-cols-3 gap-4">
                                <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Bonus Saat Ini</span>
                                    <span class="text-xl font-bold text-gray-900">+{{ $bonusPreview['current_bonus'] }}</span>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Setelah Revisi</span>
                                    <span class="text-xl font-bold text-gray-900">+{{ $bonusPreview['next_bonus'] }}</span>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Sisa Revisi</span>
                                    <span class="text-xl font-bold {{ $bonusPreview['revisions_left'] > 0 ? 'text-gray-900' : 'text-red-600' }}">{{ $bonusPreview['revisions_left'] }}</span>
                                    <span class="text-xs text-gray-400">/{{ $bonusPreview['max_revisions'] }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <h4 class="font-semibold text-gray-900">Time Bonus (Otomatis oleh Sistem)</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                1/3 tercepat dari {{ $bonusPreview['total_registrants'] }} pendaftar mendapat time bonus (max {{ $bonusPreview['max_time_bonus'] }} pts).
                                Submit lebih awal = bonus lebih besar! Time bonus Anda tidak akan hilang meskipun nanti direvisi.
                            </p>
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Estimasi Time Bonus</span>
                                    <span class="text-xl font-bold text-gray-900">+{{ $bonusPreview['next_bonus'] }}</span>
                                    <span class="text-xs text-gray-400">/{{ $bonusPreview['max_time_bonus'] }} max</span>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Maks Revisi</span>
                                    <span class="text-xl font-bold text-gray-900">{{ $bonusPreview['max_revisions'] }}×</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($submission)
                        <div class="mb-6 bg-white border border-gray-200 text-gray-800 px-4 py-3 rounded-md">
                            <p class="font-semibold text-gray-900">Anda sudah submit file untuk round ini.</p>
                            <div class="text-sm mt-2 space-y-1">
                                <p><span class="text-gray-500">Status:</span> {{ ucfirst($submission->status) }}</p>
                                <p><span class="text-gray-500">Revisi ke:</span> {{ $submission->revision_count }} dari {{ $bonusPreview['max_revisions'] }}</p>
                            </div>
                            <p class="text-sm mt-3 text-gray-500">Upload file baru akan menggantikan submission sebelumnya.</p>
                        </div>
                    @endif

                    <form id="submissionForm" action="{{ route('participant.submissions.store', [$competition, $round]) }}" method="POST" enctype="multipart/form-data" class="mt-6 border-t pt-6">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block font-semibold text-gray-800 mb-2">Option 1: Upload File</label>
                            @php
                                $allowedFormats = 'PDF, ZIP, MP4';
                                if (!empty($competition->allowed_file_types)) {
                                    $allowedFormats = is_array($competition->allowed_file_types) 
                                        ? implode(', ', $competition->allowed_file_types) 
                                        : $competition->allowed_file_types;
                                }
                            @endphp
                            <p class="text-xs text-gray-500 mb-2">Allowed formats: {{ strtoupper($allowedFormats) }}. Max 20MB.</p>
                            <input type="file" name="submission_file" id="submission_file" class="block w-full border-2 border-black rounded-md p-2 bg-white cursor-pointer hover:bg-gray-50 focus:ring-primary transition-all duration-200">
                            <x-input-error :messages="$errors->get('submission_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center my-6">
                            <div class="flex-grow border-t border-gray-300"></div>
                            <span class="flex-shrink-0 mx-4 text-gray-400 font-medium text-sm">OR</span>
                            <div class="flex-grow border-t border-gray-300"></div>
                        </div>

                        <div class="mb-4">
                            <label for="submission_url" class="block font-semibold text-gray-800 mb-2">Option 2: Submit URL</label>
                            <p class="text-xs text-gray-500 mb-2">E.g., GitHub repository, Figma link, YouTube video.</p>
                            <input type="url" name="submission_url" id="submission_url" placeholder="https://..." class="block w-full border-2 border-black rounded-md p-2 bg-white focus:ring-primary focus:border-black transition-all duration-200">
                            <x-input-error :messages="$errors->get('submission_url')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('participant.submissions.index', $competition) }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium mr-4">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-[#FFED35] border-2 border-black rounded-[1rem] font-bold text-black uppercase tracking-widest hover:-translate-y-[2px] shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:shadow-[6px_6px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                {{ $submission ? 'Upload Revisi' : 'Upload Submission' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('submissionForm').addEventListener('submit', function(e) {
        const file = document.getElementById('submission_file').files[0];
        const url = document.getElementById('submission_url').value;
        if (file && file.size > 20 * 1024 * 1024) {
            e.preventDefault();
            alert('File terlalu besar! Maks 20 MB.');
            return;
        }
        if (!file && !url) {
            e.preventDefault();
            alert('Silakan pilih file atau masukkan URL.');
        }
    });
</script>
