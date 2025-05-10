<nav  x-data="{ 
        focused: false, 
        isMobileMenuOpen: false, 
        screenWidth: window.innerWidth, 
        navHeight: 0 
    }" 
    x-init="navHeight = $el.offsetHeight" 
    x-resize="screenWidth = $width; " 
    class="fixed max-h-24 top-0 w-screen bg-white border-b border-gray-100 shadow-lg px-8 z-30"  
    wire:loading.class="cursor-wait"
>
    <!-- Primary Navigation Menu -->
    <div class="container mx-auto flex flex-wrap justify-between items-center">
            <div class="max-md:order-1 md:hidden ">
                <div class="flex items-center h-full py-2 pr-4 mr-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 18a8 8 0 100-16 8 8 0 000 16zm8-2l4 4" />
                    </svg>
                </div>
            </div>
            <div class="shrink-0 flex items-center h-full py-2 max-md:order-2" >
                <a href="/" wire:navigate   class="h-full flex items-center max-sm:max-w-[120px]">
                    <x-application-mark />
                </a>
            </div>
            <div class="flex items-center space-x-4 max-md:order-3 md:order-2" >
                <!-- Likes and Inbox Buttons -->
                <div class="flex items-center space-x-6 mr-2">
                    @if (optional(Auth::user())->role === 'guest' && $currentUrl !== url('/messages'))
                    <div class="relative" x-data="{ open: false, modalOpen: false, selectedMessage: null  }">
                        <!-- Button zum Öffnen des Popups -->
                        <button @click="open = !open" class="block">
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
                    @endif
                </div>


                <div class="hidden md:block">

                    @auth
                        <!-- Settings Dropdown -->
                        <div class="ms-3 relative">
                            <x-dropdown align="" width="48">
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
                            </x-dropdown>
                        </div>
                    @else
                        <!-- Guest Dropdown -->
                        <div class="ms-3 relative">
                            <x-dropdown align="" width="48">
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
                            </x-dropdown>
                        </div>
                    @endauth
                </div>
                
                <a class="inline-flex items-center p-2 ml-1  md:hidden focus:outline-none "   @click="isMobileMenuOpen = !isMobileMenuOpen;">
                    <div class=" z-50 text-gray-600 text-sm text-gray-500 rounded-lg hover:bg-gray-100  burger-container "  :class="isMobileMenuOpen ? 'is-open' : ''" >
                            <div class="burger-bar bar1"></div>
                            <div class="burger-bar bar2"></div>
                            <div class="burger-bar bar3"></div>
                    </div>
                    <span class="sr-only">Öffnen Hauptmenü</span>
                </a>
            </div>
            <!-- Navigation Links -->
            <div x-show="isMobileMenuOpen || screenWidth >= 768" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 "
                    x-transition:leave-end="opacity-0"
                    x-data="{
                          isPadded:  (navHeight > 0 && screenWidth <= 768 ? true : false)              
                    }"
                    :style="isPadded ? 'top: ' + navHeight + 'px; height: calc(100vh - ' + navHeight + 'px);' : ''"
                    x-cloak   class="max-md:order-3 md:order-1 max-md:fixed  max-md:inset-0   max-md:block  max-md:bg-black max-md:bg-opacity-50 max-md:z-30" >
                    
                    <div @click.away="isMobileMenuOpen = false"  
                                    :class="isMobileMenuOpen ? 'max-md:translate-x-0' : 'max-md:translate-x-full'"
                                    :style="isPadded ? 'top: ' + navHeight + 'px; height: calc(100vh - ' + navHeight + 'px);' : ''"
                            x-cloak  class="grid  content-between transition-transform  ease-out duration-400  max-md:bg-white max-md:min-w-80 max-md:right-0  max-md:fixed max-md:overflow-y-auto max-md:py-5 max-md:px-3  max-md:border-r max-md:border-gray-200">
                        <div  class="md:space-x-8 max-md:block   max-md:space-y-4 md:-my-px md:mx-4 max-md:gap-3 md:flex  w-full  " >
                            <!-- Gäste-Spezifische Navigation -->
                            <x-nav-link href="/" wire:navigate  :active="request()->is('/')">
                                    <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"/>
                                    </svg>

                                {{ __('Home') }}
                            </x-nav-link>
                            <x-nav-link href="/insurances" wire:navigate  :active="request()->is('insurances')">
                                    <svg  class="w-4 max-md:w-6 aspect-square mr-1 max-md:mr-2 text-gray-800" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                                {{ __('Versicherungen') }}
                            </x-nav-link>
                            <x-nav-link href="/reviews" wire:navigate  :active="request()->is('reviews')">
                                    <svg class="w-4 max-md:w-6 aspect-square mr-1 max-md:mr-2 text-gray-800"  viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                {{ __('Bewertungen') }}
                            </x-nav-link>
                            <x-nav-link href="/ranking" wire:navigate  :active="request()->is('ranking')">
                                    
                                    <svg class="w-4 max-md:w-6 aspect-square mr-1 max-md:mr-2 text-gray-800"  viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                                {{ __('Ranking ') }}
                            </x-nav-link>
                            

                                <div x-data="{ open: false }" @click.away="open = false"  class="relative md:px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 md:hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" >
                                        <div class="flex items-center cursor-pointer max-md:text-lg max-md:px-3" @click="open = !open">
                                            <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                            </svg>
                                                {{ __('Über uns') }}
                                            <svg class="w-4 h-4 ml-2  transition-all ease-in duration-200" :class="open ? 'transform rotate-180' : ''" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7"/>
                                            </svg>

                                        </div>
                                        <div x-show="open" x-transition x-cloak class="md:bg-white md:right-0 mt-3  z-30" :class="screenWidth <= 768 ? 'relative' : 'absolute rounded-lg shadow w-44 z-10 overflow-hidden'">
                                            <ul class=" max-md:space-y-4 max-md:pt-4 text-sm text-gray-500 hover:text-gray-700" :class="screenWidth <= 768 ? '' : 'divide-y divide-gray-100'">
                                                <li>
                                                    <a href="/aboutus" wire:navigate class='max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100'>
                                                        <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 12c.263 0 .524-.06.767-.175a2 2 0 0 0 .65-.491c.186-.21.333-.46.433-.734.1-.274.15-.568.15-.864a2.4 2.4 0 0 0 .586 1.591c.375.422.884.659 1.414.659.53 0 1.04-.237 1.414-.659A2.4 2.4 0 0 0 12 9.736a2.4 2.4 0 0 0 .586 1.591c.375.422.884.659 1.414.659.53 0 1.04-.237 1.414-.659A2.4 2.4 0 0 0 16 9.736c0 .295.052.588.152.861s.248.521.434.73a2 2 0 0 0 .649.488 1.809 1.809 0 0 0 1.53 0 2.03 2.03 0 0 0 .65-.488c.185-.209.332-.457.433-.73.1-.273.152-.566.152-.861 0-.974-1.108-3.85-1.618-5.121A.983.983 0 0 0 17.466 4H6.456a.986.986 0 0 0-.93.645C5.045 5.962 4 8.905 4 9.736c.023.59.241 1.148.611 1.567.37.418.865.667 1.389.697Zm0 0c.328 0 .651-.091.94-.266A2.1 2.1 0 0 0 7.66 11h.681a2.1 2.1 0 0 0 .718.734c.29.175.613.266.942.266.328 0 .651-.091.94-.266.29-.174.537-.427.719-.734h.681a2.1 2.1 0 0 0 .719.734c.289.175.612.266.94.266.329 0 .652-.091.942-.266.29-.174.536-.427.718-.734h.681c.183.307.43.56.719.734.29.174.613.266.941.266a1.819 1.819 0 0 0 1.06-.351M6 12a1.766 1.766 0 0 1-1.163-.476M5 12v7a1 1 0 0 0 1 1h2v-5h3v5h7a1 1 0 0 0 1-1v-7m-5 3v2h2v-2h-2Z"/>
                                                        </svg>
                                                        Unternehmen
                                                    </a>
                                                </li>
                                                <li >
                                                    <a  href="/faqs" wire:navigate  class='max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100'>
                                                        <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                        </svg>

                                                        
                                                    FAQ's
                                                    </a>
                                                </li>
                                                <li >
                                                    <a  href="/howto" wire:navigate class='max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100'>
                                                        <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                        </svg>


                                                    So funktionierts
                                                    </a>
                                                </li>
                                                <li >
                                                    <a  href="/contact" wire:navigate  class='max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100'>
                                                        <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="m3.5 5.5 7.893 6.036a1 1 0 0 0 1.214 0L20.5 5.5M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                                                        </svg>

                                                    Kontakt
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                </div>
                                <x-nav-link href="/premium" wire:navigate  :active="request()->is('premium')">
                                    <svg class="w-4 max-md:w-6 aspect-square mr-1 max-md:mr-2 text-gray-800"  viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                {{ __('Premium ') }}
                            </x-nav-link>
                            <!-- Kunden-Spezifische Navigation -->
                            @if (optional(Auth::user())->role === 'guest' || optional(Auth::user())->role === 'admin')
                                <x-nav-link href="/dashboard" wire:navigate  :active="request()->is('dashboard')">
                                    <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                                        </svg>
                                    {{ __('Mein Konto') }}
                                </x-nav-link>
                            @endif  
                             <div class="md:hidden block mt-6">
                                <div class="border-t border-gray-200"></div>
                                @auth
                                 <div class="block px-4 py-2 text-xs text-gray-400">
                                     {{ __('Konto verwalten') }}
                                 </div>
                                 <x-nav-link href="{{ route('profile.show') }}">
                                    <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm7 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
                                    </svg>
 
                                     {{ __('Profil') }}
                                 </x-nav-link>
                                 
                                 <form method="POST" action="{{ route('logout') }}" x-data>
                                     @csrf
                                     <x-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                         <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                             <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                         </svg>
 
                                         {{ __('Abmelden') }}
                                     </x-nav-link>
                                 </form>
                                @else
                                    <x-nav-link href="/login">
                                        <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14v3m4-6V7a3 3 0 1 1 6 0v4M5 11h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/>
                                        </svg>

                                        {{ __('Anmelden') }}
                                    </x-nav-link>
                                    <x-nav-link href="/register">
                                        <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="1.5" d="M7 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h1m4-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm7.441 1.559a1.907 1.907 0 0 1 0 2.698l-6.069 6.069L10 19l.674-3.372 6.07-6.07a1.907 1.907 0 0 1 2.697 0Z"/>
                                        </svg>
                                        {{ __('Registrieren') }}
                                    </x-nav-link>
                                @endauth

                             </div>
                        </div>
                        <div class="md:hidden max-md:flex self-end  bottom-0 left-0 justify-center p-4 space-x-4 w-full bg-white  z-20 border-t border-gray-200">
                            <ul class="mt-10 flex space-x-5">
                                <li>
                                <a href='' target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="fill-gray-300 hover:fill-gray-500 w-10 h-10"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7v-7h-2v-3h2V8.5A3.5 3.5 0 0 1 15.5 5H18v3h-2a1 1 0 0 0-1 1v2h3v3h-3v7h4a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"
                                        clip-rule="evenodd" />
                                    </svg>
                                    <span class="sr-only">Facebook Link</span>
                                </a>
                                </li>
                                <li>
                                <a href='' target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    class="fill-gray-300 hover:fill-gray-500 w-10 h-10" viewBox="0 0 24 24">
                                    <path
                                        d="M12 9.3a2.7 2.7 0 1 0 0 5.4 2.7 2.7 0 0 0 0-5.4Zm0-1.8a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9Zm5.85-.225a1.125 1.125 0 1 1-2.25 0 1.125 1.125 0 0 1 2.25 0ZM12 4.8c-2.227 0-2.59.006-3.626.052-.706.034-1.18.128-1.618.299a2.59 2.59 0 0 0-.972.633 2.601 2.601 0 0 0-.634.972c-.17.44-.265.913-.298 1.618C4.805 9.367 4.8 9.714 4.8 12c0 2.227.006 2.59.052 3.626.034.705.128 1.18.298 1.617.153.392.333.674.632.972.303.303.585.484.972.633.445.172.918.267 1.62.3.993.047 1.34.052 3.626.052 2.227 0 2.59-.006 3.626-.052.704-.034 1.178-.128 1.617-.298.39-.152.674-.333.972-.632.304-.303.485-.585.634-.972.171-.444.266-.918.299-1.62.047-.993.052-1.34.052-3.626 0-2.227-.006-2.59-.052-3.626-.034-.704-.128-1.18-.299-1.618a2.619 2.619 0 0 0-.633-.972 2.595 2.595 0 0 0-.972-.634c-.44-.17-.914-.265-1.618-.298-.993-.047-1.34-.052-3.626-.052ZM12 3c2.445 0 2.75.009 3.71.054.958.045 1.61.195 2.185.419A4.388 4.388 0 0 1 19.49 4.51c.457.45.812.994 1.038 1.595.222.573.373 1.227.418 2.185.042.96.054 1.265.054 3.71 0 2.445-.009 2.75-.054 3.71-.045.958-.196 1.61-.419 2.185a4.395 4.395 0 0 1-1.037 1.595 4.44 4.44 0 0 1-1.595 1.038c-.573.222-1.227.373-2.185.418-.96.042-1.265.054-3.71.054-2.445 0-2.75-.009-3.71-.054-.958-.045-1.61-.196-2.185-.419A4.402 4.402 0 0 1 4.51 19.49a4.414 4.414 0 0 1-1.037-1.595c-.224-.573-.374-1.227-.419-2.185C3.012 14.75 3 14.445 3 12c0-2.445.009-2.75.054-3.71s.195-1.61.419-2.185A4.392 4.392 0 0 1 4.51 4.51c.45-.458.994-.812 1.595-1.037.574-.224 1.226-.374 2.185-.419C9.25 3.012 9.555 3 12 3Z" />
                                    </svg>
                                    <span class="sr-only">Instagram Link</span>
                                </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                 </div>
    </div>
</nav>
