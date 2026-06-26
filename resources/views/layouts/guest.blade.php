<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CompeteHub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body style="font-family: var(--font-sans, 'DM Sans', sans-serif); background: var(--background, #f7f9f3); color: var(--foreground, #000000); overflow: hidden; margin: 0;">
        <div style="display: flex; min-height: 100vh;">
            <!-- Left Side: Branding -->
            <div style="display: none; position: relative; overflow: hidden; background: var(--primary, #4f46e5);" class="lg-brand-panel">

                <div style="position: relative; z-index: 10; display: flex; flex-direction: column; justify-content: center; align-items: flex-start; padding: 5rem; color: var(--primary-foreground, #ffffff); width: 100%; height: 100%;">
                    <div style="font-size: 2rem; font-weight: 800; letter-spacing: -0.05em; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                        CompeteHub
                    </div>
                    <h1 style="font-size: 3rem; font-weight: 800; line-height: 1.1; margin: 0 0 1.5rem; letter-spacing: -0.03em;">The Ultimate<br>Competition<br>Platform.</h1>
                    <p style="font-size: 1.1rem; opacity: 0.85; max-width: 360px; line-height: 1.7; margin: 0 0 2.5rem;">Organize, manage, and join competitions with ease. Elevate your competitive experience today.</p>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.2); border-radius: 0.75rem; padding: 1rem 1.25rem; text-align: center;">
                            <div style="font-size: 1.75rem; font-weight: 800;">500+</div>
                            <div style="font-size: 0.8rem; opacity: 0.8;">Competitions</div>
                        </div>
                        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.2); border-radius: 0.75rem; padding: 1rem 1.25rem; text-align: center;">
                            <div style="font-size: 1.75rem; font-weight: 800;">10K+</div>
                            <div style="font-size: 0.8rem; opacity: 0.8;">Participants</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Auth Form -->
            <div style="display: flex; flex-direction: column; width: 100%; justify-content: center; align-items: center; padding: 2rem; background: var(--background, #f7f9f3); position: relative;">
                <!-- Back to home link -->
                <a href="{{ route('home') }}" style="position: absolute; top: 1.5rem; left: 1.5rem; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 600; color: var(--muted-foreground, #333333); text-decoration: none; transition: color 0.2s;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Beranda
                </a>

                <!-- Logo -->
                <div style="margin-bottom: 2.5rem; text-align: center;">
                    <a href="{{ route('home') }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 1.25rem; font-weight: 800; color: var(--foreground, #000000); letter-spacing: -0.02em;">CompeteHub</span>
                    </a>
                </div>

                <!-- Form Card -->
                <div style="width: 100%; max-width: 420px; background: var(--card, #ffffff); border: 1px solid var(--border, #000000); border-radius: var(--radius, 1rem); padding: 2.5rem; box-shadow: 0 2px 16px rgba(0,0,0,0.06);">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <style>
            @media (min-width: 1024px) {
                .lg-brand-panel {
                    display: flex !important;
                    width: 50%;
                }
                body > div > div:last-child {
                    width: 50%;
                }
            }
            .btn {
                display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
                padding: 0.75rem 1.5rem; border-radius: calc(var(--radius, 1rem) - 0.25rem);
                font-weight: 700; font-size: 0.9rem; border: 1px solid transparent; cursor: pointer;
                transition: all 0.2s ease; text-decoration: none; font-family: inherit;
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
            .form-input:focus {
                border-color: var(--primary, #4f46e5) !important;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
            }
        </style>
    </body>
</html>
