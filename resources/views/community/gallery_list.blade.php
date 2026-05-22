<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Community Galleries') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm font-bold underline hover:no-underline">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f7f9f3] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h3 class="text-3xl font-black text-black uppercase tracking-tight">Galeri & Voting</h3>
                <p class="text-gray-600 font-mono mt-2">Daftar kompetisi yang sedang membuka public voting. Berikan suaramu untuk karya terbaik!</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($rounds as $round)
                    <div class="bg-white border-[3px] border-black rounded-[1rem] p-6 shadow-[6px_6px_0px_rgba(0,0,0,1)] hover:-translate-y-[4px] hover:shadow-[10px_10px_0px_rgba(0,0,0,1)] transition-all duration-200 flex flex-col">
                        <div class="flex-grow">
                            <div class="mb-4">
                                <span class="bg-black text-white font-black px-3 py-1 rounded-sm font-mono text-xs uppercase mb-2 inline-block">
                                    {{ $round->competition->type === 'team' ? 'Tim' : 'Individu' }}
                                </span>
                                <h4 class="font-bold text-2xl text-black mb-1">
                                    {{ $round->competition->name }}
                                </h4>
                                <p class="text-sm font-mono font-bold text-gray-600 mb-4">
                                    Babak: {{ $round->name }}
                                </p>
                                <p class="text-sm text-gray-600 line-clamp-3">
                                    {{ $round->competition->description }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t-[3px] border-black">
                            <a href="{{ route('community.gallery', [$round->competition, $round]) }}" class="block w-full text-center bg-[#FFED35] border-2 border-black rounded-lg py-3 font-black uppercase transition-colors hover:bg-black hover:text-[#FFED35]">
                                Masuk ke Galeri
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center border-[3px] border-black rounded-[1rem] bg-white shadow-[6px_6px_0px_rgba(0,0,0,1)]">
                        <h3 class="text-2xl font-bold mb-2">Belum ada galeri yang terbuka</h3>
                        <p class="font-mono text-gray-600">Saat ini tidak ada kompetisi yang mengaktifkan sistem voting publik.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
