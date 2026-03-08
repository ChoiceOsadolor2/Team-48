<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php($isForgotPassword = request()->routeIs('password.request'))
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>

        <script defer src="https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @if ($isForgotPassword)
            <link rel="stylesheet" href="/styles/style.css">
            <style>
                @font-face {
                    font-family: 'MiniPixel';
                    src: url('/fonts/mini-pixel-7.ttf') format('truetype');
                    font-weight: normal;
                    font-style: normal;
                    font-display: swap;
                }

                /* Match login/register/header look exactly on forgot-password route */
                body.vx-auth-forgot header,
                body.vx-auth-forgot header * {
                    font-family: 'MiniPixel', sans-serif !important;
                    line-height: 1 !important;
                }

                body.vx-auth-forgot nav > a {
                    font-size: 30px;
                    white-space: nowrap;
                }

                body.vx-auth-forgot #icons {
                    align-items: center;
                }

                body.vx-auth-forgot #icons > svg,
                body.vx-auth-forgot #icons > span,
                body.vx-auth-forgot #icons > .user-menu {
                    display: inline-flex;
                    align-items: center;
                    line-height: 1;
                }

                body.vx-auth-forgot .basket_count {
                    line-height: 1;
                }

                body.vx-auth-forgot #theme-toggle-button + label {
                    font-family: 'MiniPixel', sans-serif !important;
                    box-sizing: border-box;
                }

                body.vx-auth-forgot .user-menu-item {
                    box-sizing: border-box !important;
                    font-family: 'MiniPixel', sans-serif !important;
                }

                body.vx-auth-forgot .user-menu-dropdown {
                    right: 24px !important;
                    left: auto !important;
                }
            </style>
        @endif

        @if (! app()->environment('testing'))
            {{-- Disabled Vite for server deployment --}}
            {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        @endif
    </head>

    <body class="{{ $isForgotPassword ? 'vx-auth-forgot antialiased' : 'font-sans text-gray-900 antialiased' }}" style="--bg-y-pos: center 0;">
        @if ($isForgotPassword)
            <header></header>
        @endif

        <div
            class="min-h-screen flex flex-col sm:justify-center items-center {{ $isForgotPassword ? 'pt-28 sm:pt-28' : 'pt-6 sm:pt-0' }} bg-black"
            style="
                background-image: url('{{ asset('assets/Veltrix-homepage-background.png') }}');
                background-repeat: repeat-y;
                background-size: 100vw auto;
                background-position: var(--bg-y-pos);
            "
        >
            @unless ($isForgotPassword)
                <div>
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </div>
            @endunless

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        @if ($isForgotPassword)
            <script src="/scripts/header.js"></script>
            <script src="/scripts/animations.js" defer></script>
        @endif

        @unless ($isForgotPassword)
            <script>
                (function () {
                    let y = 0;
                    setInterval(function () {
                        y += 1;
                        document.body.style.setProperty('--bg-y-pos', `center ${y}px`);
                    }, 20);
                })();
            </script>
        @endunless
    </body>
</html>
