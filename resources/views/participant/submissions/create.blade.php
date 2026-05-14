<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Submission') }}
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

                    @if($submission)
                        <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
                            <p class="font-semibold">You have already submitted a file for this round.</p>
                            <p class="text-sm mt-1">Status: {{ ucfirst($submission->status) }}</p>
                            <p class="text-sm">Uploading a new file will replace your previous submission.</p>
                        </div>
                    @endif

                    <form id="submissionForm" action="{{ route('participant.submissions.store', [$competition, $round]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="submission_file" :value="__('Submission File (PDF, ZIP, MP4 - Max 20MB)')" />
                            <input type="file" name="submission_file" id="submission_file" class="mt-1 block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100" required>
                            <x-input-error :messages="$errors->get('submission_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('participant.submissions.index', $competition) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Upload Submission') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('submissionForm').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('submission_file');
        const file = fileInput.files[0];
        
        if (file) {
            // Check file size (20MB = 20 * 1024 * 1024 bytes)
            const maxSize = 20 * 1024 * 1024;
            if (file.size > maxSize) {
                e.preventDefault();
                alert('File terlalu besar! Ukuran maksimal adalah 20 MB. Anda mengupload file berukuran ' + (file.size / (1024*1024)).toFixed(2) + ' MB.');
            }
        }
    });
</script>
