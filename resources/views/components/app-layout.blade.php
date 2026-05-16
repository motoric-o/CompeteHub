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
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-primary: var(--primary);
            --color-primary-dark: var(--chart-5);
            --color-primary-light: var(--chart-1);
            --color-secondary: var(--secondary);
            --color-accent: var(--accent);
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
            --shadow-glow: 0 0 15px rgba(79, 70, 229, .2);

            --transition: 0.2s cubic-bezier(.4, 0, .2, 1);
        }

        body {
            font-family: var(--font-sans), 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            line-height: 1.6;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        a { color: var(--color-primary); text-decoration: none; transition: color var(--transition); }
        a:hover:not(.btn) { opacity: 0.8; }

        .navbar {
            background: rgba(247, 249, 243, 0.92);
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
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary, #4f46e5);
            white-space: nowrap;
            letter-spacing: -0.02em;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
            color: var(--muted-foreground, #333333);
            font-weight: 500;
            font-size: 0.9rem;
            transition: color var(--transition);
            padding: 0.5rem 0;
            text-decoration: none;
        }

        .navbar-nav a:hover, .navbar-nav a.active {
            color: var(--foreground, #000000);
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            white-space: nowrap;
        }

        .nav-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary, #4f46e5);
            color: var(--primary-foreground, #ffffff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            border: 2px solid var(--border, #000000);
        }

        .nav-user-name { font-size: 0.9rem; font-weight: 600; color: var(--foreground, #000000); }
        .nav-user-role { font-size: 0.7rem; color: var(--muted-foreground, #333333); text-transform: capitalize; margin-top: -0.25rem; }

        .container-custom { max-width: 1200px; margin: 0 auto; padding: 2rem; }

        .card {
            background: var(--card, #ffffff);
            border: 1px solid var(--border, #000000);
            border-radius: var(--radius, 1rem);
            padding: 1.5rem;
            transition: all var(--transition);
        }
        .card:hover { border-color: var(--primary, #4f46e5); box-shadow: var(--shadow-glow); transform: translateY(-2px); }

        .card-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.6rem 1.25rem; border-radius: var(--radius-sm);
            font-weight: 600; font-size: 0.875rem; border: 1px solid transparent; cursor: pointer;
            transition: all var(--transition); text-decoration: none; line-height: 1.4; font-family: inherit;
            white-space: nowrap;
        }
        .btn-primary { 
            background: var(--primary, #4f46e5); 
            color: var(--primary-foreground, #ffffff); 
            border-color: var(--primary, #4f46e5);
        }
        .btn-primary:hover { 
            background: transparent; 
            color: var(--primary, #4f46e5);
        }
        .btn-secondary { 
            background: var(--muted, #f0f0f0); 
            color: var(--foreground, #000000); 
            border-color: var(--border, #000000); 
        }
        .btn-secondary:hover { 
            background: var(--foreground, #000000); 
            color: var(--card, #ffffff); 
        }
        .btn-outline { 
            background: transparent; 
            color: var(--foreground, #000000); 
            border-color: var(--border, #000000); 
        }
        .btn-outline:hover { 
            background: var(--foreground, #000000); 
            color: var(--card, #ffffff); 
        }
        .btn-ghost {
            background: transparent;
            color: var(--muted-foreground, #333333);
        }
        .btn-ghost:hover {
            background: var(--muted, #f0f0f0);
            color: var(--foreground, #000000);
        }
        .btn-danger { 
            background: var(--destructive, #ef4444); 
            color: var(--destructive-foreground, #ffffff); 
            border-color: var(--destructive, #ef4444);
        }
        .btn-danger:hover { 
            background: transparent; 
            color: var(--destructive, #ef4444); 
        }
        .btn-success { 
            background: var(--chart-5, #22c55e); 
            color: #ffffff; 
            border-color: var(--chart-5, #22c55e);
        }
        .btn-success:hover {
            background: transparent;
            color: var(--chart-5, #22c55e);
        }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.75rem; }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 600; font-size: 0.875rem; color: var(--foreground, #000000); margin-bottom: 0.5rem; }
        .form-control {
            width: 100%; padding: 0.7rem 1rem;
            background: var(--background, #f7f9f3); border: 1px solid var(--border, #000000);
            border-radius: var(--radius-sm); color: var(--foreground, #000000);
            font-size: 0.9rem; font-family: inherit; box-sizing: border-box; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus { border-color: var(--primary, #4f46e5); box-shadow: 0 0 0 3px rgba(79,70,229,0.12); }

        .text-muted { color: var(--muted-foreground, #333333); }
        .text-success { color: var(--chart-5, #22c55e); }
        .text-danger { color: var(--destructive, #ef4444); }

        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .page-title { font-size: 1.75rem; font-weight: 800; margin: 0; letter-spacing: -0.03em; }
        .page-subtitle { color: var(--muted-foreground, #333333); font-size: 0.95rem; margin-top: 0.25rem; }
        .section-title { font-size: 1.1rem; font-weight: 700; color: var(--muted-foreground, #333333); margin-bottom: 1rem; }

        .grid { display: grid; gap: 1.5rem; }
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mb-2 { margin-bottom: 1rem; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: fadeInUp 0.4s ease-out both; }

        @media (max-width: 768px) {
            .grid-cols-2, .grid-cols-3 { grid-template-columns: 1fr; }
            .container-custom { padding: 1rem; }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div style="min-height: 100vh; display: flex; flex-direction: column;">
        <nav class="navbar">
            <div class="navbar-inner">
                <a href="{{ route('home') }}" class="navbar-brand">
                    CompeteHub
                </a>

                @auth
                    <ul class="navbar-nav">
                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? 'active' : '' }}"
                                style="{{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? 'color: var(--primary, #4f46e5); font-weight: 700;' : '' }}">
                                Dashboard
                            </a>
                        </li>

                                        @if(auth()->user()->role === 'committee')
                            <li>
                                <a href="{{ route('committee.competitions.index') }}"
                                    class="{{ request()->routeIs('committee.competitions.*') || request()->routeIs('committee.form-templates.*') || request()->routeIs('committee.registrations.*') ? 'active' : '' }}"
                                    style="{{ request()->routeIs('committee.*') ? 'color: var(--primary, #4f46e5); font-weight: 700;' : '' }}">
                                    Kompetisi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('broadcast.create') }}"
                                    style="{{ request()->routeIs('broadcast.*') ? 'color: var(--primary, #4f46e5); font-weight: 700;' : '' }}">
                                    Broadcast Email
                                </a>
                            </li>
                        @elseif(auth()->user()->role === 'participant')
                            <li>
                                <a href="{{ route('participant.competitions.index') }}"
                                    style="{{ request()->routeIs('participant.competitions.*') ? 'color: var(--primary, #4f46e5); font-weight: 700;' : '' }}">
                                    Browse Kompetisi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('participant.registrations.index') }}"
                                    style="{{ request()->routeIs('participant.registrations.*') ? 'color: var(--primary, #4f46e5); font-weight: 700;' : '' }}">
                                    Pendaftaranku
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('teams.index') }}"
                                    style="{{ request()->routeIs('teams.*') ? 'color: var(--primary, #4f46e5); font-weight: 700;' : '' }}">
                                    Tim Saya
                                </a>
                            </li>
                        @elseif(auth()->user()->role === 'judge')
                            <li>
                                <a href="{{ route('judge.submissions.index') }}"
                                    style="{{ request()->routeIs('judge.submissions.*') ? 'color: var(--primary, #4f46e5); font-weight: 700;' : '' }}">
                                    Tugas Penilaian
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('leaderboards.list') }}"
                                style="{{ request()->routeIs('leaderboards.*') || request()->routeIs('leaderboard.*') ? 'color: var(--primary, #4f46e5); font-weight: 700;' : '' }}">
                                Leaderboard
                            </a>
                        </li>
                    </ul>

                    <!-- User Dropdown (pure JS to avoid Alpine double-load issue) -->
                    <div style="position: relative;" id="user-dropdown-wrapper">
                        <button onclick="toggleDropdown()" class="nav-user" style="cursor: pointer; background: none; border: none; font-family: inherit;">
                            <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <div>
                                <div class="nav-user-name">{{ auth()->user()->name }}</div>
                                <div class="nav-user-role">{{ auth()->user()->role }}</div>
                            </div>
                            <svg style="width: 16px; height: 16px; color: var(--muted-foreground);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div id="user-dropdown-menu" style="display: none; position: absolute; right: 0; top: calc(100% + 0.5rem); width: 13rem; background: var(--card, #ffffff); border: 1px solid var(--border, #000000); border-radius: var(--radius, 1rem); box-shadow: 0 8px 24px rgba(0,0,0,0.08); padding: 0.25rem 0; z-index: 50;">
                            <a href="{{ route('profile.edit') }}" style="display: block; padding: 0.6rem 1rem; font-size: 0.875rem; color: var(--foreground, #000000); text-decoration: none;"
                                onmouseover="this.style.background='var(--muted, #f0f0f0)';" onmouseout="this.style.background='transparent';">
                                Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" style="display: block; width: 100%; text-align: left; padding: 0.6rem 1rem; font-size: 0.875rem; color: var(--destructive, #ef4444); background: none; border: none; cursor: pointer; font-family: inherit;"
                                    onmouseover="this.style.background='var(--muted, #f0f0f0)';" onmouseout="this.style.background='transparent';">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    <script>
                        function toggleDropdown() {
                            var menu = document.getElementById('user-dropdown-menu');
                            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
                        }
                        document.addEventListener('click', function(e) {
                            var wrapper = document.getElementById('user-dropdown-wrapper');
                            var menu = document.getElementById('user-dropdown-menu');
                            if (wrapper && !wrapper.contains(e.target)) {
                                menu.style.display = 'none';
                            }
                        });
                    </script>
                @else
                    <ul class="navbar-nav">
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li>
                            <a href="{{ route('register') }}"
                                style="background: var(--primary, #4f46e5); color: var(--primary-foreground, #ffffff); padding: 0.5rem 1rem; border-radius: calc(var(--radius, 1rem) - 0.25rem); font-weight: 700;">
                                Register
                            </a>
                        </li>
                    </ul>
                @endauth
            </div>
        </nav>

        <div class="container-custom" style="padding-bottom: 0;">
            @if(session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif
            @if(session('error'))
                <x-alert type="error">{{ session('error') }}</x-alert>
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
            <div style="background: rgba(247, 249, 243, 0.8); backdrop-filter: blur(8px); border-bottom: 1px solid var(--border, #000000); padding: 1.25rem 0; margin-bottom: 0;">
                <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <main class="container-custom" style="flex: 1;">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>

    @stack('scripts')
</body>

</html>
