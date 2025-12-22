<div
    x-data="{ 
        screenWidth: window.innerWidth,
        isScrolled: false,
        scrollTop: 0,
        lastScrollTop: 0,
        scrollDirection: 'up'
    }"
    x-init="$nextTick(() => {
        $store.nav.height = $refs.nav.offsetHeight;
        $store.nav.isScreenXl = window.innerWidth >= 1280;
        $store.nav.isMobile = window.innerWidth <= 1280;
    })"
    x-on:scroll.window="
        const current = window.scrollY || 0;

        if (current === 0) {
            $store.nav.showNav = true;
            lastScrollTop = 0;
            return;
        }
        const diff    = current - lastScrollTop;

        // Kleine Jitter komplett ignorieren
        if (Math.abs(diff) < 2) return;

        scrollTop  = current;
        isScrolled = current > 0;
        if (diff > 0) {
            // Wir bewegen uns nach unten
            scrollDirection = 'down';
            // Nur ausblenden, wenn wir wirklich unten sind und etwas 'richtig' runterscrollen
            if (current > 120 && diff > 5 && $store.nav.showNav) {
                $dispatch('navhide');
                $store.nav.showNav = false;
            }
        } else {
            // Wir bewegen uns nach oben
            scrollDirection = 'up';

            // Immer sichtbar im oberen Bereich
            if (current <= 120) {
                if (!$store.nav.showNav) {
                    $store.nav.showNav = true;
                }
            } else if (diff < -10) {
                // Deutlich nach oben -> einblenden
                if (!$store.nav.showNav) {
                    $store.nav.showNav = true;
                }
            }
        }
        lastScrollTop = current < 0 ? 0 : current;
    "
    x-resize="
        $nextTick(() => {
            screenWidth = window.innerWidth;
            if (screenWidth >= 1280) {
                $store.nav.isScreenXl = true;
                $store.nav.isMobileMenuOpen = false;
                $store.nav.isMobile = false;
            } else {
                $store.nav.isMobile = true;
                $store.nav.isScreenXl = false;
                $store.nav.isMobileMenuOpen = false;
            }
            $store.nav.height = $refs.nav.offsetHeight; 
        })"
    @click.away="$store.nav.isMobileMenuOpen = false"
    wire:ignore
    >
    <div class="">
        <nav x-ref="nav"  :style="(!$store.nav.showNav && !$store.nav.isMobileMenuOpen ) ? 'margin-top: -'+$store.nav.height+'px': 'margin-top:0px;' " class="fixed w-full z-30 transition-all duration-300 ease-in-out" wire:loading.class="cursor-wait">
            <div class="w-full  mt-2 mb-2">
                <x-ui.basic.content-container class="px-3">
                    <div class="flex flex-wrap justify-between items-center place-items-stretch">
                        <div class="max-xl:order-1  xl:order-2  flex-none self-stretch flex " @click="$store.nav.isMobileMenuOpen = false">
                            <livewire:tools.search-modal />
                            <div class="xl:hidden flex  items-center ">
             
                                 @auth
                                     <!-- Settings Dropdown -->
                                     <div class="ms-1 relative">
                                                                 <x-ui.dropdown.anchor-dropdown
                                            align="left"
                                            width="auto"
                                            :matchTriggerWidth="false"
                                            dropdownClasses=" bg-white  rounded-xl shadow overflow-hidden"
                                            contentClasses="bg-white"
                                            :overlay="false"
                                            :trap="false"
                                            :offset="20"
                                            :scrollOnOpen="false"
                                            :scrollOnTrigger="false"
                                            >
                                             <x-slot name="trigger">
                                                 <button
                                                     class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                                     <img class="h-8 w-8 rounded-full object-cover"
                                                         src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                                 </button>
                                             </x-slot>
                                             <x-slot name="content">
                                                 <div class="block px-4 py-2 text-xs text-gray-400">
                                                     {{ __('Konto verwalten') }}
                                                 </div>
                                                 <x-dropdown-link href="{{ route('profile.show') }}">
                                                 <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm7 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
                                                 </svg>
                                                     {{ __('Profil') }}
                                                 </x-dropdown-link>
                                                 <div class="border-t border-gray-200"></div>
                                                 <form method="POST" action="{{ route('logout') }}" x-data>
                                                     @csrf
                                                     <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                                         <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                             <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                         </svg>
                                                         {{ __('Abmelden') }}
                                                     </x-dropdown-link>
                                                 </form>
                                             </x-slot>
                                         </x-ui.dropdown.anchor-dropdown>
                                     </div>
                                 @else
                                     <!-- Guest Dropdown -->
                                     <div class="ms-1 relative">
                                                               <x-ui.dropdown.anchor-dropdown
                                            align="left"
                                            width="auto"
                                            :matchTriggerWidth="false"
                                            dropdownClasses=" bg-white  rounded-xl shadow overflow-hidden"
                                            contentClasses="bg-white"
                                            :overlay="false"
                                            :trap="false"
                                            :offset="20"
                                            :scrollOnOpen="false"
                                            :scrollOnTrigger="false"
                                            >
                                             <x-slot name="trigger">
                                                 <button
                                                     class="flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-400">
                                                     <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5" viewBox="0 0 512 512">
                                                         <path
                                                         d="M337.711 241.3a16 16 0 0 0-11.461 3.988c-18.739 16.561-43.688 25.682-70.25 25.682s-51.511-9.121-70.25-25.683a16.007 16.007 0 0 0-11.461-3.988c-78.926 4.274-140.752 63.672-140.752 135.224v107.152C33.537 499.293 46.9 512 63.332 512h385.336c16.429 0 29.8-12.707 29.8-28.325V376.523c-.005-71.552-61.831-130.95-140.757-135.223zM446.463 480H65.537V376.523c0-52.739 45.359-96.888 104.351-102.8C193.75 292.63 224.055 302.97 256 302.97s62.25-10.34 86.112-29.245c58.992 5.91 104.351 50.059 104.351 102.8zM256 234.375a117.188 117.188 0 1 0-117.188-117.187A117.32 117.32 0 0 0 256 234.375zM256 32a85.188 85.188 0 1 1-85.188 85.188A85.284 85.284 0 0 1 256 32z"
                                                         data-original="#000000"></path>
                                                     </svg>
                                                 </button>
                                             </x-slot>
                                             <x-slot name="content">
                                                 <x-dropdown-link href="/login">
                                                     <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                         <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14v3m4-6V7a3 3 0 1 1 6 0v4M5 11h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/>
                                                     </svg>
                 
                                                     {{ __('Anmelden') }}
                                                 </x-dropdown-link>
                                                 <div class="border-t border-gray-200"></div>
                                                 <x-dropdown-link href="/register">
                                                     <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                         <path stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="1.5" d="M7 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h1m4-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm7.441 1.559a1.907 1.907 0 0 1 0 2.698l-6.069 6.069L10 19l.674-3.372 6.07-6.07a1.907 1.907 0 0 1 2.697 0Z"/>
                                                     </svg>
                                                     {{ __('Registrieren') }}
                                                 </x-dropdown-link>
                                             </x-slot>
                                         </x-ui.dropdown.anchor-dropdown>
                                     </div>
                                 @endauth
                             </div>
                         </div>
                         <div class=" flex items-center h-full py-2 max-xl:order-1  flex-none" >
                             <a href="/" wire:navigate   class="h-full flex items-center max-sm:max-w-[120px]">
                                 <x-application-mark />
                             </a>
                         </div>
                         <div class="flex items-center space-x-4 max-xl:order-3 xl:order-2 flex-none md:ml-4 " >
                             <!-- Likes and Inbox Buttons -->
                            <div class="min-w-[20px]">
                                @if (optional(Auth::user())->role === 'guest' && $currentUrl !== url('/messages'))
                                <div class="flex items-center space-x-2 ">
                                    <div class="relative" x-data="{ open: false, modalOpen: false, selectedMessage: null  }">
                                        <!-- Button zum Öffnen des Popups -->
                                        <button @click="open = !open" class="block ">
                                            <span class="relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30px" class="fill-[#333] hover:fill-[#077bff] stroke-2 inline" viewBox="0 0 512 512" stroke-width="106">
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <path d="M479.568,412.096H33.987c-15,0-27.209-12.209-27.209-27.209V130.003c0-15,12.209-27.209,27.209-27.209h445.581      
                                                                    c15,0,27.209,12.209,27.209,27.209v255C506.661,399.886,494.568,412.096,479.568,412.096z 
                                                                    M33.987,114.189      
                                                                    c-8.721,0-15.814,7.093-15.814,15.814v255c0,8.721,7.093,15.814,15.814,15.814h445.581c8.721,0,15.814-7.093,15.814-15.814v-255      
                                                                    c0-8.721-7.093-15.814-15.814-15.814C479.568,114.189,33.987,114.189,33.987,114.189z"/>
                                                                </g>
                                                                <g>
                                                                    <path d="M256.894,300.933c-5.93,0-11.86-1.977-16.744-5.93l-41.977-33.14L16.313,118.491c-2.442-1.977-2.907-5.581-0.93-8.023      
                                                                    c1.977-2.442,5.581-2.907,8.023-0.93l181.86,143.372l42.093,33.14c5.698,4.535,13.721,4.535,19.535,0l41.977-33.14      
                                                                    l181.628-143.372c2.442-1.977,6.047-1.512,8.023,0.93c1.977-2.442,1.512,6.047-0.93,8.023l-181.86,143.372l-41.977,33.14      
                                                                    C268.755,299.072,262.708,300.933,256.894,300.933z"/>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                                @if($unreadMessagesCount >= 1)
                                                    <span class="absolute right-[-9px] -ml-1 top-[-5px] rounded-full bg-red-400 px-1.5 py-0.2 text-xs text-white">
                                                        {{ $unreadMessagesCount }}
                                                    </span>
                                                @endif
                                            </span>
                                        </button>
                                        <!-- Popup -->
                                        <div 
                                            x-show="open" 
                                            x-cloak
                                            class="absolute md:p-4 right-0 md:mt-2 md:w-[24.5rem] max-md:fixed max-md:inset-0 max-md:w-full max-md:top-0 max-md:flex max-md:items-center max-md:justify-center max-md:bg-black max-md:bg-opacity-50 max-md:z-50"
                                            x-transition>
                                            <div @click.away="open = false" class="relative max-w-full max-md:pt-10 divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem]/5 text-slate-900 ring-1 shadow-xl shadow-black/5 ring-slate-700/10 z-50">
                                                        <button type="button" @click="open = false; selectedMessage = null;" class="md:hidden absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                <!-- Nachrichtenliste -->
                                                @forelse($receivedMessages as $message)
                                                <div 
                                                    @click="modalOpen = true; open = false; selectedMessage = { subject: '{{ $message->subject }}', body: '{!! addslashes($message->message) !!}', createdAt: '{{ $message->created_at->diffForHumans() }}' }; $wire.setMessageStatus({{ $message->id }}); " 
                                                    class="flex items-center p-4 hover:bg-slate-50 cursor-pointer @if($message->status == 1) bg-blue-200 @endif">
                                                    <div class="block h-10 w-10 size-4 flex-none rounded-full">
                                                        <x-application-logo class="w-10" />
                                                    </div>
                                                    <div class="ml-4 flex-auto">    
                                                        <div class="font-medium">{{ $message->subject }}</div>
                                                        <div class="mt-1 text-slate-700">
                                                            {{ Str::limit(strip_tags($message->message), 40) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @empty
                                                <div class="p-4 text-center text-slate-700">
                                                    Keine  Nachrichten
                                                </div>
                                                @endforelse
                                                <!-- "Alle ansehen"-Button -->
                                                <div class="p-4">
                                                    <a href="{{ route('messages') }}" 
                                                        class="pointer-events-auto rounded-md px-4 py-2 text-center font-medium ring-1 shadow-xs ring-slate-700/10 hover:bg-slate-50 block">
                                                        Alle Nachrichten ansehen
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal -->
                                        <div 
                                            x-show="modalOpen" 
                                            x-cloak
                                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                                            x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0">
                                            <div @click.away="modalOpen = false"  class="bg-white w-[90%] max-w-md rounded-lg shadow-lg p-6 relative">
                                                <div>
                                                    <button type="button" @click="modalOpen = false; selectedMessage = null;" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                    <div>
                                                        <div class="flex">
                                                            <span class="inline-block  text-xs font-medium text-gray-700 mb-2 bg-green-100 px-2 py-1 rounded-full" x-text="selectedMessage?.createdAt"></span>
                                                        </div>
                                                    </div>
                                                    <h3 class="text-xl font-semibold mb-4 border-b pb-2" x-text="selectedMessage?.subject"></h3>
                                                    <div class="my-6">
                                                        <p class="text-gray-800" x-html="selectedMessage?.body"></p>
                                                    </div>
                                                    </div>
                                                    <div class="flex justify-end mt-4">
                                                        <button type="button" @click="modalOpen = false; isClicked = true; setTimeout(() => isClicked = false, 100)" 
                                                        x-data="{ isClicked: false }" 
                                                        :style="isClicked ? 'transform:scale(0.7);' : 'transform:scale(1);'"
                                                        class="transition-all duration-100 py-2.5 px-5  text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">Schließen</button>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                   </div>
                               @endif
                            </div>
             
             
                             <div class="hidden xl:block">
                                
                                 @auth
                                     <!-- Settings Dropdown -->
                                     <div class="ms-3 relative">
                                        <x-ui.dropdown.anchor-dropdown
                                            align="right"
                                            width="auto"
                                            :matchTriggerWidth="false"
                                            dropdownClasses=" bg-white  rounded-xl shadow overflow-hidden"
                                            contentClasses="bg-white"
                                            :overlay="false"
                                            :trap="false"
                                            :offset="20"
                                            :scrollOnOpen="false"
                                            :scrollOnTrigger="false"
                                            >
                                             <x-slot name="trigger">
                                                 <button
                                                     class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                                     <img class="h-8 w-8 rounded-full object-cover"
                                                         src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                                 </button>
                                             </x-slot>
                                             <x-slot name="content">
                                                 <div class="block px-4 py-2 text-xs text-gray-400">
                                                     {{ __('Konto verwalten') }}
                                                 </div>
                                                 <x-dropdown-link href="{{ route('profile.show') }}">
                                                 <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm7 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
                                                 </svg>
                 
                                                     {{ __('Profil') }}
                                                 </x-dropdown-link>
                                                 
                                                 <div class="border-t border-gray-200"></div>
                                                 <form method="POST" action="{{ route('logout') }}" x-data>
                                                     @csrf
                                                     <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                                         <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                             <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                         </svg>
                 
                                                         {{ __('Abmelden') }}
                                                     </x-dropdown-link>
                                                 </form>
                                             </x-slot>
                                         </x-ui.dropdown.anchor-dropdown>
                                     </div>
                                 @else
                                     <!-- Guest Dropdown -->
                                     <div class="ms-3 relative">
                                            <x-ui.dropdown.anchor-dropdown
                                            align="right"
                                            width="auto"
                                            :matchTriggerWidth="false"
                                            dropdownClasses=" bg-white  rounded-xl shadow overflow-hidden"
                                            contentClasses="bg-white"
                                            :overlay="false"
                                            :trap="false"
                                            :offset="20"
                                            :scrollOnOpen="false"
                                            :scrollOnTrigger="false"
                                            >                                             <x-slot name="trigger">
                                                 <button
                                                     class="flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-400">
                                                     <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5" viewBox="0 0 512 512">
                                                         <path
                                                         d="M337.711 241.3a16 16 0 0 0-11.461 3.988c-18.739 16.561-43.688 25.682-70.25 25.682s-51.511-9.121-70.25-25.683a16.007 16.007 0 0 0-11.461-3.988c-78.926 4.274-140.752 63.672-140.752 135.224v107.152C33.537 499.293 46.9 512 63.332 512h385.336c16.429 0 29.8-12.707 29.8-28.325V376.523c-.005-71.552-61.831-130.95-140.757-135.223zM446.463 480H65.537V376.523c0-52.739 45.359-96.888 104.351-102.8C193.75 292.63 224.055 302.97 256 302.97s62.25-10.34 86.112-29.245c58.992 5.91 104.351 50.059 104.351 102.8zM256 234.375a117.188 117.188 0 1 0-117.188-117.187A117.32 117.32 0 0 0 256 234.375zM256 32a85.188 85.188 0 1 1-85.188 85.188A85.284 85.284 0 0 1 256 32z"
                                                         data-original="#000000"></path>
                                                     </svg>
                                                 </button>
                                             </x-slot>
                                             <x-slot name="content">
                                                 <x-dropdown-link href="/login">
                                                     <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                         <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14v3m4-6V7a3 3 0 1 1 6 0v4M5 11h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/>
                                                     </svg>
                 
                                                     {{ __('Anmelden') }}
                                                 </x-dropdown-link>
                                                 <div class="border-t border-gray-200"></div>
                                                 <x-dropdown-link href="/register">
                                                     <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                         <path stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="1.5" d="M7 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h1m4-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm7.441 1.559a1.907 1.907 0 0 1 0 2.698l-6.069 6.069L10 19l.674-3.372 6.07-6.07a1.907 1.907 0 0 1 2.697 0Z"/>
                                                     </svg>
                                                     {{ __('Registrieren') }}
                                                 </x-dropdown-link>
                                             </x-slot>
                                         </x-ui.dropdown.anchor-dropdown>
                                     </div>
                                 @endauth
                             </div>
                             
                            <a
                                x-ref="burger"
                                class="inline-flex items-center p-2 xl:hidden focus:outline-none"
                                @click="
                                    $store.nav.isMobileMenuOpen = !$store.nav.isMobileMenuOpen;
                                    $dispatch('navhide');
                                "
                                >
                                 <div class=" z-50  text-sm text-gray-500 rounded-lg hover:bg-gray-100  burger-container "
                                        :class="$store.nav.isMobileMenuOpen ? 'is-open' : ''" >
                                      <div class="burger-bar bar1"></div>
                                      <div class="burger-bar bar2"></div>
                                      <div class="burger-bar bar3"></div>
                                 </div>
                                 <span class="sr-only">Öffnen Hauptmenü</span>
                            </a>
                         </div>
                         <!-- Navigation Links -->
                         <div class="hidden xl:block">
                            {{-- =========================
                                DESKTOP NAV (>= xl)
                            ========================= --}}
                            <div class="hidden xl:flex xl:grow xl:order-1 items-center justify-center">
                                <div class="top-navigation">
                                    <div class="md:space-x-4 xl:space-x-8 xl:-my-px md:mx-4 xl:flex items-center w-max mx-auto">
                                        <x-nav.nav-links />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </x-ui.basic.content-container>
                    {{-- =========================
                        MOBILE NAV (< xl)  – anchored dropdown am Burger
                    ========================= --}}
                    <div class="xl:hidden max-xl:order-3">

                        {{-- Overlay --}}
                        <div
                            x-show="$store.nav.isMobileMenuOpen"
                            x-transition.opacity
                            class="fixed inset-0 z-40 bg-black/50"
                            @click="$store.nav.isMobileMenuOpen = false"
                            style="display:none;"
                        ></div>

                        {{-- Panel (anchored) --}}
                        <div
                            x-show="$store.nav.isMobileMenuOpen"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            x-anchor.bottom-end.offset.12.flip.shift="$refs.burger"
                            class="fixed z-50 w-[min(92vw,420px)]"
                            style="display:none; max-height:calc(100vh - 24px);"
                            @click.outside="$store.nav.isMobileMenuOpen = false"
                            x-cloak
                        >
                            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-black/5 overflow-hidden">

                                {{-- Header --}}
                                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <div class="h-9 w-9 rounded-xl bg-primary/10 text-primary grid place-items-center">
                                            <i class="fal fa-bars"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">Menü</span>
                                    </div>

                                    <button
                                        type="button"
                                        class="h-9 w-9 rounded-xl hover:bg-gray-100 grid place-items-center text-gray-600"
                                        @click="$store.nav.isMobileMenuOpen = false"
                                        aria-label="Schließen"
                                    >
                                        <i class="fal fa-times"></i>
                                    </button>
                                </div>

                                {{-- Content (scrollbar) --}}
                                <div class="max-h-[70vh] overflow-y-auto px-3 py-3">
                                    <div class="grid gap-2">
                                        <x-nav.nav-links mobile />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
            </div>
        </nav>
    </div>
    <div :style="'height: ' + $store.nav.height + 'px'" class="min-h-12 md:min-h-[5rem] duration-300 ease-in-out transition-all" wire:ignore> </div>
    <div id="megamenu"   class="transition-all duration-200 ease-in-out " wire:ignore></div>
    <template x-teleport="#bottom-nav">
        <!-- Mobile Bottom Navigation (nur unter md sichtbar) -->
        <nav
            class="fixed bottom-0 inset-x-0 z-10 md:hidden bg-white/95 border-t border-gray-200 backdrop-blur"
        >
            <div class="max-w-6xl mx-auto px-3">
                <div class="flex items-stretch justify-between text-[11px] sm:text-xs text-gray-600">
                    <!-- Bewertungen -->
                    <a href="/reviews"
                    class="flex-1 flex flex-col items-center justify-center py-2 gap-1  "
                    :class="{'text-primary-600' : '{{ request()->is('reviews') ? 'true' : 'false' }}' === 'true'}"
                    >
                        <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 101.5483 81.854"><path d="M12.5401,81.854a2.62509,2.62509,0,0,1-2.6233-2.6214V67.041H6.5149A6.52214,6.52214,0,0,1,0,60.5269V6.4994A6.50622,6.50622,0,0,1,6.4988,0H95.7456a5.80957,5.80957,0,0,1,5.8027,5.8034V60.5269a6.51332,6.51332,0,0,1-6.4987,6.5141H31.2L14.2168,81.2431A2.61867,2.61867,0,0,1,12.5401,81.854ZM6.4988,4.1437a2.358,2.358,0,0,0-2.355,2.3557V60.5269a2.37385,2.37385,0,0,0,2.3711,2.3706h5.4736a2.07185,2.07185,0,0,1,2.0719,2.0718v11.003L29.1188,63.3797a2.07035,2.07035,0,0,1,1.3286-.4822H95.0496a2.36537,2.36537,0,0,0,2.3552-2.3706V5.8034a1.66148,1.66148,0,0,0-1.6592-1.6597Z"></path><path d="M69.0928,47.8188a2.072,2.072,0,0,1-2.0422-2.4218l1.4635-8.5252-6.1952-6.038a2.07165,2.07165,0,0,1,1.1479-3.534l8.5598-1.2444,3.8291-7.757a2.0713,2.0713,0,0,1,3.7148,0l3.8294,7.757,8.5596,1.2444a2.07155,2.07155,0,0,1,1.1479,3.534l-6.1938,6.038,1.4054,8.1961a2.07468,2.07468,0,0,1-1.9706,2.7509h-.0148a2.07552,2.07552,0,0,1-.9645-.2379l-7.65588-4.025-7.65612,4.025A2.07443,2.07443,0,0,1,69.0928,47.8188Zm8.62042-8.6757a2.07318,2.07318,0,0,1,.96438.2382l4.9044,2.5789-.9362-5.462a2.0748,2.0748,0,0,1,.5962-1.8339l3.967-3.8678-5.4832-.797a2.07051,2.07051,0,0,1-1.5592-1.133l-2.45338-4.9698L75.2595,28.8665a2.07,2.07,0,0,1-1.5592,1.133l-5.483.797,3.9683,3.8678a2.07481,2.07481,0,0,1,.5963,1.8339l-.93768,5.4615,4.90448-2.5784A2.07362,2.07362,0,0,1,77.71322,39.1431Z"></path><path d="M55.305,44.8838a2.073,2.073,0,0,1-.9645-.2381l-5.7487-3.0219-5.7487,3.0219a2.07207,2.07207,0,0,1-3.0066-2.1844l1.0994-6.4015-4.6522-4.5335a2.07157,2.07157,0,0,1,1.1479-3.5339l6.42862-.934L46.7345,21.234a2.07107,2.07107,0,0,1,3.71472-.0006l2.87558,5.825,6.42742.934a2.0716,2.0716,0,0,1,1.14768,3.5339l-4.65068,4.5335,1.04128,6.0731a2.07467,2.07467,0,0,1-1.9585,2.7509C55.3238,44.8833,55.313,44.8838,55.305,44.8838Zm-6.7132-7.6727a2.07368,2.07368,0,0,1,.9645.238l2.9972,1.5761-.572-3.3384a2.07451,2.07451,0,0,1,.596-1.8337l2.4253-2.3638-3.3518-.487a2.06993,2.06993,0,0,1-1.5593-1.1331l-1.4999-3.0368-1.4985,3.0361a2.069,2.069,0,0,1-1.5592,1.1338l-3.3518.487,2.4251,2.3638a2.07487,2.07487,0,0,1,.5963,1.8344l-.5733,3.3371,2.9969-1.5755A2.074,2.074,0,0,1,48.5918,37.2111Z"></path><path d="M15.6777,44.8838a2.07182,2.07182,0,0,1-2.0422-2.4218l1.098-6.4022-4.6508-4.5335a2.0715,2.0715,0,0,1,1.1479-3.5339l6.427-.934,2.8758-5.825a2.06991,2.06991,0,0,1,1.8575-1.1545h0a2.06937,2.06937,0,0,1,1.85732,1.1551l2.87418,5.8244,6.4285.934a2.07157,2.07157,0,0,1,1.1479,3.5339l-4.6521,4.5335,1.0427,6.0731a2.07467,2.07467,0,0,1-1.9585,2.7509c-.008-.0005-.0188,0-.027,0a2.07163,2.07163,0,0,1-.9643-.2381l-5.7487-3.0219-5.7488,3.0219A2.07256,2.07256,0,0,1,15.6777,44.8838Zm.3022-13.3945,2.425,2.3638a2.07348,2.07348,0,0,1,.5962,1.8337l-.5718,3.3384,2.9971-1.5761a2.07489,2.07489,0,0,1,1.92882,0l2.99708,1.5755-.5733-3.3371a2.075,2.075,0,0,1,.59622-1.8344l2.42518-2.3638-3.3519-.487a2.0692,2.0692,0,0,1-1.5592-1.1338l-1.4984-3.0361-1.5,3.0368a2.06975,2.06975,0,0,1-1.5594,1.1331Z"></path></svg>
                        <span>Bewertungen</span>
                    </a>
                    <!-- Über uns -->
                    <a href="/aboutus"
                    class="flex-1 flex flex-col items-center justify-center py-2 gap-1  "
                    :class="{'text-primary-600' : '{{ request()->is('aboutus') ? 'true' : 'false' }}' === 'true'}"
                    >
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                                                </svg>
                        <span>Über&nbsp;uns</span>
                    </a>
                    <!-- Versicherungen -->
                    <a href="/insurances"
                    class="flex-1 flex flex-col items-center justify-center py-2 gap-1  "
                    :class="{'text-primary-600' : '{{ request()->is('insurances') ? 'true' : 'false' }}' === 'true'}"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        <span>Versicherungen</span>
                    </a>
                    <!-- Blog -->
                    <a href="/blog"
                    class="flex-1 flex flex-col items-center justify-center py-2 gap-1 "
                    :class="{'text-primary-600' : '{{ request()->is('blog') ? 'true' : 'false' }}' === 'true'}"
                    >
                        <svg viewBox="0 0 24 24" class="w-5 h-5" stroke="currentColor" stroke-width="1.5"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                        <span>Blog</span>
                    </a>
                </div>
            </div>
        </nav>
        <!-- Spacer für Bottom-Navigation auf Mobile -->
        <div class="h-14 md:hidden"></div>
    </template>
</div>