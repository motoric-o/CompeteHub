<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CompeteHub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 overflow-hidden">
        <div class="flex min-h-screen bg-gray-50">
            <!-- Left Side: Branding / Gradient -->
            <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-900 overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
                
                <div class="relative z-10 flex flex-col justify-center items-start p-20 text-white w-full h-full">
                    <x-application-logo class="w-20 h-20 fill-current text-white mb-8" />
                    <h1 class="text-5xl font-bold tracking-tight mb-4 leading-tight">Welcome to <br>CompeteHub.</h1>
                    <p class="text-lg text-indigo-100 max-w-md">The ultimate platform for organizing, managing, and joining competitions with ease. Elevate your competitive experience.</p>
                </div>
            </div>

            <!-- Right Side: Auth Form -->
            <div class="flex flex-col w-full lg:w-1/2 justify-center items-center p-8 lg:p-24 relative bg-white/80 backdrop-blur-xl">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8">
                    <a href="/">
                        <x-application-logo class="w-16 h-16 fill-current text-indigo-600" />
                    </a>
                </div>

                <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 transition-all duration-300 hover:shadow-[0_8px_40px_rgb(0,0,0,0.08)]">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <style>
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
            .animate-blob { animation: blob 7s infinite; }
            .animation-delay-2000 { animation-delay: 2s; }
        </style>
    </body>
</html>
