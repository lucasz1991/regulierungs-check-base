<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" data-app-design>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Regulierungs-check') }}</title>
        <x-favicon-links />
        <link rel="preload" href="{{ asset('fonts/Montserrat-VariableFont_wght.ttf') }}" as="font" type="font/ttf" crossorigin>
        <script>
            (() => {
                let theme;
                try {
                    const saved = localStorage.getItem('rc-appearance');
                    if (saved === 'light' || saved === 'dark') theme = saved;
                } catch (_) {}
                if (!theme) theme = window.matchMedia?.('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                document.documentElement.dataset.theme = theme;
                document.documentElement.style.colorScheme = theme;
            })();
        </script>
        @unless(config('app.homepage_preview'))
            <script src="https://web.cmp.usercentrics.eu/modules/autoblocker.js"></script>
            <script id="usercentrics-cmp" src="https://web.cmp.usercentrics.eu/ui/loader.js" data-settings-id="XFHuZsqPDNpcWX" async></script>
        @endunless
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="antialiased has-app-background homepage-without-aos rc-home rc-app rc-auth" data-app-surface="paper">
        <x-app-background />
        <x-banner />
        <a class="skip-link" href="#app-content">Zum Inhalt</a>
        <x-homepage.header />
        <main id="app-content">
            @if (isset($header))
                <header class="app-shell app-page-heading py-8">
                    {{ $header }}
                </header>
            @endif
            {{ $slot }}
        </main>
        <x-homepage.footer />
        @stack('modals')
        <div id="bottom-nav" wire:ignore></div>
        @livewireScripts
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('nav', {
                    height: 0, isMobile: false, isScreenXl: false,
                    isMobileMenuOpen: false, showNav: true, showBottomNav: false,
                });
            });
        </script>
    </body>
</html>