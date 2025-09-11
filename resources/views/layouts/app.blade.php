<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="user-select:none;" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <x-meta-page-header />
        <title>@yield('title') | {{ config('app.name', 'Regulierungs-check') }}</title>
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('site-images/logo/logo-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('site-images/logo/logo-icon.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('site-images/logo/logo-icon.png') }}">

        <link rel="stylesheet" href="/adminresources/css/swiper-bundle.min.css">
        <script src="/adminresources/js/swiper-bundle.min.js"></script>
        <link href="{{ URL::asset('adminresources/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('adminresources/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css" />
        <script src="{{ URL::asset('adminresources/choices.js/public/assets/scripts/choices.min.js') }}"></script>
        <script src="{{ URL::asset('adminresources/flatpickr/flatpickr.min.js') }}"></script>
        <script src="{{ URL::asset('adminresources/flatpickr/l10n/de.js') }}"></script>
        <link href="{{ URL::asset('adminresources/aos/aos.css') }}" rel="stylesheet">
        <script src="{{ URL::asset('adminresources/aos/aos.js') }}"></script>
    <script id="usercentrics-cmp" src="https://web.cmp.usercentrics.eu/ui/loader.js" data-settings-id="XFHuZsqPDNpcWX" async></script>
        
        <!-- Styles -->
        @vite(['resources/css/app.css'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class=" antialiased ">
        <div id="main" class="snap-y">
            @livewire('user-alert')
            <header class="snap-start">
                @livewire('user-navigation-menu')
            </header>
            <!-- Page Heading -->
            <x-page-header />
            <x-pagebuilder-module :position="'top_banner'"/>
            <x-pagebuilder-module :position="'banner'"/>
            <x-pagebuilder-module :position="'bottom_banner'"/>
            <!-- Page Content -->
            <main  class="snap-start z-0">
                <x-pagebuilder-module/>
                <x-pagebuilder-module :position="'above_content'"/>
                {{ $slot }}
                <x-pagebuilder-module :position="'content'"/>
            </main>
        </div>
        <x-pagebuilder-module :position="'footer'"/>
        @livewire('footer')
        @livewire('tools.chatbot')
        
        @stack('modals')
        
        
        <!-- Scripts -->
        @vite(['resources/js/app.js'])
        @livewireScripts
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('nav', {
                    height: 0,
                    isMobile: false,
                    isScreenXl: false,
                    isMobileMenuOpen: false,
                    showNav: true,
                });
            });
        </script>
        <script>
            AOS.init();
        </script>
        

    </body>
</html>
