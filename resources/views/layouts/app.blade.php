<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body, h1, h2, h3, h4, h5, h6, .font-sans {
                font-family: 'Pixelify Sans', sans-serif !important;
            }
        </style>

        <script src="https://cdn.tailwindcss.com"></script>

        <script defer src="https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @if (! app()->environment('testing'))
            {{-- Disabled Vite for server deployment --}}
            {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        @endif
    </head>

    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[#FAF0F0] dark:bg-[#050036]">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white dark:bg-[#0A004A] shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-gray-800 dark:text-gray-100">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
