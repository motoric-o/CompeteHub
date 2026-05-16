<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Dashboard Panitia</h2>
    </x-slot>

    <div class="py-6">
        <!-- Welcome card -->
        <div class="card mb-8 flex items-center gap-6" style="background: var(--primary);">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--card); border: 2px solid var(--border); color: var(--foreground); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; flex-shrink: 0; box-shadow: 2px 2px 0px 0px var(--foreground);">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-1" style="color: var(--primary-foreground);">Selamat datang, {{ auth()->user()->name }}!</h3>
                <p class="text-sm font-medium" style="color: var(--primary-foreground); opacity: 0.9;">Kamu punya akses penuh untuk mengelola kompetisi dan memverifikasi peserta.</p>
            </div>
        </div>

        <!-- Action cards grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Manage Competitions -->
            <a href="{{ route('committee.management.competitions.index') }}" class="card block group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full border border-border flex items-center justify-center bg-secondary text-secondary-foreground group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h10"/><path d="M7 12h10"/><path d="M7 17h10"/></svg>
                    </div>
                    <h4 class="text-xl font-bold m-0">Kelola Kompetisi</h4>
                </div>
                <p class="text-muted-foreground m-0">Buat, edit, dan atur seluruh parameter serta persyaratan kompetisi.</p>
                <div class="mt-4 flex items-center text-sm font-bold text-secondary group-hover:translate-x-1 transition-transform">
                    Mulai Mengelola <svg class="ml-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </div>
            </a>

            <!-- Form Templates -->
            <a href="{{ route('committee.management.competitions.index') }}" class="card block group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full border border-border flex items-center justify-center text-white group-hover:scale-110 transition-transform" style="background: var(--chart-4);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                    </div>
                    <h4 class="text-xl font-bold m-0">Formulir Pendaftaran</h4>
                </div>
                <p class="text-muted-foreground m-0">Desain formulir pendaftaran secara dinamis dengan form builder.</p>
                <div class="mt-4 flex items-center text-sm font-bold group-hover:translate-x-1 transition-transform" style="color: var(--chart-4);">
                    Buat Formulir <svg class="ml-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </div>
            </a>

            <!-- Verify Registrations -->
            <a href="{{ route('committee.management.competitions.index') }}" class="card block group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full border border-border flex items-center justify-center bg-success text-white group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    </div>
                    <h4 class="text-xl font-bold m-0">Verifikasi Peserta</h4>
                </div>
                <p class="text-muted-foreground m-0">Tinjau dokumen, verifikasi pembayaran, dan setujui pendaftaran peserta.</p>
                <div class="mt-4 flex items-center text-sm font-bold text-success group-hover:translate-x-1 transition-transform">
                    Verifikasi Sekarang <svg class="ml-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </div>
            </a>

            <!-- Broadcast email -->
            <a href="{{ route('broadcast.create') }}" class="card block group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full border border-border flex items-center justify-center bg-accent text-accent-foreground group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    </div>
                    <h4 class="text-xl font-bold m-0">Kirim Email Broadcast</h4>
                </div>
                <p class="text-muted-foreground m-0">Kirim pengumuman penting secara massal kepada seluruh peserta kompetisi.</p>
                <div class="mt-4 flex items-center text-sm font-bold text-accent group-hover:translate-x-1 transition-transform">
                    Kirim Email <svg class="ml-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
