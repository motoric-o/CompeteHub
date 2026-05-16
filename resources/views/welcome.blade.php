<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'CompeteHub') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .btn {
                display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
                padding: 0.6rem 1.25rem; border-radius: 0.75rem;
                font-weight: 600; font-size: 0.875rem; border: 1px solid transparent; cursor: pointer;
                transition: all 0.2s ease; text-decoration: none;
            }
            .btn-primary { background: var(--primary); color: var(--primary-foreground); border-color: var(--primary); }
            .btn-primary:hover { background: transparent; color: var(--primary); }
            .btn-secondary { background: var(--muted); color: var(--foreground); border-color: var(--border); }
            .btn-secondary:hover { background: var(--foreground); color: var(--card); }
            .card { transition: all 0.3s ease; border: 1px solid var(--border); }
            .card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); border-color: var(--primary); }
        </style>
    </head>
    <body class="bg-background text-foreground antialiased min-h-screen font-sans">
        <div class="container mx-auto px-4 py-8 max-w-6xl">
            <header class="flex justify-between items-center mb-12">
                <h1 class="text-3xl font-extrabold text-primary tracking-tight">
                    CompeteHub
                </h1>
                <nav class="flex gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                        @endif
                    @endauth
                </nav>
            </header>

            <main>
                <div class="mb-10 text-center">
                    <h2 class="text-4xl lg:text-5xl font-extrabold mb-4">Discover & Join Competitions</h2>
                    <p class="text-lg text-muted-foreground max-w-2xl mx-auto">Explore the latest competitions, form your team, and showcase your skills. Register now to secure your spot!</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($competitions as $competition)
                        <div class="card bg-card text-card-foreground border border-border rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                            @if($competition->banner_url)
                                <img src="{{ asset('storage/' . $competition->banner_url) }}" alt="{{ $competition->name }}" class="w-full h-48 object-cover rounded-xl mb-4">
                            @else
                                <div class="w-full h-48 bg-muted rounded-xl mb-4 flex items-center justify-center">
                                    <span class="text-muted-foreground font-medium">No Image</span>
                                </div>
                            @endif
                            
                            <h3 class="text-xl font-bold mb-2">{{ $competition->name }}</h3>
                            <p class="text-sm text-muted-foreground line-clamp-3 mb-4 flex-grow">{{ $competition->description }}</p>
                            
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-border">
                                <span class="text-sm font-semibold text-accent">
                                    {{ $competition->type === 'team' ? 'Team Base' : 'Individual' }}
                                </span>
                                
                                @if($competition->isRegistrationOpen() && $competition->hasAvailableQuota())
                                    <a href="{{ route('participant.registrations.create', $competition->id) }}" class="btn btn-primary btn-sm">
                                        Register
                                    </a>
                                @else
                                    <span class="px-4 py-2 bg-muted text-muted-foreground text-sm font-bold rounded-lg cursor-not-allowed">
                                        Closed
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 bg-card rounded-2xl border border-border">
                            <h3 class="text-xl font-bold mb-2 text-foreground">No open competitions</h3>
                            <p class="text-muted-foreground">Check back later for new events!</p>
                        </div>
                    @endforelse
                </div>
            </main>
        </div>
    </body>
</html>
