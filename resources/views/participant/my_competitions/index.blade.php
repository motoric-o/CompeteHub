<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Kompetisiku</h2>
    </x-slot>

    <div class="py-6">
        <div class="mb-6 flex items-center justify-between">
            <p class="text-muted-foreground">Pilih kompetisi aktif yang sedang Anda ikuti untuk melihat detail, mengirimkan submission, dan melihat perkembangan tim.</p>
        </div>

        @if($registrations->isEmpty())
            <div class="card flex flex-col items-center justify-center py-12 text-center" style="background: var(--card);">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4 border border-border">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Belum ada kompetisi aktif</h3>
                <p class="text-muted-foreground mb-6 max-w-md">Anda belum terdaftar atau pendaftaran Anda belum diverifikasi di kompetisi manapun.</p>
                <a href="{{ route('participant.competitions.index') }}" class="btn btn-primary">
                    Cari Kompetisi
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($registrations as $registration)
                    @php
                        $comp = $registration->competition;
                    @endphp
                    <a href="{{ route('participant.my-competitions.show', $comp) }}" class="card block group hover:translate-y-[-4px] transition-transform">
                        <div class="flex items-center gap-3 mb-4">
                            @if($comp->logo_url)
                                <img src="{{ Storage::url($comp->logo_url) }}" alt="{{ $comp->name }}" class="w-12 h-12 rounded-full border border-border object-cover bg-white">
                            @else
                                <div class="w-12 h-12 rounded-full border border-border bg-gray-100 flex items-center justify-center font-bold text-gray-500">
                                    {{ substr($comp->name, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-lg leading-tight group-hover:text-primary transition-colors">{{ $comp->name }}</h3>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $comp->type === 'team' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }} border border-border mt-1 inline-block">
                                    {{ $comp->type === 'team' ? 'Tim' : 'Individu' }}
                                </span>
                            </div>
                        </div>

                        <div class="text-sm text-muted-foreground mb-4 line-clamp-2">
                            {{ $comp->description }}
                        </div>

                        <div class="flex justify-between items-center mt-auto pt-4 border-t border-border">
                            <span class="text-xs font-bold px-2 py-1 bg-yellow-100 text-yellow-800 rounded border border-border">
                                Active
                            </span>
                            <span class="text-sm font-bold text-primary flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                Buka Hub <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
