<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CompeteHub')</title>
    <meta name="description" content="@yield('description', 'Platform manajemen kompetisi terpadu')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

        .dark {
            --card: #171717;
            --ring: #4ade80;
            --input: #242424;
            --muted: #1f1f1f;
            --accent: #313131;
            --border: #292929;
            --chart-1: #4ade80;
            --chart-2: #60a5fa;
            --chart-3: #a78bfa;
            --chart-4: #fbbf24;
            --chart-5: #2dd4bf;
            --popover: #242424;
            --primary: #006239;
            --sidebar: #121212;
            --secondary: #242424;
            --background: #121212;
            --foreground: #e2e8f0;
            --destructive: #541c15;
            --sidebar-ring: #4ade80;
            --sidebar-accent: #313131;
            --sidebar-border: #292929;
            --card-foreground: #e2e8f0;
            --sidebar-primary: #006239;
            --muted-foreground: #a2a2a2;
            --accent-foreground: #fafafa;
            --popover-foreground: #a9a9a9;
            --primary-foreground: #dde8e3;
            --sidebar-foreground: #898989;
            --secondary-foreground: #fafafa;
            --destructive-foreground: #ede9e8;
            --sidebar-accent-foreground: #fafafa;
            --sidebar-primary-foreground: #dde8e3;
        }

        @theme inline {
            --color-card: var(--card);
            --color-ring: var(--ring);
            --color-input: var(--input);
            --color-muted: var(--muted);
            --color-accent: var(--accent);
            --color-border: var(--border);
            --color-radius: var(--radius);
            --color-chart-1: var(--chart-1);
            --color-chart-2: var(--chart-2);
            --color-chart-3: var(--chart-3);
            --color-chart-4: var(--chart-4);
            --color-chart-5: var(--chart-5);
            --color-popover: var(--popover);
            --color-primary: var(--primary);
            --color-sidebar: var(--sidebar);
            --color-font-mono: var(--font-mono);
            --color-font-sans: var(--font-sans);
            --color-secondary: var(--secondary);
            --color-background: var(--background);
            --color-font-serif: var(--font-serif);
            --color-foreground: var(--foreground);
            --color-destructive: var(--destructive);
            --color-shadow-blur: var(--shadow-blur);
            --color-shadow-color: var(--shadow-color);
            --color-sidebar-ring: var(--sidebar-ring);
            --color-shadow-spread: var(--shadow-spread);
            --color-letter-spacing: var(--letter-spacing);
            --color-shadow-opacity: var(--shadow-opacity);
            --color-sidebar-accent: var(--sidebar-accent);
            --color-sidebar-border: var(--sidebar-border);
            --color-card-foreground: var(--card-foreground);
            --color-shadow-offset-x: var(--shadow-offset-x);
            --color-shadow-offset-y: var(--shadow-offset-y);
            --color-sidebar-primary: var(--sidebar-primary);
            --color-muted-foreground: var(--muted-foreground);
            --color-accent-foreground: var(--accent-foreground);
            --color-popover-foreground: var(--popover-foreground);
            --color-primary-foreground: var(--primary-foreground);
            --color-sidebar-foreground: var(--sidebar-foreground);
            --color-secondary-foreground: var(--secondary-foreground);
            --color-destructive-foreground: var(--destructive-foreground);
            --color-sidebar-accent-foreground: var(--sidebar-accent-foreground);
            --color-sidebar-primary-foreground: var(--sidebar-primary-foreground);
        }

        /* ── Reset & Base ─────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-sans), 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            line-height: 1.6;
            min-height: 100vh;
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
        .container {
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

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
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

        .btn-primary {
            background: var(--color-primary);
            color: var(--primary-foreground);
        }
        .btn-primary:hover {
            background: var(--color-primary-dark);
            box-shadow: var(--shadow-glow);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--color-bg-elevated);
            color: var(--color-text);
            border: 1px solid var(--color-border);
        }
        .btn-secondary:hover {
            background: var(--color-border);
            color: white;
        }

        .btn-danger {
            background: var(--color-danger);
            color: var(--destructive-foreground);
        }
        .btn-danger:hover {
            background: #b36e6e;
            box-shadow: 0 0 15px rgba(200,122,122,.4);
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--color-success);
            color: white;
        }
        .btn-success:hover {
            background: #0d9668;
            box-shadow: 0 0 15px rgba(16,185,129,.4);
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        .btn-outline {
            background: transparent;
            color: var(--color-primary-light);
            border: 1px solid var(--color-primary);
        }
        .btn-outline:hover {
            background: var(--color-primary);
            color: white;
        }

        /* ── Forms ────────────────────────────────────────── */
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
            transition: border-color var(--transition), box-shadow var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,.2);
        }

        .form-control::placeholder {
            color: var(--color-text-dim);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }

        /* ── Alerts ───────────────────────────────────────── */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            animation: slideDown 0.3s ease-out;
            background: var(--color-card);
            border: 1px solid var(--color-border);
            box-shadow: var(--shadow-sm);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success { border-left: 4px solid var(--color-success); }
        .alert-error, .alert-danger { border-left: 4px solid var(--color-danger); }
        .alert-info { border-left: 4px solid var(--color-chart-2); }
        .alert-warning { border-left: 4px solid var(--color-warning); }

        .alert-icon {
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        .alert-success .alert-icon { color: var(--color-success); }
        .alert-error .alert-icon, .alert-danger .alert-icon { color: var(--color-danger); }
        .alert-info .alert-icon { color: var(--color-chart-2); }
        .alert-warning .alert-icon { color: var(--color-warning); }

        /* ── Badges ───────────────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.65rem;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .badge-primary {
            background: var(--color-primary-light);
            color: var(--color-bg);
        }

        .badge-success {
            background: var(--color-success);
            color: var(--color-bg);
        }

        .badge-warning {
            background: var(--color-warning);
            color: var(--color-bg);
        }

        .badge-captain {
            background: var(--color-accent);
            color: var(--accent-foreground);
            border: 1px solid var(--color-border);
        }

        /* ── Tables ───────────────────────────────────────── */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--color-text-dim);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--color-border);
        }

        tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(51, 65, 85, 0.5);
            font-size: 0.9rem;
        }

        tbody tr {
            transition: background var(--transition);
        }

        tbody tr:hover {
            background: rgba(99, 102, 241, 0.05);
        }

        /* ── Invite Code Box ──────────────────────────────── */
        .invite-code-box {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--color-bg);
            border: 2px dashed var(--color-primary);
            border-radius: var(--radius-md);
            padding: 1rem 1.5rem;
            margin: 1rem 0;
        }

        .invite-code {
            font-family: var(--color-font-mono);
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            color: var(--color-primary);
        }

        /* ── Grid ─────────────────────────────────────────── */
        .grid {
            display: grid;
            gap: 1.5rem;
        }

        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }

        @media (max-width: 768px) {
            .grid-cols-2,
            .grid-cols-3 { grid-template-columns: 1fr; }
            .container { padding: 1rem; }
        }

        /* ── Page Header ──────────────────────────────────── */
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
        }

        .page-subtitle {
            color: var(--color-text-muted);
            font-size: 0.95rem;
            margin-top: 0.25rem;
        }

        /* ── Section ──────────────────────────────────────── */
        .section {
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--color-text-muted);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* ── Empty State ──────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--color-text-dim);
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .empty-state-text {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        /* ── Utility ──────────────────────────────────────── */
        .text-muted { color: var(--color-text-muted); }
        .text-success { color: var(--color-success); }
        .text-danger { color: var(--color-danger); }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mb-2 { margin-bottom: 1rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }

        /* ── Animations ───────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeInUp 0.4s ease-out both;
        }

        .animate-in:nth-child(2) { animation-delay: 0.1s; }
        .animate-in:nth-child(3) { animation-delay: 0.2s; }
        .animate-in:nth-child(4) { animation-delay: 0.3s; }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="/" class="navbar-brand">CompeteHub</a>

            <ul class="navbar-nav">
                <li><a href="{{ route('teams.index') }}" class="{{ request()->routeIs('teams.*') ? 'active' : '' }}">Tim Saya</a></li>
            </ul>

            @auth
            <div class="nav-user">
                <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span style="font-size: 0.9rem; font-weight: 500;">{{ auth()->user()->name }}</span>
            </div>
            @endauth
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container" style="padding-bottom: 0;">
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

    <!-- Main Content -->
    <main class="container">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
