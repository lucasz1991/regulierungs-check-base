@php
    $homepageUnreadMessages = auth()->check() && auth()->user()->role === 'guest'
        ? auth()->user()->receivedUnreadMessages()->count()
        : 0;
@endphp
<header :class="{ 'menu-is-open': menuOpen, 'is-scrolled': scrolled, 'is-hidden': hidden }" @click.outside="closeMenu(false); closeDropdowns()" @homepage-search-state.window="searchState($event.detail.open)" @keydown.escape="escape($event)" class="site-header" data-homepage-header="" x-data="homepageNavigation" x-trap.inert.noscroll="menuOpen">
 <div class="header-inner">
  <a @click="closeMenu(false)" aria-label="Regulierungs-CHECK Startseite" class="brand" href="{{ route('home') }}" wire:navigate="">
   <img alt="" aria-hidden="true" class="brand-app-icon" height="313" src="{{ asset('site-images/logo/logo-icon.png') }}" width="320"/>
   <span aria-hidden="true" class="brand-wordmark">
    <img alt="" class="brand-wordmark-light" height="232" src="{{ asset('site-images/logo/logo-text-only.png') }}" width="671"/>
    <img alt="" class="brand-wordmark-dark" height="965" src="{{ asset('site-images/logo/logo-white-yelllow.png') }}" width="2794"/>
   </span>
  </a>
  <nav :aria-hidden="mobile ? String(!menuOpen) : null" :inert="mobile && !menuOpen" :class="{ open: menuOpen }" aria-label="Hauptnavigation" class="site-nav" id="site-nav" x-ref="navigation">
   <div class="mobile-nav-heading">
    <strong>
     Menü
    </strong>
    <button @click="closeMenu(true)" aria-label="Menü schließen" class="menu-close" data-close-menu="" type="button">
     ×
    </button>
   </div>
   <a @click="closeMenu(false)" aria-current="page" href="{{ route('home') }}" wire:navigate="">
    <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
     <path d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
     </path>
    </svg>
    <span class="nav-label">
     Home
    </span>
   </a>
   <details @toggle="submenuChanged($event)" class="nav-submenu">
    <summary @keydown.arrow-down.prevent="focusSubmenu($event, false)" @keydown.arrow-up.prevent="focusSubmenu($event, true)">
     <svg aria-hidden="true" class="nav-icon" fill="currentColor" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="M7 10H3v10h4V10zm7-6h-4v16h4V4zm7 10h-4v6h4v-6z">
      </path>
     </svg>
     <span class="nav-label">
      Leistungs-CHECK
     </span>
     <svg aria-hidden="true" class="nav-chevron" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="m19 9-7 7-7-7" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
      </path>
     </svg>
    </summary>
    <div class="nav-submenu-list">
     <a @click="closeMenu(false)" href="{{ route('insurances') }}" wire:navigate="">
      <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewbox="0 0 24 24">
       <polygon points="12 2 2 7 12 12 22 7 12 2">
       </polygon>
       <polyline points="2 17 12 22 22 17">
       </polyline>
       <polyline points="2 12 12 17 22 12">
       </polyline>
      </svg>
      <span class="nav-label">
       Versicherungs Anbieter
      </span>
     </a>
     <a @click="closeMenu(false)" href="{{ route('reviews') }}" wire:navigate="">
      <svg aria-hidden="true" class="nav-icon" fill="currentColor" focusable="false" viewbox="0 0 101.5483 81.854" xmlns="http://www.w3.org/2000/svg">
       <path d="M12.5401,81.854a2.62509,2.62509,0,0,1-2.6233-2.6214V67.041H6.5149A6.52214,6.52214,0,0,1,0,60.5269V6.4994A6.50622,6.50622,0,0,1,6.4988,0H95.7456a5.80957,5.80957,0,0,1,5.8027,5.8034V60.5269a6.51332,6.51332,0,0,1-6.4987,6.5141H31.2L14.2168,81.2431A2.61867,2.61867,0,0,1,12.5401,81.854ZM6.4988,4.1437a2.358,2.358,0,0,0-2.355,2.3557V60.5269a2.37385,2.37385,0,0,0,2.3711,2.3706h5.4736a2.07185,2.07185,0,0,1,2.0719,2.0718v11.003L29.1188,63.3797a2.07035,2.07035,0,0,1,1.3286-.4822H95.0496a2.36537,2.36537,0,0,0,2.3552-2.3706V5.8034a1.66148,1.66148,0,0,0-1.6592-1.6597Z">
       </path>
       <path d="M69.0928,47.8188a2.072,2.072,0,0,1-2.0422-2.4218l1.4635-8.5252-6.1952-6.038a2.07165,2.07165,0,0,1,1.1479-3.534l8.5598-1.2444,3.8291-7.757a2.0713,2.0713,0,0,1,3.7148,0l3.8294,7.757,8.5596,1.2444a2.07155,2.07155,0,0,1,1.1479,3.534l-6.1938,6.038,1.4054,8.1961a2.07468,2.07468,0,0,1-1.9706,2.7509h-.0148a2.07552,2.07552,0,0,1-.9645-.2379l-7.65588-4.025-7.65612,4.025A2.07443,2.07443,0,0,1,69.0928,47.8188Zm8.62042-8.6757a2.07318,2.07318,0,0,1,.96438.2382l4.9044,2.5789-.9362-5.462a2.0748,2.0748,0,0,1,.5962-1.8339l3.967-3.8678-5.4832-.797a2.07051,2.07051,0,0,1-1.5592-1.133l-2.45338-4.9698L75.2595,28.8665a2.07,2.07,0,0,1-1.5592,1.133l-5.483.797,3.9683,3.8678a2.07481,2.07481,0,0,1,.5963,1.8339l-.93768,5.4615,4.90448-2.5784A2.07362,2.07362,0,0,1,77.71322,39.1431Z">
       </path>
       <path d="M55.305,44.8838a2.073,2.073,0,0,1-.9645-.2381l-5.7487-3.0219-5.7487,3.0219a2.07207,2.07207,0,0,1-3.0066-2.1844l1.0994-6.4015-4.6522-4.5335a2.07157,2.07157,0,0,1,1.1479-3.5339l6.42862-.934L46.7345,21.234a2.07107,2.07107,0,0,1,3.71472-.0006l2.87558,5.825,6.42742.934a2.0716,2.0716,0,0,1,1.14768,3.5339l-4.65068,4.5335,1.04128,6.0731a2.07467,2.07467,0,0,1-1.9585,2.7509C55.3238,44.8833,55.313,44.8838,55.305,44.8838Zm-6.7132-7.6727a2.07368,2.07368,0,0,1,.9645.238l2.9972,1.5761-.572-3.3384a2.07451,2.07451,0,0,1,.596-1.8337l2.4253-2.3638-3.3518-.487a2.06993,2.06993,0,0,1-1.5593-1.1331l-1.4999-3.0368-1.4985,3.0361a2.069,2.069,0,0,1-1.5592,1.1338l-3.3518.487,2.4251,2.3638a2.07487,2.07487,0,0,1,.5963,1.8344l-.5733,3.3371,2.9969-1.5755A2.074,2.074,0,0,1,48.5918,37.2111Z">
       </path>
       <path d="M15.6777,44.8838a2.07182,2.07182,0,0,1-2.0422-2.4218l1.098-6.4022-4.6508-4.5335a2.0715,2.0715,0,0,1,1.1479-3.5339l6.427-.934,2.8758-5.825a2.06991,2.06991,0,0,1,1.8575-1.1545h0a2.06937,2.06937,0,0,1,1.85732,1.1551l2.87418,5.8244,6.4285.934a2.07157,2.07157,0,0,1,1.1479,3.5339l-4.6521,4.5335,1.0427,6.0731a2.07467,2.07467,0,0,1-1.9585,2.7509c-.008-.0005-.0188,0-.027,0a2.07163,2.07163,0,0,1-.9643-.2381l-5.7487-3.0219-5.7488,3.0219A2.07256,2.07256,0,0,1,15.6777,44.8838Zm.3022-13.3945,2.425,2.3638a2.07348,2.07348,0,0,1,.5962,1.8337l-.5718,3.3384,2.9971-1.5761a2.07489,2.07489,0,0,1,1.92882,0l2.99708,1.5755-.5733-3.3371a2.075,2.075,0,0,1,.59622-1.8344l2.42518-2.3638-3.3519-.487a2.0692,2.0692,0,0,1-1.5592-1.1338l-1.4984-3.0361-1.5,3.0368a2.06975,2.06975,0,0,1-1.5594,1.1331Z">
       </path>
      </svg>
      <span class="nav-label">
       Bewertungen
      </span>
     </a>
    </div>
   </details>
   <details @toggle="submenuChanged($event)" class="nav-submenu">
    <summary @keydown.arrow-down.prevent="focusSubmenu($event, false)" @keydown.arrow-up.prevent="focusSubmenu($event, true)">
     <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
      </path>
     </svg>
     <span class="nav-label">
      Ratgeber
     </span>
     <svg aria-hidden="true" class="nav-chevron" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="m19 9-7 7-7-7" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
      </path>
     </svg>
    </summary>
    <div class="nav-submenu-list">
     <a @click="closeMenu(false)" href="{{ route('guidance') }}" wire:navigate="">
      <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" stroke="currentColor" stroke-width="1.5" viewbox="0 0 24 24">
       <path d="M12 15.5c2.485 0 4.5-2.015 4.5-4.5S14.485 6.5 12 6.5 7.5 8.515 7.5 11s2.015 4.5 4.5 4.5Zm0 0v2.25m-6.75 2.25a7.5 7.5 0 1 1 13.5 0" stroke-linecap="round" stroke-linejoin="round">
       </path>
      </svg>
      <span class="nav-label">
       Beratung
      </span>
     </a>
     <a @click="closeMenu(false)" href="{{ route('faqs') }}" wire:navigate="">
      <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
       <path d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
       </path>
      </svg>
      <span class="nav-label">
       FAQ's
      </span>
     </a>
    </div>
   </details>
   @if(\App\Models\Setting::enabled('webcontent', 'blog_enabled', false))
   <a @click="closeMenu(false)" href="{{ route('blog.index') }}" wire:navigate="">
    <svg aria-hidden="true" class="nav-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewbox="0 0 24 24">
     <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z">
     </path>
     <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z">
     </path>
    </svg>
    <span class="nav-label">
     Blog
    </span>
   </a>
   @endif
   @if(\App\Models\Setting::enabled('webcontent', 'news_enabled', false) || app(\App\Support\NewsPreviewAccess::class)->isActive(request()))
   <a @click="closeMenu(false)" href="{{ route('news.index') }}" wire:navigate="">
    <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewbox="0 0 24 24">
     <path d="M4 5h13a3 3 0 0 1 3 3v11H7a3 3 0 0 1-3-3V5Z">
     </path>
     <path d="M8 9h8">
     </path>
     <path d="M8 13h6">
     </path>
     <path d="M4 16H3a1 1 0 0 1-1-1V7">
     </path>
    </svg>
    <span class="nav-label">
     News
    </span>
   </a>
   @endif
   <details @toggle="submenuChanged($event)" class="nav-submenu">
    <summary @keydown.arrow-down.prevent="focusSubmenu($event, false)" @keydown.arrow-up.prevent="focusSubmenu($event, true)">
     <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
      </path>
     </svg>
     <span class="nav-label">
      Informationen
     </span>
     <svg aria-hidden="true" class="nav-chevron" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="m19 9-7 7-7-7" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
      </path>
     </svg>
    </summary>
    <div class="nav-submenu-list">
     <a @click="closeMenu(false)" href="{{ route('aboutus') }}" wire:navigate="">
      <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
       <path d="M6 12c.263 0 .524-.06.767-.175a2 2 0 0 0 .65-.491c.186-.21.333-.46.433-.734.1-.274.15-.568.15-.864a2.4 2.4 0 0 0 .586 1.591c.375.422.884.659 1.414.659.53 0 1.04-.237 1.414-.659A2.4 2.4 0 0 0 12 9.736a2.4 2.4 0 0 0 .586 1.591c.375.422.884.659 1.414.659.53 0 1.04-.237 1.414-.659A2.4 2.4 0 0 0 16 9.736c0 .295.052.588.152.861s.248.521.434.73a2 2 0 0 0 .649.488 1.809 1.809 0 0 0 1.53 0 2.03 2.03 0 0 0 .65-.488c.185-.209.332-.457.433-.73.1-.273.152-.566.152-.861 0-.974-1.108-3.85-1.618-5.121A.983.983 0 0 0 17.466 4H6.456a.986.986 0 0 0-.93.645C5.045 5.962 4 8.905 4 9.736c.023.59.241 1.148.611 1.567.37.418.865.667 1.389.697Zm0 0c.328 0 .651-.091.94-.266A2.1 2.1 0 0 0 7.66 11h.681a2.1 2.1 0 0 0 .718.734c.29.175.613.266.942.266.328 0 .651-.091.94-.266.29-.174.537-.427.719-.734h.681a2.1 2.1 0 0 0 .719.734c.289.175.612.266.94.266.329 0 .652-.091.942-.266.29-.174.536-.427.718-.734h.681c.183.307.43.56.719.734.29.174.613.266.941.266a1.819 1.819 0 0 0 1.06-.351M6 12a1.766 1.766 0 0 1-1.163-.476M5 12v7a1 1 0 0 0 1 1h2v-5h3v5h7a1 1 0 0 0 1-1v-7m-5 3v2h2v-2h-2Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
       </path>
      </svg>
      <span class="nav-label">
       Unternehmen
      </span>
     </a>
     <a @click="closeMenu(false)" href="{{ route('howto') }}" wire:navigate="">
      <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
       <path d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
       </path>
      </svg>
      <span class="nav-label">
       So funktionierts
      </span>
     </a>
     <a @click="closeMenu(false)" href="{{ route('abos') }}" wire:navigate="">
      <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewbox="0 0 24 24">
       <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
       </polygon>
      </svg>
      <span class="nav-label">
       Abonnements
      </span>
     </a>
     <a @click="closeMenu(false)" href="{{ route('contact') }}" wire:navigate="">
      <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
       <path d="m3.5 5.5 7.893 6.036a1 1 0 0 0 1.214 0L20.5 5.5M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z" stroke="currentColor" stroke-linecap="round" stroke-width="1.5">
       </path>
      </svg>
      <span class="nav-label">
       Kontakt
      </span>
     </a>
    </div>
   </details>
   <div class="mobile-nav-utilities">
    @auth
    <a href="{{ route('dashboard') }}" wire:navigate @click="closeMenu(false)">Mein Konto</a>
    <a href="{{ route('profile.show') }}" wire:navigate @click="closeMenu(false)">Profil</a>
    @if(auth()->user()->role === 'guest')
        <a href="{{ route('messages') }}" wire:navigate @click="closeMenu(false)">Nachrichten @if($homepageUnreadMessages > 0)<span class="homepage-message-count">{{ $homepageUnreadMessages }}</span>@endif</a>
    @endif
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="homepage-account-logout" type="submit">Abmelden</button>
    </form>
@else
<a @click="closeMenu(false)" href="{{ route('login') }}" wire:navigate=""><svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M10 14v3m4-6V7a3 3 0 1 1 6 0v4M5 11h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
</svg><span class="nav-label">Anmelden</span></a><a @click="closeMenu(false)" href="{{ route('register') }}" wire:navigate=""><svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M7 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h1m4-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm7.441 1.559a1.907 1.907 0 0 1 0 2.698l-6.069 6.069L10 19l.674-3.372 6.07-6.07a1.907 1.907 0 0 1 2.697 0Z" stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="1.5"></path>
</svg><span class="nav-label">Registrieren</span></a>
@endauth
    <button :aria-label="theme === 'dark' ? 'Zum hellen Modus wechseln' : 'Zum dunklen Modus wechseln'" :aria-pressed="String(theme === 'dark')" @click="toggleTheme()" aria-label="Zum dunklen Modus wechseln" aria-pressed="false" class="theme-toggle mobile-theme-toggle" type="button">
     <svg aria-hidden="true" class="theme-icon-sun" fill="none" height="20" stroke="currentColor" stroke-width="1.5" viewbox="0 0 24 24" width="20">
      <circle cx="12" cy="12" r="3.5">
      </circle>
      <path d="M12 2v2m0 16v2M2 12h2m16 0h2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4">
      </path>
     </svg>
     <svg aria-hidden="true" class="theme-icon-moon" fill="none" height="20" stroke="currentColor" stroke-width="1.5" viewbox="0 0 24 24" width="20">
      <path d="M20.4 14.1A8.7 8.7 0 0 1 9.9 3.6a8.7 8.7 0 1 0 10.5 10.5Z">
      </path>
     </svg>
     <span class="theme-label">
      Darstellung
     </span>
    </button>
   </div>
  </nav>
  <div class="header-tools">
   <button @click="openSearch()" :aria-expanded="String(searchOpen)" aria-controls="Search-menü" aria-haspopup="dialog" aria-label="Suche öffnen" class="header-search" title="Suche" type="button" x-ref="searchButton">
    <svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
     <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm8-2l4 4" stroke-linecap="round" stroke-linejoin="round">
     </path>
    </svg>
   </button>
   <button :aria-label="theme === 'dark' ? 'Zum hellen Modus wechseln' : 'Zum dunklen Modus wechseln'" :aria-pressed="String(theme === 'dark')" @click="toggleTheme()" aria-label="Zum dunklen Modus wechseln" aria-pressed="false" class="theme-toggle" type="button">
    <svg aria-hidden="true" class="theme-icon-sun" fill="none" height="20" stroke="currentColor" stroke-width="1.5" viewbox="0 0 24 24" width="20">
     <circle cx="12" cy="12" r="3.5">
     </circle>
     <path d="M12 2v2m0 16v2M2 12h2m16 0h2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4">
     </path>
    </svg>
    <svg aria-hidden="true" class="theme-icon-moon" fill="none" height="20" stroke="currentColor" stroke-width="1.5" viewbox="0 0 24 24" width="20">
     <path d="M20.4 14.1A8.7 8.7 0 0 1 9.9 3.6a8.7 8.7 0 1 0 10.5 10.5Z">
     </path>
    </svg>
   </button>
   <details @toggle="submenuChanged($event)" class="header-account nav-submenu">
    <summary @keydown.arrow-down.prevent="focusSubmenu($event, false)" @keydown.arrow-up.prevent="focusSubmenu($event, true)" aria-label="Konto öffnen" title="Konto">
     @auth
    <img class="homepage-account-avatar" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" width="28" height="28">
@else
<svg aria-hidden="true" class="nav-icon" fill="currentColor" focusable="false" viewbox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
<path d="M337.711 241.3a16 16 0 0 0-11.461 3.988c-18.739 16.561-43.688 25.682-70.25 25.682s-51.511-9.121-70.25-25.683a16.007 16.007 0 0 0-11.461-3.988c-78.926 4.274-140.752 63.672-140.752 135.224v107.152C33.537 499.293 46.9 512 63.332 512h385.336c16.429 0 29.8-12.707 29.8-28.325V376.523c-.005-71.552-61.831-130.95-140.757-135.223zM446.463 480H65.537V376.523c0-52.739 45.359-96.888 104.351-102.8C193.75 292.63 224.055 302.97 256 302.97s62.25-10.34 86.112-29.245c58.992 5.91 104.351 50.059 104.351 102.8zM256 234.375a117.188 117.188 0 1 0-117.188-117.187A117.32 117.32 0 0 0 256 234.375zM256 32a85.188 85.188 0 1 1-85.188 85.188A85.284 85.284 0 0 1 256 32z" data-original="#000000"></path>
</svg>
@endauth
    </summary>
    <div class="nav-submenu-list">
     @auth
    <span class="homepage-account-heading">{{ auth()->user()->name }}</span>
    <a href="{{ route('dashboard') }}" wire:navigate><span class="nav-label">Mein Konto</span></a>
    <a href="{{ route('profile.show') }}" wire:navigate><span class="nav-label">Profil</span></a>
    @if(auth()->user()->role === 'guest')
        <a href="{{ route('messages') }}" wire:navigate><span class="nav-label">Nachrichten</span>
            @if($homepageUnreadMessages > 0)<span class="homepage-message-count">{{ $homepageUnreadMessages }}</span>@endif
        </a>
    @endif
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="homepage-account-logout" type="submit">Abmelden</button>
    </form>
@else
<a @click="closeMenu(false)" href="{{ route('login') }}" wire:navigate=""><svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M10 14v3m4-6V7a3 3 0 1 1 6 0v4M5 11h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
</svg><span class="nav-label">Anmelden</span></a><a @click="closeMenu(false)" href="{{ route('register') }}" wire:navigate=""><svg aria-hidden="true" class="nav-icon" fill="none" focusable="false" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M7 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h1m4-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm7.441 1.559a1.907 1.907 0 0 1 0 2.698l-6.069 6.069L10 19l.674-3.372 6.07-6.07a1.907 1.907 0 0 1 2.697 0Z" stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="1.5"></path>
</svg><span class="nav-label">Registrieren</span></a>
@endauth
    </div>
   </details>
   <button :aria-expanded="String(menuOpen)" :aria-label="menuOpen ? 'Menü schließen' : 'Hauptmenü öffnen'" @click="toggleMenu()" aria-controls="site-nav" aria-expanded="false" aria-label="Hauptmenü öffnen" class="menu-toggle" type="button" x-ref="menuToggle">
    <span>
     Menü
    </span>
    <span aria-hidden="true" class="menu-lines">
    </span>
   </button>
  </div>
 </div>
 <button @click="closeMenu(true)" aria-hidden="true" class="menu-backdrop" data-close-menu="" tabindex="-1" type="button">
 </button>
</header>

<div id="megamenu" class="homepage-search-layer"></div>
<livewire:tools.search-modal :show-trigger="false" />
