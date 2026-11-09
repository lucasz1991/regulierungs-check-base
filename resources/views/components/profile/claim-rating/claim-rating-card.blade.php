<article class="app-account app-private-rating rounded-[18px] border border-[var(--line)] bg-[var(--surface)] h-full" >
    <div class="p-5 pb-4">
        <div class="flex flex-wrap justify-between gap-3">
            <div class="">
                <div class="text-xs text-[var(--muted)]">
                    Erstellt am  - {{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}
                </div>
            </div>

            <div class="flex justify-between items-start">
                    <livewire:profile.claim-rating.verification-status-modal
                        :claim-rating="$rating"
                        wire:key="verification-status-{{ $rating->id }}"
                        lazy
                    />                    <div class="mr-3">
                    @if($rating->is_public)
                        <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-semibold">Öffentlich</span>
                    @else
                        <span class="ml-2 bg-[var(--surface-soft)] text-[var(--muted)] text-xs px-2 py-1 rounded font-semibold opacity-60">Privat</span>
                    @endif
                </div>
                <x-dropdown>
                    <x-slot name="trigger">
                        <button type="button" class="flex h-10 w-10 items-center justify-center rounded-full text-[var(--muted)] hover:bg-[var(--surface-soft)] hover:text-[var(--ink)]" aria-label="Bewertungsaktionen">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        @if($rating->status != 'pending')
                            <x-dropdown-link href="{{ route('profile.claim-rating.claim-rating-show', ['claimRating' => $rating->id]) }}">
                                Details ansehen
                            </x-dropdown-link>
                        @endif
                        <x-dropdown-link wire:click="deleteRating({{ $rating->id }})" wire:confirm="Möchten Sie diesen Eintrag wirklich löschen?">
                            Löschen
                        </x-dropdown-link>
                    </x-slot>   
                </x-dropdown>
            </div>
        </div>
    </div>
    <hr class="mx-5 border-t border-[var(--line)]">
    <div class="p-5">
        <div class=" mb-3 ">
            <x-insurance.insurance-name :insurance="$rating->insurance" />
        </div>
        <div class="">
            <div class="inline-flex max-w-full rounded-full bg-[var(--surface-soft)] px-3 py-1 text-xs font-medium text-[var(--muted)]">
                {{ strlen($rating->insuranceSubType->name) > 25 ? substr($rating->insuranceSubType->name, 0, 25) . '...' : $rating->insuranceSubType->name }}
            </div>
        </div>
    </div>
    <hr class="mx-5 border-t border-[var(--line)]">
    <div class="p-5">
        @switch($rating->status)
            @case('pending')
                <span class="text-yellow-600 font-medium">Prüfung steht bevor</span>
                @break
            @case('rating')
                <span class="text-yellow-600 font-medium">In Prüfung</span>
                @break
            @default
                <x-insurance.insurance-rating-stars :score="$rating->rating_score" />
                <div class="mt-3 text-sm leading-7 text-[var(--ink)]">
                    {{ strlen($rating->attachments['scorings']['ai_overall_comment']) > 80 ? substr($rating->attachments['scorings']['ai_overall_comment'], 0, 80) . '...' : $rating->attachments['scorings']['ai_overall_comment'] }}
                </div>
        @endswitch
    </div>
</article>
