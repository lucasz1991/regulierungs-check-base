<div  @if($hasActiveRating) wire:poll.3s @endif  class="w-full relative bg-cover bg-center bg-gray-100 pb-20 pt-8" wire:loading.class="cursor-wait">
    <div class="container mx-auto px-5">
        <div x-data="{ selectedTab: 'basic' }" class="w-full">
            <div x-on:keydown.right.prevent="$focus.wrap().next()" x-on:keydown.left.prevent="$focus.wrap().previous()" class="flex gap-2 overflow-x-auto border-b border-outline " role="tablist" aria-label="tab options">
                <button x-on:click="selectedTab = 'basic'" x-bind:aria-selected="selectedTab === 'basic'" x-bind:tabindex="selectedTab === 'basic' ? '0' : '-1'" x-bind:class="selectedTab === 'basic' ? 'bg-white rounded-t-lg shadow font-bold text-primary border-b-2 border-secondary ' : 'text-on-surface font-medium  hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelBasic" >Allgemein</button>
                <button x-on:click="selectedTab = 'verification'" x-bind:aria-selected="selectedTab === 'verification'" x-bind:tabindex="selectedTab === 'verification' ? '0' : '-1'" x-bind:class="selectedTab === 'verification' ? 'bg-white rounded-t-lg font-bold text-primary border-b-2 border-secondary ' : 'text-on-surface font-medium hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelVerification" >Verifiziert</button>
            </div>
            <div class="px-2 py-20 text-on-surface dark:text-on-surface-dark">
                <div x-cloak x-show="selectedTab === 'basic'" id="tabpanelGroups" role="tabpanel" aria-label="basic">
                    <div class="mr-auto font-semibold text-2xl place-self-center">
                        <h1 class="max-w-2xl mb-4 font-bold tracking-tight leading-none text-2xl xl:text-3xl">
                            Willkommen {{ $userData->name }},
                        </h1>
                        <p class="max-w-2xl mb-6 text-gray-500 md:text-lg lg:text-xl">
                            Deine Bewertungen und dein Versicherungsprofil im Überblick
                        </p>
                    </div>
                    @if(!auth()->user()->email_verified_at)
                        <x-alert class="mb-6">
                            <h6 class="text-xl font-semibold  mb-1" >E-Mail Verifizierung</h6>
                            <p>Um deine Bewertungen öffentlich sichtbar zu machen, musst du zuerst deine E-Mail-Adresse verifizieren.</p>
                        </x-alert>
                    @endif
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                        <div class="bg-white shadow-lg rounded-lg p-5">
                            <h2 class="text-lg font-semibold text-gray-700">Abgegebene Bewertungen</h2>
                            <p class="text-3xl font-bold text-gray-500">{{ $ratingsCount }}</p>
                        </div>
                        <div class="bg-white shadow-lg rounded-lg p-5">
                            <h2 class="text-lg font-semibold text-gray-700">Öffentliche Bewertungen</h2>
                            <p class="text-3xl font-bold text-green-600">{{ $verifiedRatingsCount }}</p>
                        </div>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Deine letzten Bewertungen</h2>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse ($claimRatings as $rating)
                            <x-profile.claim-rating.claim-rating-card :rating="$rating" />
                        @empty
                            <x-alert>
                                Du hast noch keine Bewertungen abgegeben.
                            </x-alert>
                        @endforelse
                    </div>
                    @if ($claimRatings->hasPages())
                        <div class="mt-6">
                            {{ $claimRatings->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>
                <div x-cloak x-show="selectedTab === 'verification'" id="tabpanelComments" role="tabpanel" aria-label="verification">
                    <x-alert>
                        <h6 class="text-xl font-semibold  mb-1" >Verifizierungen</h6>
                        Die Verifizierungsübersicht wird hier in Kürze verfügbar sein. Wir arbeiten daran, dir eine transparente Darstellung deiner Verifizierungen bereitzustellen. Vielen Dank für deine Geduld!
                    </x-alert>
                </div>
            </div>
        </div>
    </div>      
</div>
