<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight tracking-tight">
            {{ __('Judge Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-purple-200">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ __('Welcome back, :name!', ['name' => auth()->user()->name]) }}</h3>
                            <p class="text-gray-500">{{ __('You are logged in as Judge / Jury.') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('judge.submissions.index') }}" class="block group cursor-pointer bg-gradient-to-br from-purple-50 to-white rounded-2xl p-6 border border-purple-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-purple-100">
                            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ __('Daftar Tugas Penilaian') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('Lihat daftar kompetisi dan round yang ditugaskan kepada Anda untuk dinilai.') }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
