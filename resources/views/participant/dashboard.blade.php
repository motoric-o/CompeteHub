<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Dashboard Peserta</h2>
    </x-slot>

    <div class="py-6">
        <!-- Welcome card -->
        <div class="card mb-8 flex items-center gap-6" style="background: var(--primary);">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--card); border: 2px solid var(--border); color: var(--foreground); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; flex-shrink: 0; box-shadow: 2px 2px 0px 0px var(--foreground);">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-1" style="color: var(--primary-foreground);">Selamat datang, {{ auth()->user()->name }}!</h3>
                <p class="text-sm font-medium" style="color: var(--primary-foreground); opacity: 0.9;">Kamu login sebagai <strong>Peserta</strong>. Siap untuk kompetisi hari ini?</p>
            </div>
        </div>

        <!-- Action cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('participant.competitions.index') }}" class="card block group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full border border-border flex items-center justify-center bg-secondary text-secondary-foreground group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>
                    <h4 class="text-xl font-bold m-0">Cari Kompetisi</h4>
                </div>
                <p class="text-muted-foreground m-0">Eksplorasi dan ikuti berbagai kompetisi seru yang sesuai dengan minat dan bakatmu.</p>
                <div class="mt-4 flex items-center text-sm font-bold text-accent group-hover:translate-x-1 transition-transform">
                    Mulai Mencari <svg class="ml-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </div>
            </a>

            <a href="{{ route('participant.registrations.index') }}" class="card block group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full border border-border flex items-center justify-center" style="background: var(--chart-4); color: #fff;" class="group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                    </div>
                    <h4 class="text-xl font-bold m-0">Pendaftaranku</h4>
                </div>
                <p class="text-muted-foreground m-0">Pantau status pendaftaran, perbarui dokumen, dan cek progres kompetisimu.</p>
                <div class="mt-4 flex items-center text-sm font-bold group-hover:translate-x-1 transition-transform" style="color: var(--chart-4);">
                    Lihat Status <svg class="ml-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </div>
            </a>

            <a href="{{ route('leaderboards.list') }}" class="card block group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full border border-border flex items-center justify-center bg-accent text-accent-foreground group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold m-0">Leaderboard</h4>
                </div>
                <p class="text-muted-foreground m-0">Lihat peringkatmu dibandingkan peserta lain dan pantau perkembangan kompetitifmu.</p>
                <div class="mt-4 flex items-center text-sm font-bold text-accent group-hover:translate-x-1 transition-transform">
                    Cek Peringkat <svg class="ml-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </div>
            </a>

            <a href="{{ route('teams.index') }}" class="card block group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full border border-border flex items-center justify-center text-white group-hover:scale-110 transition-transform" style="background: var(--success);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h4 class="text-xl font-bold m-0">Tim Saya</h4>
                </div>
                <p class="text-muted-foreground m-0">Kelola anggota tim, buat tim baru, dan undang teman untuk kompetisi beregu.</p>
                <div class="mt-4 flex items-center text-sm font-bold group-hover:translate-x-1 transition-transform" style="color: var(--success);">
                    Kelola Tim <svg class="ml-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
