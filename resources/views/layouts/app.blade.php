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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ── Design System Tokens ─────────────────────────── */
        :root {
            --color-primary: #6366f1;
            --color-primary-dark: #4f46e5;
            --color-primary-light: #818cf8;
            --color-secondary: #0ea5e9;
            --color-accent: #f59e0b;
            --color-success: #10b981;
            --color-danger: #ef4444;
            --color-warning: #f59e0b;

            --color-bg: #0f172a;
            --color-bg-card: #1e293b;
            --color-bg-elevated: #334155;
            --color-text: #f1f5f9;
            --color-text-muted: #94a3b8;
            --color-text-dim: #64748b;
            --color-border: #334155;

            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;

            --shadow-sm: 0 1px 3px rgba(0,0,0,.3);
            --shadow-md: 0 4px 12px rgba(0,0,0,.4);
            --shadow-lg: 0 8px 32px rgba(0,0,0,.5);
            --shadow-glow: 0 0 20px rgba(99,102,241,.3);

            --transition: 0.2s cubic-bezier(.4,0,.2,1);
        }

        /* ── Reset & Base ─────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            line-height: 1.6;
            min-height: 100vh;
        }

        a { color: var(--color-primary-light); text-decoration: none; transition: color var(--transition); }
        a:hover { color: var(--color-primary); }

        /* ── Navbar ───────────────────────────────────────── */
        .navbar {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
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
            background: linear-gradient(135deg, var(--color-primary-light), var(--color-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
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
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
            color: white;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--color-primary-light), var(--color-primary));
            box-shadow: var(--shadow-glow);
            transform: translateY(-1px);
            color: white;
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
            background: linear-gradient(135deg, var(--color-danger), #dc2626);
            color: white;
        }
        .btn-danger:hover {
            box-shadow: 0 0 15px rgba(239,68,68,.4);
            transform: translateY(-1px);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--color-success), #059669);
            color: white;
        }
        .btn-success:hover {
            box-shadow: 0 0 15px rgba(16,185,129,.4);
            transform: translateY(-1px);
            color: white;
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
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

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
            background: rgba(99, 102, 241, 0.2);
            color: var(--color-primary-light);
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: #fcd34d;
        }

        .badge-captain {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.3), rgba(249, 115, 22, 0.3));
            color: #fcd34d;
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
            font-family: 'Courier New', monospace;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            background: linear-gradient(135deg, var(--color-primary-light), var(--color-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            <a href="/" class="navbar-brand">⚡ CompeteHub</a>

            <ul class="navbar-nav">
                <li><a href="{{ route('teams.index') }}" class="{{ request()->routeIs('teams.*') ? 'active' : '' }}">🏆 Tim Saya</a></li>
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
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                ❌
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="container">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
