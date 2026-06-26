<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">{{ __('Jelajahi Kompetisi') }}</h2>
        <p class="page-subtitle">Pilih kompetisi seru yang ingin kamu ikuti.</p>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Category Filter Header -->
            <div class="card mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-sm text-muted-foreground uppercase tracking-wider mb-1">Filter Kategori</h3>
                    <p class="text-xs text-muted-foreground m-0">Saring kompetisi berdasarkan kategori yang kamu inginkan.</p>
                </div>
                <div class="flex gap-2 flex-wrap" style="font-family: var(--font-sans);">
                    @php
                        $currentCategory = request('category');
                        $categories = ['Web Development', 'Capture The Flag', 'UI/UX Design', 'Competitive Programming', 'Other'];
                    @endphp
                    <a href="{{ route('participant.competitions.index') }}" class="btn btn-sm {{ !$currentCategory ? 'btn-primary' : 'btn-outline bg-card' }}">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('participant.competitions.index', ['category' => $cat]) }}" class="btn btn-sm {{ $currentCategory === $cat ? 'btn-primary' : 'btn-outline bg-card' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Competitions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($competitions as $competition)
                    <div class="card flex flex-col overflow-hidden" style="padding: 0; display: flex; flex-direction: column;">
                        <!-- Solid Header Visual -->
                        <div class="h-28 border-b-2 border-border flex items-center justify-between px-6" style="background: var(--primary);">
                            <div class="font-mono text-xs font-bold uppercase tracking-wider bg-card text-foreground px-3 py-1 border-2 border-border rounded-full shadow-[2px_2px_0px_0px_var(--foreground)]">
                                {{ $competition->category }}
                            </div>
                            <div class="font-mono text-xs font-bold uppercase tracking-wider bg-secondary text-secondary-foreground px-3 py-1 border-2 border-border rounded-full shadow-[2px_2px_0px_0px_var(--foreground)]">
                                {{ $competition->type === 'team' ? 'Tim' : 'Individu' }}
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between" style="display: flex; flex-direction: column; flex-grow: 1;">
                            <div style="flex-grow: 1;">
                                <h3 class="text-xl font-bold text-foreground mb-2">{{ $competition->name }}</h3>
                                <p class="text-muted-foreground text-sm mb-6 line-clamp-3">{{ $competition->description }}</p>
                            </div>
                            
                            <div>
                                <div class="space-y-2 mb-6 border-t border-dashed border-border/50 pt-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-muted-foreground font-semibold">Biaya Pendaftaran:</span>
                                        <span class="font-bold text-foreground">{{ $competition->registration_fee > 0 ? 'Rp ' . number_format($competition->registration_fee, 0, ',', '.') : 'Gratis' }}</span>
                                    </div>
                                    @if($competition->registration_end)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-muted-foreground font-semibold">Deadline:</span>
                                            <span class="font-bold text-danger">{{ $competition->registration_end->format('d M Y') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <a href="{{ route('participant.registrations.create', $competition) }}" class="btn btn-primary w-full text-center py-3">
                                    Daftar Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full card text-center py-12" style="border-style: dashed;">
                        <div class="w-16 h-16 bg-muted border-2 border-border rounded-full flex items-center justify-center mx-auto mb-4 shadow-[2px_2px_0px_0px_var(--foreground)]">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-foreground mb-1">Tidak Ada Kompetisi Ditemukan</h3>
                        <p class="text-muted-foreground text-sm max-w-md mx-auto">Saat ini tidak ada kompetisi yang aktif dalam kategori ini. Silakan pilih kategori lain.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
