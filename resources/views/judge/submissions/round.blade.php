<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $competition->name }} — {{ $round->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-status class="mb-4 text-red-600 bg-red-100 p-4 rounded" :status="session('error')" />

            <div class="mb-4 flex items-center justify-between">
                <a href="{{ route('judge.submissions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Kembali ke Daftar Kompetisi
                </a>
                <a href="{{ route('leaderboard.index', $competition) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Leaderboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-0">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-50 border-b border-gray-200 text-gray-700 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 w-12 font-semibold">#</th>
                                <th class="px-6 py-4 font-semibold">Peserta</th>
                                <th class="px-6 py-4 text-center font-semibold">File</th>
                                <th class="px-6 py-4 text-center font-semibold">Submitted</th>
                                <th class="px-6 py-4 text-center font-semibold">Time Bonus</th>
                                <th class="px-6 py-4 text-center font-semibold">Nilai Saya</th>
                                <th class="px-6 py-4 text-center font-semibold">Avg Score</th>
                                <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $idx => $submission)
                                @php
                                    $myScore = $submission->scores->first(); // already filtered in controller
                                @endphp
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500">{{ $idx + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ $submission->team ? $submission->team->name : ($submission->user ? $submission->user->name : '-') }}
                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-gray-500">{{ $submission->team_id ? 'Tim' : 'Individu' }}</span>
                                            @if($submission->revision_count > 0)
                                                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded">Rev #{{ $submission->revision_count }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($submission->file_path)
                                            <a href="{{ Storage::url($submission->file_path) }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                                Download
                                            </a>
                                            <div class="text-xs text-gray-500 mt-0.5">.{{ $submission->file_type }}</div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600">
                                        {{ $submission->submitted_at?->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm {{ ($submission->time_bonus ?? 0) > 0 ? 'text-green-600 font-medium' : 'text-gray-500' }}">
                                            +{{ $submission->time_bonus ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($myScore)
                                            <span class="text-base font-bold text-gray-900">{{ $myScore->score }}</span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($submission->final_score !== null)
                                            <span class="font-medium text-gray-900">{{ $submission->final_score }}</span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('judge.submissions.show', [$competition, $submission]) }}"
                                           class="inline-flex items-center px-4 py-2 rounded border text-xs font-semibold transition
                                               {{ $myScore ? 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' : 'bg-blue-600 border-transparent text-white hover:bg-blue-700' }}">
                                            {{ $myScore ? 'Edit Nilai' : 'Beri Nilai' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        Belum ada submisi untuk round ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
