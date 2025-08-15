<div  @if($claimRating != null && ($claimRating->status == 'rating' || $claimRating->status == 'pending')) wire:poll.3s @endif>
    <x-dialog-modal wire:model="openModal" :maxWidth="'4xl'">
        <x-slot name="title">
            <h2 class="text-xl font-semibold mb-4">Bewertung veröffentlichen</h2>
            <x-alert class="mb-4 w-full md:w-full">
                <p class="text-gray-700 mb-2 text-sm">Bitte überprüfe die Analyse deiner Bewertung, bevor du sie veröffentlichst.</p>
                <p class="text-gray-700 mb-2 text-sm">Die Analyse kann neu durchgeführt werden, wenn du mit ihr unzufrieden bist.</p>
                <p class="text-gray-700 mb-2 text-sm">Möchtest du deine Bewertung öffentlich machen?</p>
            </x-alert>
        </x-slot>
        <x-slot name="content">
            @if($claimRating && $claimRating->attachments['scorings'] != null && $claimRating->status == 'rated')
                <div class="bg-gray-200 rounded shadow p-2 md:p-6">
                    <h2 class="text-xl md:text-xl  text-gray-800 mb-4">
                        Auswertung:
                    </h2>
                
                    <div class="xl:flex gap-4">
                        <div class="bg-primary-50 p-4 rounded w-full xl:w-2/3">
                            <div class="text-sm text-gray-600 mb-4">
                                <strong class="my-4">Gesammt Scoring:</strong> 
                                <x-insurance.insurance-rating-stars :score="$claimRating->score()" />
                            </div>
                            <div class="prose max-w-full">
                                <h2 class="text-lg font-semibold mb-2">Kommentar</h2>
                                <p>{{ $claimRating->comment() ?: 'Kein Kommentar vorhanden.' }}</p>
                            </div>
                        </div>    
                        <div class="bg-white p-4 rounded w-full xl:w-1/3  items-center">
                            <strong class="mb-4">Scorings:</strong> 
                            <div label="Regulations Dauer">
                                <div class="flex items-center justify-between space-x-2">
                                    <span class="mr-4 max-sm:text-sm">Dauer:</span>
                                    <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['regulation_speed']" />
                                </div>
                            </div>
                            <div label="Kundenservice">
                                <div class="flex items-center justify-between space-x-2">
                                <span class="mr-4 max-sm:text-sm">Kundenservice:</span>
                                    <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['customer_service']" />
                                </div>
                            </div>
                            <div label="Fairness">
                                <div class="flex items-center justify-between space-x-2">
                                <span class="mr-4 max-sm:text-sm">Fairness:</span>
                                    <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['fairness']" />
                                </div>
                            </div>
                            <div label="Transparency">
                                <div class="flex items-center justify-between space-x-2">
                                <span class="mr-4 max-sm:text-sm">Transparenz:</span>
                                    <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['transparency']" />
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach ($claimRating->tags() as $tag)
                                        <x-profile.claim-rating.claim-rating-tag-badge :tag="$tag" />
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-200 rounded shadow p-6 flex items-center justify-center h-20">
                    <div class="w-6 h-6 border-4 border-t-transparent border-gray-400 rounded-full animate-spin"></div>
                    <span class="ml-3 text-gray-700 text-sm">Analyse läuft …</span>
                </div>
            @endif
        </x-slot>
        <x-slot name="footer">
                <div class="sm:space-x-2 space-y-2">
                    <x-button  wire:click="reanalyze" class="max-sm:w-full text-sm" wire:loading.attr="disabled" wire:target="reanalyze">
                        Erneut analysieren
                    </x-button>
                    <x-button wire:click="$set('openModal', false)" class="max-sm:w-full text-sm">Abbrechen</x-button>
                    <x-button wire:click="confirm" class="max-sm:w-full text-sm">Veröffentlichen</x-button>
                </div>
        </x-slot>
    </x-dialog-modal>
</div>

    