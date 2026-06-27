<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Kompetisi: ') }} {{ $competition->name }}
            </h2>
            <a href="{{ route('home') }}" class="text-sm font-bold underline hover:no-underline">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f7f9f3] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border-[3px] border-black rounded-[1rem] p-8 shadow-[8px_8px_0px_rgba(0,0,0,1)] mb-8">
                
                @if($competition->banner_url)
                    <div class="w-full h-64 border-[3px] border-black rounded-lg mb-6 overflow-hidden">
                        <img src="{{ asset('storage/' . $competition->banner_url) }}" alt="{{ $competition->name }}" class="w-full h-full object-cover">
                    </div>
                @endif
                
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-4xl font-black uppercase mb-2">{{ $competition->name }}</h1>
                        <p class="font-mono text-gray-700 bg-gray-100 border-2 border-black inline-block px-3 py-1 rounded-sm text-sm">
                            Penyelenggara: {{ $competition->creator->name ?? 'Committee' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="font-mono text-xl font-bold bg-[#FFED35] border-2 border-black px-4 py-2 rounded-sm shadow-[2px_2px_0px_rgba(0,0,0,1)] inline-block">
                            {{ $competition->type === 'team' ? 'Tim' : 'Individu' }}
                        </div>
                    </div>
                </div>

                <div class="prose max-w-none font-sans mb-8 text-lg">
                    <p>{{ $competition->description }}</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 font-mono text-sm border-t-2 border-b-2 border-black py-4">
                    <div>
                        <span class="block text-gray-500 font-sans font-bold">Biaya Pendaftaran</span>
                        <span class="block font-bold text-lg">{{ $competition->registration_fee > 0 ? 'Rp ' . number_format($competition->registration_fee, 0, ',', '.') : 'Gratis' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 font-sans font-bold">Kuota</span>
                        <span class="block font-bold text-lg">{{ $competition->quota ?? 'Unlimited' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 font-sans font-bold">Mulai Daftar</span>
                        <span class="block font-bold">{{ $competition->registration_start ? $competition->registration_start->format('d M Y') : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 font-sans font-bold">Tutup Daftar</span>
                        <span class="block font-bold text-red-600">{{ $competition->registration_end ? $competition->registration_end->format('d M Y') : '-' }}</span>
                    </div>
                </div>
                
                @if($competition->rules)
                <div class="mb-8">
                    <h3 class="text-2xl font-black uppercase mb-2">Peraturan</h3>
                    <div class="bg-gray-50 border-2 border-black p-4 rounded-lg font-mono whitespace-pre-line text-sm">
                        {{ $competition->rules }}
                    </div>
                </div>
                @endif
                
                @if($competition->isRegistrationOpen() && $competition->hasAvailableQuota())
                    <a href="{{ route('participant.registrations.create', $competition->id) }}" class="block w-full text-center bg-black text-white border-2 border-black rounded-lg py-4 font-black uppercase text-xl hover:-translate-y-1 hover:shadow-[6px_6px_0px_rgba(255,237,53,1)] transition-all">
                        Daftar Kompetisi Ini
                    </a>
                @else
                    <button disabled class="w-full bg-gray-300 text-gray-600 border-2 border-gray-400 rounded-lg py-4 font-black uppercase text-xl cursor-not-allowed">
                        Pendaftaran Ditutup
                    </button>
                @endif

            </div>

            <!-- Rounds Section -->
            @if($competition->rounds->count() > 0)
            <div class="mb-8">
                <h3 class="text-3xl font-black uppercase mb-6 tracking-tight">Jadwal & Babak</h3>
                <div class="space-y-4">
                    @foreach($competition->rounds as $round)
                        <div class="bg-white border-[3px] border-black rounded-lg p-6 flex flex-col md:flex-row justify-between items-start md:items-center shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:shadow-[6px_6px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all">
                            <div class="mb-4 md:mb-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="bg-black text-white font-black px-3 py-1 rounded-sm font-mono text-sm">Babak {{ $round->round_order }}</span>
                                    <h4 class="text-2xl font-bold">{{ $round->name }}</h4>
                                </div>
                                <div class="font-mono text-sm text-gray-600 mt-2">
                                    {{ $round->start_date ? $round->start_date->format('d M Y H:i') : '-' }} s/d {{ $round->end_date ? $round->end_date->format('d M Y H:i') : '-' }}
                                    <span class="mx-2">|</span> 
                                    <span class="font-bold">Sistem Penilaian: {{ $round->scoringType->name ?? 'Manual' }}</span>
                                </div>
                            </div>
                            
                            @if($round->scoringType && $round->scoringType->name === 'Community Voting')
                                <div>
                                    <a href="{{ route('community.gallery', [$competition, $round]) }}" class="inline-block bg-[#FFED35] border-2 border-black font-black uppercase px-6 py-3 rounded-full hover:bg-black hover:text-[#FFED35] transition-colors shadow-[2px_2px_0px_rgba(0,0,0,1)]">
                                        Lihat Karya & Vote
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
