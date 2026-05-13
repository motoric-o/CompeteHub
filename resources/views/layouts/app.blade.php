<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'CompeteHub'))</title>
    <meta name="description" content="@yield('description', 'Platform manajemen kompetisi terpadu')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts dari Week 1 (Tailwind & AlpineJS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ── Design System Tokens ─────────────────────────── */
        :root {
            --card: #fcfcfc;
            --ring: #72e3ad;
            --input: #f6f6f6;
            --muted: #ededed;
            --accent: #ededed;
            --border: #dfdfdf;
            --radius: 0.5rem;
            --chart-1: #72e3ad;
            --chart-2: #3b82f6;
            --chart-3: #8b5cf6;
            --chart-4: #f59e0b;
            --chart-5: #10b981;
            --popover: #fcfcfc;
            --primary: #72e3ad;
            --sidebar: #fcfcfc;
            --font-mono: monospace;
            --font-sans: "Outfit", sans-serif;
            --secondary: #fdfdfd;
            --background: #fcfcfc;
            --font-serif: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
            --foreground: #171717;
            --destructive: #ca3214;
            --shadow-blur: 3px;
            --shadow-color: #000000;
            --sidebar-ring: #72e3ad;
            --shadow-spread: 0px;
            --letter-spacing: 0.025em;
            --shadow-opacity: 0.17;
            --sidebar-accent: #ededed;
            --sidebar-border: #dfdfdf;
            --card-foreground: #171717;
            --shadow-offset-x: 0px;
            --shadow-offset-y: 1px;
            --sidebar-primary: #72e3ad;
            --muted-foreground: #202020;
            --accent-foreground: #202020;
            --popover-foreground: #525252;
            --primary-foreground: #1e2723;
            --sidebar-foreground: #707070;
            --secondary-foreground: #171717;
            --destructive-foreground: #fffcfc;
            --sidebar-accent-foreground: #202020;
            --sidebar-primary-foreground: #1e2723;

            /* Legacy mappings to keep the view working */
            --color-primary: var(--primary);
            --color-primary-dark: var(--chart-5);
            --color-primary-light: var(--chart-1);
            --color-secondary: var(--secondary);
            --color-accent: var(--chart-4);
            --color-success: var(--chart-5);
            --color-danger: var(--destructive);
            --color-warning: var(--chart-4);

            --color-bg: var(--background);
            --color-bg-card: var(--card);
            --color-bg-elevated: var(--muted);
            --color-text: var(--foreground);
            --color-text-muted: var(--muted-foreground);
            --color-text-dim: var(--sidebar-foreground);
            --color-border: var(--border);

            --radius-sm: calc(var(--radius) - 2px);
            --radius-md: var(--radius);
            --radius-lg: calc(var(--radius) + 4px);
            --radius-xl: calc(var(--radius) + 12px);

            --shadow-sm: 0 1px 2px rgba(0,0,0,.05);
            --shadow-md: var(--shadow-offset-x) var(--shadow-offset-y) var(--shadow-blur) var(--shadow-spread) rgba(0,0,0,var(--shadow-opacity));
            --shadow-lg: 0 4px 12px rgba(0,0,0,.1);
            --shadow-glow: 0 0 15px rgba(114,227,173,.3);

            --transition: 0.2s cubic-bezier(.4,0,.2,1);
        }

        /* ── Reset & Base ─────────────────────────────────── */
        body {
            font-family: var(--font-sans), 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            line-height: 1.6;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        a { color: var(--color-primary-light); text-decoration: none; transition: color var(--transition); }
        a:hover { color: var(--color-primary); }

        /* ── Navbar ───────────────────────────────────────── */
        .navbar {
            background: rgba(252, 252, 252, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--color-border);
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--color-primary);
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navbar-nav a {
            color: var(--color-text-muted);
            font-weight: 500;
            font-size: 0.9rem;
            transition: color var(--transition);
            padding: 0.5rem 0;
        }

        .navbar-nav a:hover,
        .navbar-nav a.active {
            color: var(--color-text);
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--color-primary);
            color: var(--primary-foreground);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
        }

        /* ── Container ────────────────────────────────────── */
        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ── Cards ────────────────────────────────────────── */
        .card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            transition: all var(--transition);
        }

        .card:hover {
            border-color: var(--color-primary);
            box-shadow: var(--shadow-glow);
            transform: translateY(-2px);
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        /* ── Buttons ──────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
            line-height: 1.4;
        }

        .btn-primary { background: var(--color-primary); color: var(--primary-foreground); }
        .btn-secondary { background: var(--color-bg-elevated); color: var(--color-text); border: 1px solid var(--color-border); }
        .btn-danger { background: var(--color-danger); color: var(--destructive-foreground); }
        .btn-success { background: var(--color-success); color: white; }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; }

        /* ── Forms ────────────────────────────────────────── */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 600; font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 0.5rem; }
        .form-control {
            width: 100%; padding: 0.7rem 1rem; background: var(--color-bg); border: 1px solid var(--color-border);
            border-radius: var(--radius-sm); color: var(--color-text); font-size: 0.9rem; font-family: inherit;
        }

        /* ── Utility ──────────────────────────────────────── */
        .text-muted { color: var(--color-text-muted); }
        .text-success { color: var(--color-success); }
        .text-danger { color: var(--color-danger); }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mb-2 { margin-bottom: 1rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }
        .grid { display: grid; gap: 1.5rem; }
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .page-title { font-size: 1.75rem; font-weight: 800; margin: 0; }
        .page-subtitle { color: var(--color-text-muted); font-size: 0.95rem; margin-top: 0.25rem; }
        .section-title { font-size: 1.1rem; font-weight: 700; color: var(--color-text-muted); margin-bottom: 1rem; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp 0.4s ease-out both; }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen flex flex-col">
        
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-inner">
                <a href="/" class="navbar-brand">CompeteHub</a>

                <ul class="navbar-nav">
                    <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('*.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('teams.index') }}" class="{{ request()->routeIs('teams.*') ? 'active' : '' }}">Tim Saya</a></li>
                    <li><a href="{{ route('broadcast.create') }}" class="{{ request()->routeIs('broadcast.*') ? 'active' : '' }}">Kirim Email</a></li>
                </ul>

                @auth
                <div class="nav-user">
                    <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span style="font-size: 0.9rem; font-weight: 500;">{{ auth()->user()->name }}</span>
                    
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-secondary btn-sm" style="padding: 0.4rem 0.75rem; font-size: 0.75rem;">
                        Keluar
                    </a>
                </div>
                @endauth
            </div>
        </nav>

        <!-- Flash Messages -->
        <div class="container-custom" style="padding-bottom: 0;">
            @if(session('success'))
                <x-alert type="success">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if(session('error'))
                <x-alert type="error">
                    {{ session('error') }}
                </x-alert>
            @endif

            @if($errors->any())
                <x-alert type="error" title="Terdapat Kesalahan">
                    <ul style="margin-left: 1.5rem; margin-top: 0.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif
        </div>

        <!-- Header untuk Week 1 / Tailwind -->
        @isset($header)
            <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-10" style="margin-bottom: 2rem;">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content -->
        <main class="container-custom flex-1 relative">
            <!-- Tempat merender yield (custom theme FO6) -->
            @yield('content')
            
            <!-- Tempat merender slot (Tailwind component week1) -->
            <div class="relative z-10">
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
