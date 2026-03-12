<!DOCTYPE html>
@php($isProfilePage = request()->routeIs('profile.*'))
@php($isOrdersPage = request()->routeIs('orders.*'))
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if($isProfilePage || $isOrdersPage) data-theme="dark" @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        @if ($isProfilePage)
            <link rel="stylesheet" href="/styles/style.css">
            <script src="https://kit.fontawesome.com/1165876da6.js" crossorigin="anonymous"></script>
        @endif
        <style>
            body, h1, h2, h3, h4, h5, h6, .font-sans {
                font-family: 'Pixelify Sans', sans-serif !important;
            }
            @if ($isOrdersPage)
                html[data-theme="dark"],
                html[data-theme="dark"] body.orders-page {
                    background-color: #000 !important;
                }

                body.orders-page {
                    overflow-x: hidden;
                    background-color: #000 !important;
                    background-image: url('{{ asset('assets/Veltrix-homepage-background.png') }}');
                    background-repeat: repeat-y;
                    background-size: 100vw auto;
                    background-position: var(--bg-y-pos, center 0);
                }
            @endif
            @if ($isProfilePage)
                @font-face {
                    font-family: 'MiniPixel';
                    src: url('/fonts/mini-pixel-7.ttf') format('truetype');
                    font-weight: normal;
                    font-style: normal;
                    font-display: swap;
                }

                html[data-theme="dark"],
                html[data-theme="dark"] body.profile-page {
                    background-color: #000 !important;
                }

                body.profile-page {
                    overflow-x: hidden;
                    background-color: #000 !important;
                    background-image: url('{{ asset('assets/Veltrix-homepage-background.png') }}');
                    background-repeat: repeat-y;
                    background-size: 100vw auto;
                    background-position: var(--bg-y-pos, center 0);
                }

                /* Match the exact nav typography/sizing helpers used on other working pages */
                body.profile-page header,
                body.profile-page header * {
                    font-family: 'MiniPixel', sans-serif !important;
                }

                body.profile-page nav > a {
                    font-size: 30px !important;
                    white-space: nowrap !important;
                }

                body.profile-page #theme-toggle-button + label {
                    font-family: 'MiniPixel', sans-serif !important;
                    box-sizing: border-box !important;
                    min-width: 200px !important;
                    padding: 12px 18px !important;
                    font-size: 20px !important;
                    line-height: 1 !important;
                }

                body.profile-page .user-menu-item {
                    box-sizing: border-box !important;
                    font-family: 'MiniPixel', sans-serif !important;
                }

                body.profile-page .user-menu-dropdown {
                    right: 24px !important;
                    left: auto !important;
                }

                body.profile-page main.profile-content {
                    background: transparent !important;
                    position: relative !important;
                    top: 0 !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    margin: 0;
                    padding: 15vh 0 40px;
                    min-height: 100vh;
                    z-index: 1;
                }

                body.profile-page main.profile-content,
                body.profile-page main.profile-content * {
                    font-family: 'MiniPixel', sans-serif !important;
                }

                /* The profile forms use semantic <header> tags; stop the global navbar styles from hijacking them. */
                body.profile-page main.profile-content header {
                    position: static !important;
                    top: auto !important;
                    width: auto !important;
                    height: auto !important;
                    display: block !important;
                    border-bottom: 0 !important;
                    background: transparent !important;
                    animation: none !important;
                    z-index: auto !important;
                }

                body.profile-page main.profile-content header::before {
                    content: none !important;
                    backdrop-filter: none !important;
                    -webkit-backdrop-filter: none !important;
                }

                /* Keep text/labels stable without blocking button glow */
                body.profile-page .profile-panel .profile-field label,
                body.profile-page .profile-panel .profile-field p,
                body.profile-page .profile-panel .profile-field span {
                    animation: none !important;
                }

                body.profile-page .profile-panel {
                    background: #1d1d1f !important;
                    border: 1px solid #444 !important;
                    border-radius: 18px !important;
                    box-shadow: none !important;
                    width: min(100%, 720px) !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                }

                body.profile-page .profile-panel > div {
                    width: 100% !important;
                    max-width: 540px !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                }

                body.profile-page .profile-panel,
                body.profile-page .profile-panel h1,
                body.profile-page .profile-panel h2,
                body.profile-page .profile-panel h3,
                body.profile-page .profile-panel p,
                body.profile-page .profile-panel label,
                body.profile-page .profile-panel span,
                body.profile-page .profile-panel a,
                body.profile-page .profile-panel button,
                body.profile-page .profile-panel input,
                body.profile-page .profile-panel textarea,
                body.profile-page .profile-panel select {
                    color: #fff !important;
                }

                body.profile-page .profile-panel input::placeholder,
                body.profile-page .profile-panel textarea::placeholder {
                    color: #aaa !important;
                }

                @keyframes profileBorderGlow {
                    0% { border-color: #ff00a8; }
                    25% { border-color: #ff5ebd; }
                    50% { border-color: #a800ff; }
                    75% { border-color: #0022ff; }
                    100% { border-color: #00eaff; }
                }

                @keyframes profileEdgeGlow {
                    0% { border-color: #ff00a8; }
                    25% { border-color: #ff5ebd; }
                    50% { border-color: #a800ff; }
                    75% { border-color: #0022ff; }
                    100% { border-color: #00eaff; }
                }

                body.profile-page .profile-panel .profile-textbox {
                    display: block !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                    margin-top: 0 !important;
                    padding: 12px 14px !important;
                    background: #000 !important;
                    border-width: 1px !important;
                    border-style: solid !important;
                    border-color: #444;
                    border-radius: 12px !important;
                    box-shadow: none;
                    outline: none !important;
                    transition: background-color 0.15s ease, border-color 0.15s ease !important;
                    filter: none !important;
                }

                body.profile-page .profile-panel .profile-textarea-singleline {
                    min-height: 56px !important;
                    height: 56px !important;
                    resize: none !important;
                    overflow: hidden !important;
                    line-height: 32px !important;
                }

                body.profile-page .profile-panel .profile-editable-field {
                    display: flex !important;
                    align-items: center !important;
                    width: 100% !important;
                    min-height: 56px !important;
                    padding: 12px 14px !important;
                    white-space: nowrap !important;
                    overflow: hidden !important;
                    cursor: text !important;
                }

                body.profile-page .profile-panel .profile-masked-password {
                    -webkit-text-security: disc;
                    text-security: disc;
                }

                body.profile-page .profile-panel .profile-field label {
                    display: inline-block !important;
                    margin-bottom: 6px !important;
                }

                body.profile-page .profile-panel .profile-input-wrap {
                    position: relative !important;
                    width: 100% !important;
                    margin-top: 4px !important;
                }

                body.profile-page .profile-panel .profile-textbox:hover {
                    background: #1d1d1d !important;
                    animation: profileBorderGlow 2s infinite alternate !important;
                    outline: none !important;
                }

                body.profile-page .profile-panel .profile-textbox:focus,
                body.profile-page .profile-panel .profile-textbox:focus-visible,
                body.profile-page .profile-panel .profile-textbox:active {
                    background: #1d1d1d !important;
                    animation: profileBorderGlow 2s infinite alternate !important;
                    outline: none !important;
                    --tw-ring-shadow: 0 0 #0000 !important;
                    --tw-ring-offset-shadow: 0 0 #0000 !important;
                    --tw-shadow: 0 0 #0000 !important;
                }

                body.profile-page .profile-panel .profile-textbox:not(:hover):not(:focus):not(:focus-visible):not(:active) {
                    animation: none !important;
                    border-color: #444 !important;
                }

                body.profile-page .profile-panel input[type="date"]::-webkit-calendar-picker-indicator {
                    filter: invert(1) brightness(1.2);
                }

                /* Match login/register typography scale */
                body.profile-page .profile-panel .text-lg {
                    font-size: 30px !important;
                    line-height: 1 !important;
                    font-weight: 400 !important;
                }

                body.profile-page .profile-panel .text-sm,
                body.profile-page .profile-panel label,
                body.profile-page .profile-panel input,
                body.profile-page .profile-panel button,
                body.profile-page .profile-panel a,
                body.profile-page .profile-panel p {
                    font-size: 20px !important;
                    font-weight: 400 !important;
                }

                body.profile-page .profile-panel .profile-action-button {
                    position: relative !important;
                    overflow: visible !important;
                    background: #000 !important;
                    border: 1px solid #444 !important;
                    border-radius: 12px !important;
                    color: #fff !important;
                    box-shadow: none !important;
                    transition: background-color 0.3s ease, transform 0.25s ease !important;
                    cursor: pointer !important;
                }

                body.profile-page .profile-panel .profile-action-button::after {
                    content: '' !important;
                    position: absolute !important;
                    top: -1px !important;
                    left: -1px !important;
                    right: -1px !important;
                    bottom: -1px !important;
                    border-radius: inherit !important;
                    border-width: 1px !important;
                    border-style: solid !important;
                    border-color: transparent;
                    animation-name: profileEdgeGlow !important;
                    animation-duration: 2s !important;
                    animation-iteration-count: infinite !important;
                    animation-direction: alternate !important;
                    animation-timing-function: ease-in-out !important;
                    opacity: 0 !important;
                    transition: opacity 0.3s ease !important;
                    pointer-events: none !important;
                    box-shadow: none !important;
                }

                body.profile-page .profile-panel button {
                    text-transform: none !important;
                    letter-spacing: normal !important;
                }

                /* Only animate when actually pressed (hover + click), not on hover proximity */
                body.profile-page .profile-panel .profile-action-button:hover::after,
                body.profile-page .profile-panel .profile-action-button:focus-visible::after {
                    opacity: 1 !important;
                }

                body.profile-page .profile-panel .profile-action-button:hover {
                    background-color: #2a2a2a !important;
                    border-color: transparent !important;
                    transform: translateY(-2px) !important;
                    box-shadow: none !important;
                }

                body.profile-page .profile-panel .profile-action-button:focus-visible {
                    background-color: #2a2a2a !important;
                    border-color: transparent !important;
                    transform: translateY(-2px) !important;
                    outline: none !important;
                    box-shadow: none !important;
                    --tw-ring-shadow: 0 0 #0000 !important;
                    --tw-ring-offset-shadow: 0 0 #0000 !important;
                }

                body.profile-page .profile-panel .profile-action-button:active,
                body.profile-page .profile-panel .profile-action-button:hover:active {
                    transform: none !important;
                }
            @endif
        </style>

        <script src="https://cdn.tailwindcss.com"></script>

        <script defer src="https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @if (! app()->environment('testing'))
            {{-- Disabled Vite for server deployment --}}
            {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        @endif
    </head>

    <body class="font-sans antialiased {{ $isProfilePage ? 'profile-page' : '' }} {{ $isOrdersPage ? 'orders-page' : '' }}" style="--bg-y-pos: center 0;">
        <div class="{{ $isProfilePage || $isOrdersPage ? '' : 'min-h-screen bg-[#FAF0F0] dark:bg-[#050036]' }}">
            @if ($isProfilePage)
                @include('layouts.veltrix-header')
            @else
                @include('layouts.navigation')
            @endif

            @if (! $isProfilePage)
                @isset($header)
                    <header class="bg-white dark:bg-[#0A004A] shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-gray-800 dark:text-gray-100">
                            {{ $header }}
                        </div>
                    </header>
                @endisset
            @endif

            <main class="{{ $isProfilePage ? 'profile-content' : '' }}">
                {{ $slot }}
            </main>
        </div>

        @if ($isProfilePage)
            <script src="/scripts/header.js"></script>
            <script src="/scripts/animations.js" defer></script>
            <script>
                (function () {
                    if (!localStorage.getItem('theme')) {
                        localStorage.setItem('theme', 'dark');
                    }
                    document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') || 'dark');

                    // Always start profile at the top on refresh/navigation.
                    if ('scrollRestoration' in history) {
                        history.scrollRestoration = 'manual';
                    }
                    const goTop = () => window.scrollTo(0, 0);
                    goTop();
                    window.addEventListener('load', goTop, { once: true });
                    window.addEventListener('pageshow', goTop);
                })();
            </script>
        @elseif ($isOrdersPage)
            <script>
                (function () {
                    if (!localStorage.getItem('theme')) {
                        localStorage.setItem('theme', 'dark');
                    }
                    document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') || 'dark');

                    let y = 0;
                    setInterval(function () {
                        y += 1;
                        document.body.style.setProperty('--bg-y-pos', `center ${y}px`);
                    }, 20);
                })();
            </script>
        @endif
    </body>
</html>
