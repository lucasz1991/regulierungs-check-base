
    <x-app-layout>
    @section('title')
        {{ __('Profil') }}
    @endsection
    <div class=" py-3 ">

        <div class="container mx-auto px-5" >
                <div x-data="{ selectedTab: 'basic' }" class="w-full">
                    <div x-on:keydown.right.prevent="$focus.wrap().next()" x-on:keydown.left.prevent="$focus.wrap().previous()" class="flex gap-2 overflow-x-auto border-b border-outline dark:border-outline-dark" role="tablist" aria-label="tab options">
                        <button x-on:click="selectedTab = 'basic'" x-bind:aria-selected="selectedTab === 'basic'" x-bind:tabindex="selectedTab === 'basic' ? '0' : '-1'" x-bind:class="selectedTab === 'basic' ? 'bg-white rounded-t-lg shadow font-bold text-primary border-b-2 border-secondary dark:border-primary-dark dark:text-primary-dark' : 'text-on-surface font-medium dark:text-on-surface-dark dark:hover:border-b-outline-dark-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelBasic" >Profil</button>
                        <button x-on:click="selectedTab = 'abos'" x-bind:aria-selected="selectedTab === 'abos'" x-bind:tabindex="selectedTab === 'abos' ? '0' : '-1'" x-bind:class="selectedTab === 'abos' ? 'bg-white rounded-t-lg font-bold text-primary border-b-2 border-secondary dark:border-primary-dark dark:text-primary-dark' : 'text-on-surface font-medium dark:text-on-surface-dark dark:hover:border-b-outline-dark-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelAbos" >Privatsphäre</button>
                        <button x-on:click="selectedTab = 'verification'" x-bind:aria-selected="selectedTab === 'verification'" x-bind:tabindex="selectedTab === 'verification' ? '0' : '-1'" x-bind:class="selectedTab === 'verification' ? 'bg-white rounded-t-lg font-bold text-primary border-b-2 border-secondary dark:border-primary-dark dark:text-primary-dark' : 'text-on-surface font-medium dark:text-on-surface-dark dark:hover:border-b-outline-dark-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelVerification" >Sicherheit</button>
                    </div>
                    <div class=" bg-white px-2 py-20 text-on-surface dark:text-on-surface-dark rounded-b rounded-se">
                        <div x-cloak x-show="selectedTab === 'basic'" id="tabpanelGroups" role="tabpanel" aria-label="basic">
                            <div class="antialiased" wire:loading.class="cursor-wait">
                                <div class="py-2 sm:px-6 lg:px-8">
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
                        <div x-cloak x-show="selectedTab === 'abos'" id="tabpanelLikes" role="tabpanel" aria-label="likes">
                            <div class="antialiased" wire:loading.class="cursor-wait">
                                <div class="py-2 sm:px-6 lg:px-8">  
                                    <div class="mt-10 sm:mt-0">
                                        @livewire('profile.edit-privacy-settings')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div x-cloak x-show="selectedTab === 'verification'" id="tabpanelComments" role="tabpanel" aria-label="verification">
                            <div class="antialiased" wire:loading.class="cursor-wait">
                                <div class="py-2 sm:px-6 lg:px-8">
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
                </div>
        </div>  
    </div>
    </x-app-layout>

