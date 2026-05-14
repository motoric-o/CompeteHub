<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Form Templates') }} — {{ $competition->name }}
            </h2>
            <a href="{{ route('committee.form-templates.create', $competition) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                + New Template
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @forelse($templates as $template)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6 flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $template->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ count($template->fields) }} field(s) &middot;
                                Updated {{ $template->updated_at->diffForHumans() }}
                            </p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($template->fields as $field)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $field['label'] ?? 'Unnamed' }}
                                        <span class="ml-1 text-gray-400">({{ $field['type'] ?? 'text' }})</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('committee.form-templates.edit', [$competition, $template]) }}"
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</a>
                            <form method="POST" action="{{ route('committee.form-templates.destroy', [$competition, $template]) }}"
                                  onsubmit="return confirm('Delete this template?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-500 text-center">
                        No form templates yet. Create one to start accepting registrations.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
