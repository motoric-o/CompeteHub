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
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? 'active' : '' }}">
                                Dashboard
                            </a>
                        </li>

                        @if(auth()->user()->role === 'committee')
                            <li>
                                <a href="{{ route('committee.competitions.index') }}" class="{{ request()->routeIs('committee.competitions.*') || request()->routeIs('committee.form-templates.*') || request()->routeIs('committee.registrations.*') || request()->routeIs('committee.management.*') ? 'active' : '' }}">
                                    Kompetisi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('broadcast.create') }}" class="{{ request()->routeIs('broadcast.*') ? 'active' : '' }}">
                                    Broadcast Email
                                </a>
                            </li>
                        @elseif(auth()->user()->role === 'participant')
                            <li>
                                <a href="{{ route('participant.competitions.index') }}" class="{{ request()->routeIs('participant.competitions.*') ? 'active' : '' }}">
                                    Browse Kompetisi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('participant.registrations.index') }}" class="{{ request()->routeIs('participant.registrations.*') ? 'active' : '' }}">
                                    Pendaftaranku
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('teams.index') }}" class="{{ request()->routeIs('teams.*') ? 'active' : '' }}">
                                    Tim Saya
                                </a>
                            </li>
                        @elseif(auth()->user()->role === 'judge')
                            <li>
                                <a href="{{ route('judge.submissions.index') }}" class="{{ request()->routeIs('judge.submissions.*') ? 'active' : '' }}">
                                    Tugas Penilaian
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('leaderboards.list') }}" class="{{ request()->routeIs('leaderboards.*') || request()->routeIs('leaderboard.*') ? 'active' : '' }}">
                                Leaderboard
                            </a>
                        </li>
                    </ul>

                    <!-- User Dropdown (pure JS) -->
                    <div style="position: relative;" id="user-dropdown-wrapper-old">
                        <button onclick="toggleDropdownOld()" class="nav-user cursor-pointer bg-transparent border-none" style="font-family: inherit;">
                            <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <div class="text-left">
                                <div class="nav-user-name">{{ auth()->user()->name }}</div>
                                <div class="nav-user-role">{{ auth()->user()->role }}</div>
                            </div>
                            <svg class="text-muted-foreground" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div id="user-dropdown-menu-old" class="card shadow-md" style="display: none; position: absolute; right: 0; top: calc(100% + 0.5rem); width: 13rem; padding: 0.25rem 0; z-index: 50;">
                            <a href="{{ route('profile.edit') }}" style="display: block; padding: 0.6rem 1rem; font-size: 0.875rem; text-decoration: none;" class="text-foreground hover:bg-muted">
                                 Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" style="display: block; width: 100%; text-align: left; padding: 0.6rem 1rem; font-size: 0.875rem; background: none; border: none; cursor: pointer; font-family: inherit;" class="text-danger hover:bg-muted">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    <script>
                        function toggleDropdownOld() {
                            var menu = document.getElementById('user-dropdown-menu-old');
                            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
                        }
                        document.addEventListener('click', function(e) {
                            var wrapper = document.getElementById('user-dropdown-wrapper-old');
                            var menu = document.getElementById('user-dropdown-menu-old');
                            if (wrapper && !wrapper.contains(e.target)) {
                                menu.style.display = 'none';
                            }
                        });
                    </script>
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
            <header class="bg-white/80 backdrop-blur-md border-b border-border sticky top-0 z-10"
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