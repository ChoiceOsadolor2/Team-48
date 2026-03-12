<!DOCTYPE html>
@php($isProfilePage = request()->routeIs('profile.*'))
@php($isOrdersPage = request()->routeIs('orders.*'))
@php($isCheckoutPage = request()->routeIs('checkout.*'))
@php($isAdminPage = request()->routeIs('admin.*'))
@php($siteNotifications = collect([
    session('stock_error') ? ['type' => 'error', 'message' => session('stock_error')] : null,
    session('status') ? ['type' => 'success', 'message' => session('status')] : null,
])->filter()->values())
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if($isProfilePage || $isOrdersPage || $isCheckoutPage || $isAdminPage) class="home" @endif @if($isProfilePage || $isOrdersPage || $isCheckoutPage || $isAdminPage) data-theme="dark" @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        @if ($isProfilePage || $isOrdersPage || $isCheckoutPage || $isAdminPage)
            <link rel="stylesheet" href="/styles/styleHomepage.css">
        @endif
        @if ($isProfilePage || $isOrdersPage || $isCheckoutPage || $isAdminPage)
            <link rel="stylesheet" href="/styles/style.css">
            <script src="https://kit.fontawesome.com/1165876da6.js" crossorigin="anonymous"></script>
        @endif
        <style>
            body, h1, h2, h3, h4, h5, h6, .font-sans {
                font-family: 'Pixelify Sans', sans-serif !important;
            }
            .site-toast-region {
                position: fixed;
                top: 100px;
                right: 20px;
                z-index: 10050;
                display: flex;
                flex-direction: column;
                gap: 12px;
                width: min(420px, calc(100vw - 32px));
                pointer-events: none;
            }

            .site-toast {
                pointer-events: auto;
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 14px 16px;
                border-radius: 16px;
                border: 1px solid rgba(255, 255, 255, 0.16);
                background: rgba(12, 12, 12, 0.94);
                color: #fff;
                box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                opacity: 0;
                transform: translateY(-12px);
                transition: opacity 0.25s ease, transform 0.25s ease;
            }

            .site-toast.is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            .site-toast.is-closing {
                opacity: 0;
                transform: translateY(-8px);
            }

            .site-toast-icon {
                flex-shrink: 0;
                width: 34px;
                height: 34px;
                border-radius: 999px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                line-height: 1;
                border: 1px solid transparent;
            }

            .site-toast-content {
                flex: 1;
                min-width: 0;
            }

            .site-toast-title {
                display: block;
                font-size: 0.95rem;
                font-weight: 700;
                margin-bottom: 4px;
            }

            .site-toast-message {
                display: block;
                font-size: 0.9rem;
                line-height: 1.45;
                word-break: break-word;
            }

            .site-toast-close {
                flex-shrink: 0;
                border: 0;
                background: transparent;
                color: inherit;
                font-size: 18px;
                line-height: 1;
                padding: 2px;
                cursor: pointer;
                opacity: 0.72;
                transition: opacity 0.2s ease, transform 0.2s ease;
            }

            .site-toast-close:hover,
            .site-toast-close:focus-visible {
                opacity: 1;
                transform: scale(1.05);
                outline: none;
            }

            .site-toast--success {
                border-color: rgba(46, 204, 113, 0.35);
            }

            .site-toast--success .site-toast-icon {
                color: #8ff0b5;
                background: rgba(46, 204, 113, 0.12);
                border-color: rgba(46, 204, 113, 0.35);
            }

            .site-toast--error {
                border-color: rgba(255, 107, 107, 0.35);
            }

            .site-toast--error .site-toast-icon {
                color: #ff8d8d;
                background: rgba(255, 107, 107, 0.12);
                border-color: rgba(255, 107, 107, 0.35);
            }

            .site-toast--info {
                border-color: rgba(88, 166, 255, 0.35);
            }

            .site-toast--info .site-toast-icon {
                color: #9dc8ff;
                background: rgba(88, 166, 255, 0.12);
                border-color: rgba(88, 166, 255, 0.35);
            }

            @media (max-width: 640px) {
                .site-toast-region {
                    top: 84px;
                    right: 12px;
                    left: 12px;
                    width: auto;
                }
            }
            @if ($isOrdersPage || $isCheckoutPage)
                @font-face {
                    font-family: 'MiniPixel';
                    src: url('/fonts/mini-pixel-7.ttf') format('truetype');
                    font-weight: normal;
                    font-style: normal;
                    font-display: swap;
                }

                html[data-theme="dark"],
                html[data-theme="dark"] body.orders-page,
                html[data-theme="dark"] body.checkout-page {
                    background-color: #000 !important;
                }

                body.orders-page,
                body.checkout-page {
                    overflow-x: hidden;
                    background-color: #000 !important;
                    background-image: url('{{ asset('assets/Veltrix-homepage-background.png') }}');
                    background-repeat: repeat-y;
                    background-size: 100vw auto;
                    background-position: var(--bg-y-pos, center 0);
                }

                body.orders-page header,
                body.orders-page header *,
                body.checkout-page header,
                body.checkout-page header * {
                    font-family: 'MiniPixel', sans-serif !important;
                }

                body.orders-page nav > a,
                body.checkout-page nav > a {
                    font-size: 30px !important;
                    white-space: nowrap !important;
                }

                body.orders-page #theme-toggle-button + label,
                body.checkout-page #theme-toggle-button + label {
                    font-family: 'MiniPixel', sans-serif !important;
                    box-sizing: border-box !important;
                }

                body.orders-page #theme-toggle-button + label {
                    min-width: 200px !important;
                    padding: 12px 18px !important;
                    font-size: 20px !important;
                    line-height: 1 !important;
                }

                body.checkout-page #theme-toggle-button + label {
                    min-width: 200px !important;
                    padding: 12px 18px !important;
                    font-size: 20px !important;
                    line-height: 1 !important;
                }

                body.orders-page .user-menu-item,
                body.checkout-page .user-menu-item {
                    box-sizing: border-box !important;
                    font-family: 'MiniPixel', sans-serif !important;
                }

                body.orders-page .user-menu-dropdown,
                body.checkout-page .user-menu-dropdown {
                    right: 24px !important;
                    left: auto !important;
                }

                body.orders-page header svg {
                    display: inline !important;
                    vertical-align: baseline !important;
                }

                body.checkout-page header svg {
                    display: inline !important;
                    vertical-align: baseline !important;
                }

                body.orders-page .user-menu-btn {
                    line-height: normal !important;
                }

                body.checkout-page .user-menu-btn {
                    line-height: normal !important;
                }

                body.orders-page #icons > span {
                    white-space: nowrap !important;
                }

                body.checkout-page #icons > span {
                    white-space: nowrap !important;
                }

                body.orders-page #menu_nav {
                    display: none !important;
                }

                body.checkout-page #menu_nav {
                    display: none !important;
                }

                body.orders-page main.orders-content {
                    background: transparent !important;
                    position: relative !important;
                    top: 0 !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    margin: 0;
                    padding: 90px 0 40px;
                    min-height: 100vh;
                    z-index: 1;
                }

                body.orders-page main.orders-content,
                body.orders-page main.orders-content * {
                    font-family: 'MiniPixel', sans-serif !important;
                }

                body.checkout-page main.checkout-content {
                    background: transparent !important;
                    position: relative !important;
                    top: 0 !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    margin: 0;
                    padding: 90px 0 40px;
                    min-height: 100vh;
                    z-index: 1;
                }

                body.checkout-page main.checkout-content,
                body.checkout-page main.checkout-content * {
                    font-family: 'MiniPixel', sans-serif !important;
                }
            @endif
            @if ($isAdminPage)
                @font-face {
                    font-family: 'MiniPixel';
                    src: url('/fonts/mini-pixel-7.ttf') format('truetype');
                    font-weight: normal;
                    font-style: normal;
                    font-display: swap;
                }

                html[data-theme="dark"],
                html[data-theme="dark"] body.admin-page {
                    background-color: #000 !important;
                }

                body.admin-page {
                    overflow-x: hidden;
                    background-color: #000 !important;
                    background-image: url('{{ asset('assets/Veltrix-homepage-background.png') }}');
                    background-repeat: repeat-y;
                    background-size: 100vw auto;
                    background-position: var(--bg-y-pos, center 0);
                }

                body.admin-page header,
                body.admin-page header * {
                    font-family: 'MiniPixel', sans-serif !important;
                }

                body.admin-page nav > a {
                    font-size: 30px !important;
                    white-space: nowrap !important;
                }

                body.admin-page #theme-toggle-button + label {
                    font-family: 'MiniPixel', sans-serif !important;
                    box-sizing: border-box !important;
                    min-width: 200px !important;
                    padding: 12px 18px !important;
                    font-size: 20px !important;
                    line-height: 1 !important;
                }

                body.admin-page .user-menu-item {
                    box-sizing: border-box !important;
                    font-family: 'MiniPixel', sans-serif !important;
                }

                body.admin-page .user-menu-dropdown {
                    right: 24px !important;
                    left: auto !important;
                }

                body.admin-page header svg {
                    display: inline !important;
                    vertical-align: baseline !important;
                }

                body.admin-page .user-menu-btn {
                    line-height: normal !important;
                }

                body.admin-page #icons > span {
                    white-space: nowrap !important;
                }

                body.admin-page #menu_nav {
                    display: none !important;
                }

                body.admin-page main.admin-content {
                    background: transparent !important;
                    position: relative !important;
                    top: 0 !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    margin: 0;
                    padding: 90px 0 40px;
                    min-height: 100vh;
                    z-index: 1;
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

                /* Match the static home/unavailable navbar shell under Tailwind preflight */
                body.profile-page header svg {
                    display: inline !important;
                    vertical-align: baseline !important;
                }

                body.profile-page .user-menu-btn {
                    line-height: normal !important;
                }

                body.profile-page #icons > span {
                    white-space: nowrap !important;
                }

                body.profile-page #menu_nav {
                    display: none !important;
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

    <body class="font-sans antialiased {{ $isProfilePage ? 'profile-page' : '' }} {{ $isOrdersPage ? 'orders-page' : '' }} {{ $isCheckoutPage ? 'checkout-page' : '' }} {{ $isAdminPage ? 'admin-page' : '' }}" style="--bg-y-pos: center 0;">
        <div class="{{ $isProfilePage || $isOrdersPage || $isCheckoutPage || $isAdminPage ? '' : 'min-h-screen bg-[#FAF0F0] dark:bg-[#050036]' }}">
            @if ($isProfilePage || $isOrdersPage || $isCheckoutPage || $isAdminPage)
                <header></header>
            @else
                @include('layouts.navigation')
            @endif

            @if ($isProfilePage || $isOrdersPage || $isCheckoutPage || $isAdminPage)
                <div class="search-container">
                    <input type="text" placeholder="Search..." class="search-bar" id="Search_Input">
                    <button class="search-close">&times;</button>
                </div>
            @endif

            @if (! $isProfilePage && ! $isOrdersPage && ! $isCheckoutPage && ! $isAdminPage)
                @isset($header)
                    <header class="bg-white dark:bg-[#0A004A] shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-gray-800 dark:text-gray-100">
                            {{ $header }}
                        </div>
                    </header>
                @endisset
            @endif

            <main class="{{ $isProfilePage ? 'profile-content' : '' }} {{ $isOrdersPage ? 'orders-content' : '' }} {{ $isCheckoutPage ? 'checkout-content' : '' }} {{ $isAdminPage ? 'admin-content' : '' }}">
                {{ $slot }}
            </main>
        </div>

        <div
            id="site-toast-region"
            class="site-toast-region"
            aria-live="polite"
            aria-atomic="true"
            data-session-toasts='@json($siteNotifications)'
        ></div>

        @if ($isProfilePage)
            <script src="/scripts/header.js"></script>
            <script src="/scripts/products.js?v=8"></script>
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
            <script src="/scripts/header.js"></script>
            <script src="/scripts/products.js?v=8"></script>
            <script src="/scripts/animations.js" defer></script>
            <script>
                (function () {
                    if (!localStorage.getItem('theme')) {
                        localStorage.setItem('theme', 'dark');
                    }
                    document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') || 'dark');
                })();
            </script>
        @elseif ($isCheckoutPage)
            <script src="/scripts/header.js"></script>
            <script src="/scripts/products.js?v=8"></script>
            <script src="/scripts/animations.js" defer></script>
            <script>
                (function () {
                    if (!localStorage.getItem('theme')) {
                        localStorage.setItem('theme', 'dark');
                    }
                    document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') || 'dark');
                })();
            </script>
        @elseif ($isAdminPage)
            <script src="/scripts/header.js"></script>
            <script src="/scripts/products.js?v=8"></script>
            <script src="/scripts/animations.js" defer></script>
            <script>
                (function () {
                    if (!localStorage.getItem('theme')) {
                        localStorage.setItem('theme', 'dark');
                    }
                    document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') || 'dark');
                })();
            </script>
        @endif

        <script>
            (function () {
                const toastRegion = document.getElementById('site-toast-region');

                if (!toastRegion || window.__siteToastInit) {
                    return;
                }

                window.__siteToastInit = true;

                const TOAST_TITLES = {
                    success: 'Success',
                    error: 'Something went wrong',
                    info: 'Notice'
                };

                const TOAST_ICONS = {
                    success: 'OK',
                    error: '!',
                    info: 'i'
                };

                function normalizeToast(type, message) {
                    return {
                        type: ['success', 'error', 'info'].includes(type) ? type : 'info',
                        message: String(message || '').trim()
                    };
                }

                function closeToast(toast) {
                    if (!toast || toast.dataset.closing === '1') {
                        return;
                    }

                    toast.dataset.closing = '1';
                    toast.classList.remove('is-visible');
                    toast.classList.add('is-closing');
                    window.setTimeout(() => toast.remove(), 220);
                }

                function renderToast(rawType, rawMessage, options = {}) {
                    const { type, message } = normalizeToast(rawType, rawMessage);
                    if (!message) {
                        return null;
                    }

                    const toast = document.createElement('div');
                    toast.className = `site-toast site-toast--${type}`;
                    toast.setAttribute('role', type === 'error' ? 'alert' : 'status');

                    const title = options.title || TOAST_TITLES[type];
                    const icon = options.icon || TOAST_ICONS[type];

                    toast.innerHTML = `
                        <span class="site-toast-icon" aria-hidden="true">${icon}</span>
                        <div class="site-toast-content">
                            <span class="site-toast-title">${title}</span>
                            <span class="site-toast-message"></span>
                        </div>
                        <button type="button" class="site-toast-close" aria-label="Dismiss notification">&times;</button>
                    `;

                    toast.querySelector('.site-toast-message').textContent = message;
                    toast.querySelector('.site-toast-close').addEventListener('click', () => closeToast(toast));

                    toastRegion.appendChild(toast);
                    window.requestAnimationFrame(() => toast.classList.add('is-visible'));

                    const duration = Number(options.duration || 5000);
                    if (duration > 0) {
                        window.setTimeout(() => closeToast(toast), duration);
                    }

                    return toast;
                }

                window.showSiteToast = function (type, message, options = {}) {
                    return renderToast(type, message, options);
                };

                let sessionToasts = [];
                try {
                    sessionToasts = JSON.parse(toastRegion.dataset.sessionToasts || '[]');
                } catch (_) {
                    sessionToasts = [];
                }

                sessionToasts.forEach((toast) => {
                    if (!toast || !toast.message) {
                        return;
                    }

                    renderToast(toast.type, toast.message);
                });
            })();
        </script>
    </body>
</html>
