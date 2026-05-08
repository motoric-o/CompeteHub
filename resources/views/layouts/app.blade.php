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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-10">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1 relative">
            <div
                class="absolute top-0 right-0 -mr-48 mt-[-10%] w-[40rem] h-[40rem] bg-indigo-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 z-0 pointer-events-none">
            </div>
            <div
                class="absolute bottom-0 left-0 -ml-48 mb-[-10%] w-[40rem] h-[40rem] bg-purple-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 z-0 pointer-events-none">
            </div>

            <div class="relative z-10">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>