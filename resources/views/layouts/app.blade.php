@php($isHomepage = request()->routeIs('home'))
@php($appPageHeader = new \App\View\Components\PageHeader)
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" data-app-design @if($isHomepage) data-homepage @endif>
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

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="antialiased bg-main has-app-background homepage-without-aos rc-home{{ $isHomepage ? ' homepage' : ' rc-app' }}" data-app-surface="paper">
        <x-app-background />
        <div id="main" >
            @livewire('user-alert')
            <a class="skip-link" href="#{{ $isHomepage ? 'homepage-content' : 'app-content' }}">Zum Inhalt</a>
            <x-homepage.header />
            <!-- Page Heading -->
            @include('components.page-header', ['isWebPage' => $appPageHeader->isWebPage, 'showHeader' => $appPageHeader->showHeader, 'title' => $appPageHeader->title, 'safeIcon' => $appPageHeader->safeIcon])
            <x-pagebuilder-module :position="'top_banner'" :normalize-headings="!$isHomepage && $appPageHeader->showHeader"/>
            <x-pagebuilder-module :position="'banner'" :normalize-headings="!$isHomepage && $appPageHeader->showHeader"/>
            <x-pagebuilder-module :position="'bottom_banner'" :normalize-headings="!$isHomepage && $appPageHeader->showHeader"/>
            <!-- Page Content -->
            <main id="{{ $isHomepage ? 'homepage-content' : 'app-content' }}" @unless($isHomepage) class="app-content" @endunless>
                <x-pagebuilder-module :normalize-headings="!$isHomepage && $appPageHeader->showHeader"/>
                <x-pagebuilder-module :position="'above_content'" :normalize-headings="!$isHomepage && $appPageHeader->showHeader"/>
                {{ $slot }}
                <x-pagebuilder-module :position="'content'" :normalize-headings="!$isHomepage && $appPageHeader->showHeader"/>
            </main>
        </div>
        @if($isHomepage)
            @php($homepageFooterModule = new \App\View\Components\PagebuilderModule(position: 'footer'))
            @if($homepageFooterModule->modules->isNotEmpty())
                @include('components.pagebuilder-module', ['position' => $homepageFooterModule->position, 'modules' => $homepageFooterModule->modules, 'homepageModuleContext' => 'footer'])
            @endif
        @else
            <x-pagebuilder-module :position="'footer'"/>
        @endif
        <x-homepage.footer />
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
