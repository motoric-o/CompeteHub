<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'CompeteHub'))</title>
    <meta name="description" content="@yield('description', 'Platform manajemen kompetisi terpadu')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
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

            --shadow-sm: 0 1px 2px rgba(0, 0, 0, .05);
            --shadow-md: var(--shadow-offset-x) var(--shadow-offset-y) var(--shadow-blur) var(--shadow-spread) rgba(0, 0, 0, var(--shadow-opacity));
            --shadow-lg: 0 4px 12px rgba(0, 0, 0, .1);
            --shadow-glow: 0 0 15px rgba(114, 227, 173, .3);

            --transition: 0.2s cubic-bezier(.4, 0, .2, 1);
        }

        body {
            font-family: var(--font-sans), 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            line-height: 1.6;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        a {
            color: var(--color-primary-light);
            text-decoration: none;
            transition: color var(--transition);
        }

        a:hover {
            color: var(--color-primary);
        }

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
            gap: 1.5rem;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--color-primary);
            white-space: nowrap;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 1.25rem;
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
            white-space: nowrap;
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

        .nav-user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--color-text);
        }

        .nav-user-role {
            font-size: 0.7rem;
            color: var(--color-text-dim);
            text-transform: capitalize;
            margin-top: -0.25rem;
        }

        .nav-profile {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--color-text-muted);
        }

        .nav-profile:hover {
            color: var(--color-text);
        }

        .logout-button {
            border: none;
            background: transparent;
            color: var(--color-danger);
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            padding: 0;
        }

        .logout-button:hover {
            text-decoration: underline;
        }

        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

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

        .btn-primary {
            background: var(--color-primary);
            color: var(--primary-foreground);
        }

        .btn-secondary {
            background: var(--color-bg-elevated);
            color: var(--color-text);
            border: 1px solid var(--color-border);
        }

        .btn-danger {
            background: var(--color-danger);
            color: var(--destructive-foreground);
        }

        .btn-success {
            background: var(--color-success);
            color: white;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--color-text-muted);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.7rem 1rem;
            background: var(--color-bg);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            color: var(--color-text);
            font-size: 0.9rem;
            font-family: inherit;
        }

        .text-muted {
            color: var(--color-text-muted);
        }

        .text-success {
            color: var(--color-success);
        }

        .text-danger {
            color: var(--color-danger);
        }

        .mt-1 {
            margin-top: 0.5rem;
        }

        .mt-2 {
            margin-top: 1rem;
        }

        .mb-2 {
            margin-bottom: 1rem;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .gap-1 {
            gap: 0.5rem;
        }

        .gap-2 {
            gap: 1rem;
        }

        .grid {
            display: grid;
            gap: 1.5rem;
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            margin: 0;
        }

        .page-subtitle {
            color: var(--color-text-muted);
            font-size: 0.95rem;
            margin-top: 0.25rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--color-text-muted);
            margin-bottom: 1rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeInUp 0.4s ease-out both;
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen flex flex-col">
        <nav class="navbar">
            <div class="navbar-inner">
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="navbar-brand">
                    CompeteHub
                </a>

                @auth
                    <ul class="navbar-nav">
                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? 'active' : '' }}">
                                Dashboard
                            </a>
                        </li>

                        @if(auth()->user()->role === 'committee')
                            <li>
                                <a href="{{ route('committee.competitions.index') }}"
                                    class="{{ request()->routeIs('committee.competitions.*') || request()->routeIs('committee.form-templates.*') || request()->routeIs('committee.registrations.*') ? 'active' : '' }}">
                                    Kompetisi
                                </a>
                            </li>
                        @elseif(auth()->user()->role === 'participant')
                            <li>
                                <a href="{{ route('participant.competitions.index') }}"
                                    class="{{ request()->routeIs('participant.competitions.*') ? 'active' : '' }}">
                                    Browse Competitions
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('participant.registrations.index') }}"
                                    class="{{ request()->routeIs('participant.registrations.*') ? 'active' : '' }}">
                                    My Registrations
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('teams.index') }}"
                                class="{{ request()->routeIs('teams.*') ? 'active' : '' }}">
                                Tim Saya
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('broadcast.create') }}"
                                class="{{ request()->routeIs('broadcast.*') ? 'active' : '' }}">
                                Kirim Email
                            </a>
                        </li>
                    </ul>

                    {{-- User Dropdown (Alpine.js) --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="nav-user" style="cursor: pointer; background: none; border: none; font-family: inherit;">
                            <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <div>
                                <div class="nav-user-name">{{ auth()->user()->name }}</div>
                                <div class="nav-user-role">{{ auth()->user()->role }}</div>
                            </div>
                            <svg style="width: 16px; height: 16px; color: #707070;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open" x-transition.opacity style="display: none; position: absolute; right: 0; margin-top: 0.5rem; width: 12rem; background: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--radius-md); box-shadow: var(--shadow-lg); padding: 0.25rem 0; z-index: 50;">
                            <a href="{{ route('profile.edit') }}" style="display: block; padding: 0.5rem 1rem; font-size: 0.85rem; color: var(--color-text-muted); text-decoration: none;">
                                Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" style="display: block; width: 100%; text-align: left; padding: 0.5rem 1rem; font-size: 0.85rem; color: var(--color-danger); background: none; border: none; cursor: pointer; font-family: inherit;">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <ul class="navbar-nav">
                        <li>
                            <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">
                                Register
                            </a>
                        </li>
                    </ul>
                @endauth
            </div>
        </nav>

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

        @isset($header)
            <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-10"
                style="margin-bottom: 2rem;">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="container-custom flex-1 relative">
            @yield('content')

            <div class="relative z-10">
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>

    @stack('scripts')
</body>

</html>