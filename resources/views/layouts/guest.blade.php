<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>

        <script defer src="https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @if (! app()->environment('testing'))
            {{-- Disabled Vite for server deployment --}}
            {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        @endif
    </head>

    <body class="font-sans text-gray-900 antialiased" style="--bg-y-pos: center 0;">
        <div
            class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-black"
            style="
                background-image: url('{{ asset('assets/Veltrix-homepage-background.png') }}');
                background-repeat: repeat-y;
                background-size: 100vw auto;
                background-position: var(--bg-y-pos);
            "
        >
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        <script>
            (function () {
                let y = 0;
                setInterval(function () {
                    y += 1;
                    document.body.style.setProperty('--bg-y-pos', `center ${y}px`);
                }, 20);
            })();
        </script>
    </body>
</html>
