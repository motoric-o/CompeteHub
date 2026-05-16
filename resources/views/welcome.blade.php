<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'CompeteHub') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            .highlight-text {
                background-image: linear-gradient(to right, var(--primary), var(--secondary));
                background-repeat: no-repeat;
                background-position: left center;
                transition: background-size 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            }
        </style>
    </head>
    <body class="text-foreground antialiased min-h-screen font-sans relative"
          x-data="{ x: 0, y: 0, show: false }" 
          x-init="setTimeout(() => show = true, 500)"
          @mousemove="x = $event.clientX; y = $event.clientY">
          
        <!-- Global Background Effects -->
        <div class="fixed inset-0 pointer-events-none z-[-1] bg-background">
             <!-- Static dots -->
             <div class="absolute inset-0 opacity-40"
                  style="background-image: radial-gradient(circle, var(--foreground) 1px, transparent 1px); background-size: 24px 24px;"></div>
             
             <!-- Spotlight dots -->
             <div class="absolute inset-0 transition-opacity duration-300"
                  :style="`background-image: radial-gradient(circle, var(--accent) 1.5px, transparent 1.5px); background-size: 24px 24px; mask-image: radial-gradient(350px circle at ${x}px ${y}px, black 0%, transparent 100%); -webkit-mask-image: radial-gradient(350px circle at ${x}px ${y}px, black 0%, transparent 100%);`"></div>
        </div>

        <!-- Navbar -->
        <nav class="absolute top-0 left-0 right-0 z-50 flex justify-between items-center px-8 py-6 max-w-7xl mx-auto">
            <h1 class="text-2xl font-extrabold tracking-tight flex items-center gap-2">
                <div class="w-8 h-8 bg-primary text-primary-foreground flex items-center justify-center rounded-lg border-2 border-border shadow-[2px_2px_0px_0px_var(--foreground)]">C</div>
                CompeteHub
            </h1>
            <div class="flex gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline bg-card">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                    @endif
                @endauth
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative flex flex-col items-center justify-center w-full min-h-[40rem] border-b-2 border-border pt-20">
             <div class="relative z-20 text-center px-4 max-w-4xl mx-auto flex flex-col items-center">
                <h2 class="text-4xl md:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight tracking-tight text-foreground"
                    style="opacity: 0; animation: fadeInUp 0.8s ease-out forwards;">
                    Temukan dan Ikuti Berbagai Kompetisi 
                    <span class="highlight-text px-2 rounded-lg text-foreground mt-2"
                          :style="show ? 'background-size: 100% 100%' : 'background-size: 0% 100%'"
                          style="display: inline-block;">
                        Seru & Menantang
                    </span>
                </h2>
                <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto mb-10"
                   style="opacity: 0; animation: fadeInUp 0.8s ease-out 0.2s forwards;">
                    Bergabunglah dengan ribuan peserta lainnya. Bangun tim, tunjukkan keahlianmu, dan raih prestasi terbaikmu bersama CompeteHub.
                </p>
                <div style="opacity: 0; animation: fadeInUp 0.8s ease-out 0.4s forwards;">
                    <a href="#competitions" class="btn btn-primary text-lg px-8 py-4 rounded-xl border-2 shadow-[4px_4px_0px_0px_var(--foreground)] hover:shadow-[2px_2px_0px_0px_var(--foreground)] hover:translate-y-[2px] transition-all">Mulai Eksplorasi <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg></a>
                </div>
             </div>
        </div>

        <div class="container mx-auto px-8 py-20 max-w-7xl" id="competitions">
            <div class="mb-12 flex justify-between items-end">
                <div>
                    <h2 class="text-4xl font-extrabold mb-3">Kompetisi Terbaru</h2>
                    <p class="text-muted-foreground text-lg">Jangan sampai kelewatan kesempatan untuk bersinar.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($competitions as $competition)
                    <div class="card bg-card text-card-foreground border-2 border-border rounded-2xl p-0 shadow-[6px_6px_0px_0px_var(--foreground)] hover:shadow-[8px_8px_0px_0px_var(--accent)] hover:-translate-y-2 transition-all flex flex-col overflow-hidden">
                        @if($competition->banner_url)
                            <div class="w-full h-56 border-b-2 border-border relative overflow-hidden group">
                                <img src="{{ asset('storage/' . $competition->banner_url) }}" alt="{{ $competition->name }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @else
                            <div class="w-full h-56 bg-muted border-b-2 border-border flex items-center justify-center">
                                <svg class="w-12 h-12 text-muted-foreground opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="px-3 py-1 bg-secondary text-secondary-foreground text-xs font-bold border border-border rounded-full shadow-[2px_2px_0px_0px_var(--foreground)]">
                                    {{ $competition->type === 'team' ? 'Tim' : 'Individu' }}
                                </span>
                                @if(!$competition->isRegistrationOpen() || !$competition->hasAvailableQuota())
                                    <span class="px-3 py-1 bg-danger text-white text-xs font-bold border border-border rounded-full shadow-[2px_2px_0px_0px_var(--foreground)]">
                                        Ditutup
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="text-2xl font-bold mb-3">{{ $competition->name }}</h3>
                            <p class="text-sm text-muted-foreground line-clamp-3 mb-6 flex-grow">{{ $competition->description }}</p>
                            
                            @if($competition->isRegistrationOpen() && $competition->hasAvailableQuota())
                                <a href="{{ route('participant.registrations.create', $competition->id) }}" class="btn btn-primary w-full py-3 shadow-[2px_2px_0px_0px_var(--foreground)] hover:shadow-[1px_1px_0px_0px_var(--foreground)] hover:translate-y-[1px]">
                                    Daftar Sekarang
                                </a>
                            @else
                                <button disabled class="btn w-full py-3 bg-muted text-muted-foreground border-border cursor-not-allowed">
                                    Pendaftaran Ditutup
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-card rounded-2xl border-2 border-border shadow-[4px_4px_0px_0px_var(--foreground)]">
                        <div class="w-20 h-20 bg-muted border-2 border-border rounded-full flex items-center justify-center mx-auto mb-6 shadow-[2px_2px_0px_0px_var(--foreground)]">
                            <svg class="w-10 h-10 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h3 class="text-3xl font-bold mb-2">Belum ada kompetisi terbuka</h3>
                        <p class="text-muted-foreground text-lg">Silakan kembali lagi nanti untuk melihat kompetisi terbaru!</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <footer class="bg-card border-t-2 border-border py-12 mt-10">
            <div class="container mx-auto px-8 max-w-7xl text-center">
                <h2 class="text-2xl font-extrabold mb-4 flex items-center justify-center gap-2">
                    <div class="w-6 h-6 bg-primary text-primary-foreground flex items-center justify-center rounded border border-border shadow-[1px_1px_0px_0px_var(--foreground)] text-xs">C</div>
                    CompeteHub
                </h2>
                <p class="text-muted-foreground font-bold">&copy; {{ date('Y') }} CompeteHub. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
