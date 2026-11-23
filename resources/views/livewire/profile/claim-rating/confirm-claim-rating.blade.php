<div  @if($claimRating != null && ($claimRating->status == 'rating' || $claimRating->status == 'pending')) wire:poll.3s @endif>
    <x-dialog-modal wire:model="openModal" :maxWidth="'4xl'">
        <x-slot name="title">
            <h2 class="text-xl font-semibold mb-4 text-[var(--ink)]">Bewertung veröffentlichen</h2>
            <x-alert class="mb-4 w-full md:w-full">
                <p class="text-[var(--ink)] mb-2 text-sm">Bitte überprüfe die Analyse deiner Bewertung, bevor du sie veröffentlichst.</p>
                <p class="text-[var(--ink)] mb-2 text-sm">Die Analyse kann neu durchgeführt werden, wenn du mit ihr unzufrieden bist.</p>
                <p class="text-[var(--ink)] mb-2 text-sm">Möchtest du deine Bewertung öffentlich machen?</p>
            </x-alert>
        </x-slot>
        <x-slot name="content">
            @if($claimRating && data_get($claimRating->attachments, 'scorings') != null && $claimRating->status == 'rated')
                <div class="rounded-2xl border border-[var(--line)] bg-[var(--surface-soft)] p-3 md:p-6">
                    <h3 class="text-lg font-semibold text-[var(--ink)] mb-4">
                        Auswertung
                    </h3>
                
                    <div class="xl:flex gap-4">
                        <div class="rounded-xl border border-[var(--line)] bg-[var(--surface)] p-4 w-full xl:w-2/3">
                            <div class="text-sm text-[var(--muted)] mb-4">
                                <strong class="my-4 text-[var(--ink)]">Gesamt-Scoring:</strong>
                                <x-insurance.insurance-rating-stars :score="$claimRating->score()" />
                            </div>
                            <div class="max-w-full">
                                <h4 class="text-base font-semibold mb-2 text-[var(--ink)]">Kommentar</h4>
                                <p class="text-sm leading-7 text-[var(--ink)]">{{ $claimRating->comment() ?: 'Kein Kommentar vorhanden.' }}</p>
                            </div>
                        </div>    
                        <div class="mt-4 xl:mt-0 rounded-xl border border-[var(--line)] bg-[var(--surface)] p-4 w-full xl:w-1/3 space-y-2 text-[var(--ink)]">
                            <strong class="block mb-2">Scorings:</strong>
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
                            <div class="mt-4">
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
                <div class="rounded-2xl border border-[var(--line)] bg-[var(--surface-soft)] p-6 flex items-center justify-center h-20">
                    <div class="w-6 h-6 border-4 border-[var(--line)] border-t-[var(--accent)] rounded-full animate-spin"></div>
                    <span class="ml-3 text-[var(--ink)] text-sm">Analyse läuft …</span>
                </div>
            @endif
        </x-slot>
        <x-slot name="footer">
                <div class="sm:space-x-2 space-y-2">
                    <x-secondary-button wire:click="reanalyze" class="max-sm:w-full text-sm" wire:loading.attr="disabled" wire:target="reanalyze">
                        Erneut analysieren
                    </x-secondary-button>
                    <x-secondary-button wire:click="$set('openModal', false)" class="max-sm:w-full text-sm">Abbrechen</x-secondary-button>
                    <x-button wire:click="confirm" class="max-sm:w-full text-sm">Veröffentlichen</x-button>
                </div>
        </x-slot>
    </x-dialog-modal>
</div>

    