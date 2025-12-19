<div    class="w-full relative  pb-20 " wire:loading.class="cursor-wait">
    <div class="container mx-auto px-4">
        <div x-data="{ selectedTab: $persist('basic') }" class="w-full">
            <!-- Hauptbereich -->
            <div class=" flex flex-col lg:flex-row gap-10">
                <!-- Sidebar -->
                <aside class="lg:w-1/4  xl:w-1/6">
                    <div class="bg-white rounded-lg shadow-xl  border  border-gray-300 w-full  p-6 ">
                        <div class=" text-center">
                            <div class="w-24 aspect-square rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-2xl font-bold mx-auto"><img class="w-full aspect-square rounded-full object-cover"
                                                         src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" /></div>
                            <h2 class="mt-4 text-lg font-semibold truncate text-ellipsis ">{{ $userData->name }}</h2>
                            <p class="text-sm text-gray-500  truncate text-ellipsis">{{ $userData->email }}</p>
                        </div>
                        <nav class="mt-6 space-y-3 text-sm ">
                            <button x-on:click="selectedTab = 'profile'" x-bind:aria-selected="selectedTab === 'profile'" x-bind:tabindex="selectedTab === 'profile' ? '0' : '-1'" x-bind:class="selectedTab === 'profile' ? ' bg-primary-50 font-medium text-primary-800 ' : ' hover:bg-gray-50 '" class="flex items-center gap-3 px-4 py-2  rounded  w-full">
                                <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm7 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"></path>
                                </svg>
                                Profil
                            </button>
                            <button x-on:click="selectedTab = 'basic'" x-bind:aria-selected="selectedTab === 'basic'" x-bind:tabindex="selectedTab === 'basic' ? '0' : '-1'" x-bind:class="selectedTab === 'basic' ? ' bg-primary-50 font-medium text-primary-800 ' : ' hover:bg-gray-50 '" class="flex items-center gap-3 px-4 py-2  rounded  w-full">
                                <svg class="w-5  h-5 xl:min-w-5 mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 101.5483 81.854"><path d="M12.5401,81.854a2.62509,2.62509,0,0,1-2.6233-2.6214V67.041H6.5149A6.52214,6.52214,0,0,1,0,60.5269V6.4994A6.50622,6.50622,0,0,1,6.4988,0H95.7456a5.80957,5.80957,0,0,1,5.8027,5.8034V60.5269a6.51332,6.51332,0,0,1-6.4987,6.5141H31.2L14.2168,81.2431A2.61867,2.61867,0,0,1,12.5401,81.854ZM6.4988,4.1437a2.358,2.358,0,0,0-2.355,2.3557V60.5269a2.37385,2.37385,0,0,0,2.3711,2.3706h5.4736a2.07185,2.07185,0,0,1,2.0719,2.0718v11.003L29.1188,63.3797a2.07035,2.07035,0,0,1,1.3286-.4822H95.0496a2.36537,2.36537,0,0,0,2.3552-2.3706V5.8034a1.66148,1.66148,0,0,0-1.6592-1.6597Z"></path><path d="M69.0928,47.8188a2.072,2.072,0,0,1-2.0422-2.4218l1.4635-8.5252-6.1952-6.038a2.07165,2.07165,0,0,1,1.1479-3.534l8.5598-1.2444,3.8291-7.757a2.0713,2.0713,0,0,1,3.7148,0l3.8294,7.757,8.5596,1.2444a2.07155,2.07155,0,0,1,1.1479,3.534l-6.1938,6.038,1.4054,8.1961a2.07468,2.07468,0,0,1-1.9706,2.7509h-.0148a2.07552,2.07552,0,0,1-.9645-.2379l-7.65588-4.025-7.65612,4.025A2.07443,2.07443,0,0,1,69.0928,47.8188Zm8.62042-8.6757a2.07318,2.07318,0,0,1,.96438.2382l4.9044,2.5789-.9362-5.462a2.0748,2.0748,0,0,1,.5962-1.8339l3.967-3.8678-5.4832-.797a2.07051,2.07051,0,0,1-1.5592-1.133l-2.45338-4.9698L75.2595,28.8665a2.07,2.07,0,0,1-1.5592,1.133l-5.483.797,3.9683,3.8678a2.07481,2.07481,0,0,1,.5963,1.8339l-.93768,5.4615,4.90448-2.5784A2.07362,2.07362,0,0,1,77.71322,39.1431Z"></path><path d="M55.305,44.8838a2.073,2.073,0,0,1-.9645-.2381l-5.7487-3.0219-5.7487,3.0219a2.07207,2.07207,0,0,1-3.0066-2.1844l1.0994-6.4015-4.6522-4.5335a2.07157,2.07157,0,0,1,1.1479-3.5339l6.42862-.934L46.7345,21.234a2.07107,2.07107,0,0,1,3.71472-.0006l2.87558,5.825,6.42742.934a2.0716,2.0716,0,0,1,1.14768,3.5339l-4.65068,4.5335,1.04128,6.0731a2.07467,2.07467,0,0,1-1.9585,2.7509C55.3238,44.8833,55.313,44.8838,55.305,44.8838Zm-6.7132-7.6727a2.07368,2.07368,0,0,1,.9645.238l2.9972,1.5761-.572-3.3384a2.07451,2.07451,0,0,1,.596-1.8337l2.4253-2.3638-3.3518-.487a2.06993,2.06993,0,0,1-1.5593-1.1331l-1.4999-3.0368-1.4985,3.0361a2.069,2.069,0,0,1-1.5592,1.1338l-3.3518.487,2.4251,2.3638a2.07487,2.07487,0,0,1,.5963,1.8344l-.5733,3.3371,2.9969-1.5755A2.074,2.074,0,0,1,48.5918,37.2111Z"></path><path d="M15.6777,44.8838a2.07182,2.07182,0,0,1-2.0422-2.4218l1.098-6.4022-4.6508-4.5335a2.0715,2.0715,0,0,1,1.1479-3.5339l6.427-.934,2.8758-5.825a2.06991,2.06991,0,0,1,1.8575-1.1545h0a2.06937,2.06937,0,0,1,1.85732,1.1551l2.87418,5.8244,6.4285.934a2.07157,2.07157,0,0,1,1.1479,3.5339l-4.6521,4.5335,1.0427,6.0731a2.07467,2.07467,0,0,1-1.9585,2.7509c-.008-.0005-.0188,0-.027,0a2.07163,2.07163,0,0,1-.9643-.2381l-5.7487-3.0219-5.7488,3.0219A2.07256,2.07256,0,0,1,15.6777,44.8838Zm.3022-13.3945,2.425,2.3638a2.07348,2.07348,0,0,1,.5962,1.8337l-.5718,3.3384,2.9971-1.5761a2.07489,2.07489,0,0,1,1.92882,0l2.99708,1.5755-.5733-3.3371a2.075,2.075,0,0,1,.59622-1.8344l2.42518-2.3638-3.3519-.487a2.0692,2.0692,0,0,1-1.5592-1.1338l-1.4984-3.0361-1.5,3.0368a2.06975,2.06975,0,0,1-1.5594,1.1331Z"></path></svg>
                                Bewertungen
                            </button>
                            <button x-on:click="selectedTab = 'settings'" x-bind:aria-selected="selectedTab === 'settings'" x-bind:tabindex="selectedTab === 'settings' ? '0' : '-1'" x-bind:class="selectedTab === 'settings' ? ' bg-primary-50 font-medium text-primary-800 ' : ' hover:bg-gray-50 '" class="flex items-center gap-3 px-4 py-2  rounded  w-full">
                                <svg class="w-5  h-5  xl:min-w-5 mr-1"   xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                                Einstellungen
                            </button>
                            <button x-on:click="selectedTab = 'security'" x-bind:aria-selected="selectedTab === 'security'" x-bind:tabindex="selectedTab === 'security' ? '0' : '-1'" x-bind:class="selectedTab === 'security' ? ' bg-primary-50 font-medium text-primary-800 ' : ' hover:bg-gray-50 '" class="flex items-center gap-3 px-4 py-2  rounded  w-full">
                                <svg class="w-5  h-5  mr-1"   xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                Sicherheit
                            </button>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <a href="{{ route('logout') }}" @click.prevent="$root.submit();" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 rounded  ">
                                    <svg class="w-5 h-5  mr-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                                    </svg>
                                    Abmelden
                                </a>
                            </form>
                        </nav>
                    </div>
                </aside>
                <main class="w-full lg:w-3/4 xl:w-5/6">
                    <div x-cloak x-show="selectedTab === 'profile'" x-collapse id="tabpanelGroups" role="tabpanel" aria-label="profile">
                        <div class="antialiased" wire:loading.class="cursor-wait">
                            <div class=" sm:pl-6 lg:pl-8">
                                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                                    @livewire('profile.update-profile-information-form')

                                    <x-section-border />
                                @endif
                                <!-- Customer Information Livewire-Komponente -->
                                <div class="mt-10 sm:mt-0">
                                    @livewire('profile.update-customer-information-form')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div x-cloak x-show="selectedTab === 'basic'" x-collapse id="tabpanelGroups" role="tabpanel" aria-label="basic">
                        @livewire('profile.claim-rating.confirm-claim-rating')
                        @livewire('profile.claim-rating.claim-rating-list')
                    </div>
                    <div x-cloak x-show="selectedTab === 'settings'" x-collapse id="tabpanelGroups" role="tabpanel" aria-label="settings">
                        <div class="mt-10 sm:mt-0">
                            @livewire('profile.edit-privacy-settings')
                        </div>
                    </div>
                    <div x-cloak x-show="selectedTab === 'security'" x-collapse id="tabpanelGroups" role="tabpanel" aria-label="security">
                        <div class="mt-10 sm:mt-0">
                              <div class="antialiased" wire:loading.class="cursor-wait">
                              <div class=" sm:pl-6 lg:pl-8">
                              @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                                        <div class="mt-10 sm:mt-0">
                                            @livewire('profile.update-password-form')
                                        </div>
                                        <x-section-border />
                                    @endif
                                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                                        <div class="mt-10 sm:mt-0">
                                            @livewire('profile.two-factor-authentication-form')
                                        </div>
                                        <x-section-border />
                                    @endif
                                    <div class="mt-10 sm:mt-0">
                                        @livewire('profile.logout-other-browser-sessions-form')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>

        </div>
    </div>      
</div>
