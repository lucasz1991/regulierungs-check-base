@php($disableAosOnCurrentRoute = request()->routeIs('home'))
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="user-select:none;" class="scroll-smooth" @if($disableAosOnCurrentRoute) data-homepage @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        @if(isset($newsMeta))
            <x-news-meta :meta="$newsMeta" />
        @else
            <x-meta-page-header />
            <title>@yield('title') | {{ config('app.name', 'Regulierungs-check') }}</title>
        @endif
        <x-favicon-links />

        <link rel="stylesheet" href="/adminresources/css/swiper-bundle.min.css">
        <script src="/adminresources/js/swiper-bundle.min.js"></script>
        <link href="{{ URL::asset('adminresources/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('adminresources/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css" />
        <script src="{{ URL::asset('adminresources/choices.js/public/assets/scripts/choices.min.js') }}"></script>
        <script src="{{ URL::asset('adminresources/flatpickr/flatpickr.min.js') }}"></script>
        <script src="{{ URL::asset('adminresources/flatpickr/l10n/de.js') }}"></script>
        <link href="{{ URL::asset('adminresources/aos/aos.css') }}" rel="stylesheet">
                <link rel="stylesheet" href="{{ asset('adminresources/fontawesome6/css/all.min.css') }}">

        @unless(config('app.homepage_preview'))
            <script id="usercentrics-cmp" src="https://web.cmp.usercentrics.eu/ui/loader.js" data-settings-id="XFHuZsqPDNpcWX" async></script>
        @endunless
        
        <!-- Styles -->
        @if($loadNewsPagebuilderTailwind ?? false)
            @php($newsTailwindPath = public_path('adminresources/css/tailwind.min.css'))
            <link
                rel="stylesheet"
                href="{{ asset('adminresources/css/tailwind.min.css') }}?v={{ is_file($newsTailwindPath) ? filemtime($newsTailwindPath) : 'missing' }}"
                data-news-pagebuilder-tailwind
            >
        @endif
        @vite(['resources/css/app.css'])
        @if($disableAosOnCurrentRoute)
            <script>
(() => {
  'use strict';

  let theme;
  try {
    const saved = localStorage.getItem('rc-appearance');
    if (saved === 'light' || saved === 'dark') theme = saved;
  } catch (_) {
    // Browsers may restrict preference storage for local files.
  }
  if (!theme) theme = window.matchMedia?.('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  document.documentElement.dataset.theme = theme;
  document.documentElement.style.colorScheme = theme;
})();
            </script>
            <link rel="preload" href="{{ asset('fonts/Montserrat-VariableFont_wght.ttf') }}" as="font" type="font/ttf" crossorigin>
        @endif

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="antialiased bg-main{{ $disableAosOnCurrentRoute ? ' homepage-without-aos rc-home homepage' : '' }}">
        @if($disableAosOnCurrentRoute)
            <div class="homepage-background" aria-hidden="true" wire:ignore>
                <svg class="homepage-background-lines homepage-background-lines-near" viewBox="0 0 1600 1200" preserveAspectRatio="none" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
                    <path d="M-120-80C340 100-180 260 170 470S400 850 40 1280" />
                    <path d="M-160-60C300 130-220 300 130 510S360 890 0 1320" />
                    <path d="M-200-40C260 160-260 340 90 550S320 930-40 1360" />
                </svg>
                <svg class="homepage-background-lines homepage-background-lines-far" viewBox="0 0 1600 1200" preserveAspectRatio="none" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1710-120C1110 70 1720 300 1390 530S1180 960 1650 1350" />
                    <path d="M1750-80C1150 110 1760 340 1430 570S1220 1000 1690 1390" />
                    <path class="homepage-background-line-warm" d="M1790-40C1190 150 1800 380 1470 610S1260 1040 1730 1430" />
                </svg>
            </div>
        @endif
        <div id="main" >
            @livewire('user-alert')
            @if($disableAosOnCurrentRoute)
                <a class="skip-link" href="#homepage-content">Zum Inhalt</a>
                <x-homepage.header />
            @else
                <header wire:ignore>
                    @livewire('user-navigation-menu')
                </header>
            @endif
            <!-- Page Heading -->
            <x-page-header />
            <x-pagebuilder-module :position="'top_banner'"/>
            <x-pagebuilder-module :position="'banner'"/>
            <x-pagebuilder-module :position="'bottom_banner'"/>
            <!-- Page Content -->
            <main @if($disableAosOnCurrentRoute) id="homepage-content" @endif>
                <x-pagebuilder-module/>
                <x-pagebuilder-module :position="'above_content'"/>
                {{ $slot }}
                <x-pagebuilder-module :position="'content'"/>
            </main>
        </div>
        @if($disableAosOnCurrentRoute)
            @php($homepageFooterModule = new \App\View\Components\PagebuilderModule(position: 'footer'))
            @if($homepageFooterModule->modules->isNotEmpty())
                @include('components.pagebuilder-module', ['position' => $homepageFooterModule->position, 'modules' => $homepageFooterModule->modules, 'homepageModuleContext' => 'footer'])
            @endif
            <x-homepage.footer />
        @else
            <x-pagebuilder-module :position="'footer'"/>
            @livewire('footer')
        @endif
        @livewire('tools.chatbot')
        <x-share-link-handler />
        
        @stack('modals')
        
        
        <div id="bottom-nav"  wire:ignore></div>
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
                    showBottomNav: false,
                });
            });
        </script>
    </body>
</html>
