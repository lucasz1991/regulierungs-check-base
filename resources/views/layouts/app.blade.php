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
    <body class="antialiased bg-main has-app-background{{ $disableAosOnCurrentRoute ? ' homepage-without-aos rc-home homepage' : '' }}" @if(request()->routeIs('home', 'news.index', 'news.show')) data-app-surface="paper" @endif>
        <x-app-background />
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
