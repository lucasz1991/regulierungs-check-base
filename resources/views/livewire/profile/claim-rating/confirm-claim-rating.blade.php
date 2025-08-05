<div>
    <x-dialog-modal wire:model="openModal">
        <x-slot name="title">
            <h2 class="text-xl font-semibold mb-4">Bewertung veröffentlichen</h2>
            <p class="text-gray-700 mb-2">Bitte überprüfe die Analyse deiner Bewertung, bevor du sie veröffentlichst.</p>
            <p class="text-gray-700 mb-2">Möchtest du deine Bewertung öffentlich machen?</p>
        </x-slot>
        <x-slot name="content">
            @if($claimRating && $claimRating->attachments['scorings'] != null)
                <div class="bg-gray-200 rounded shadow p-6">
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
                                    <span class="mr-4">Dauer:</span>
                                    <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['regulation_speed']" />
                                </div>
                            </div>
                            <div label="Kundenservice">
                                <div class="flex items-center justify-between space-x-2">
                                <span class="mr-4">Kundenservice:</span>
                                    <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['customer_service']" />
                                </div>
                            </div>
                            <div label="Fairness">
                                <div class="flex items-center justify-between space-x-2">
                                <span class="mr-4">Fairness:</span>
                                    <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['fairness']" />
                                </div>
                            </div>
                            <div label="Transparency">
                                <div class="flex items-center justify-between space-x-2">
                                <span class="mr-4">Transparenz:</span>
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
            @endif
        </x-slot>
        <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('openModal', false)" class="bg-gray-200 px-4 py-2 rounded">Abbrechen</button>
                    <button wire:click="confirm" class="bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700">Veröffentlichen</button>
                </div>
        </x-slot>
    </x-dialog-modal>
</div>

    