<div class="bg-white rounded-lg shadow-xl  border  border-gray-300 h-full" >
    <div class=" p-4 py-3">
        <div class="flex justify-between ">
            <div class="">
                <div class="text-sm text-gray-500">
                    Erstellt am  - {{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}
                </div>
            </div>

            <div class="flex justify-between items-start">
                <x-profile.claim-rating.claim-rating-circleprogress/>
                <div class="mr-3">
                    @if($rating->is_public)
                        <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-semibold">Öffentlich</span>
                    @else
                        <span class="ml-2 bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded font-semibold opacity-60">Privat</span>
                    @endif
                </div>
                <x-dropdown>
                    <x-slot name="trigger">
                        <button class="text-gray-600 hover:text-gray-900 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        @if($rating->status != 'pending')
                            <x-dropdown-link href="{{ route('profile.claim-rating.show', ['claimRating' => $rating->id]) }}">
                                Details ansehen
                            </x-dropdown-link>
                        @endif
                        <x-dropdown-link href="#"  wire:confirm="Möchten Sie diesen Eintrag wirklich löschen?">
                            Löschen
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
    <hr class="max-w-[95%] mx-auto border-t border-gray-200">
    <div class=" p-4  ">
        <div class=" mb-3 ">
            <x-insurance.insurance-name :insurance="$rating->insurance" />
        </div>
        <div class="">
            <div  class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded  border border-gray-500 w-max opacity-60 ">
                {{ strlen($rating->insuranceSubType->name) > 25 ? substr($rating->insuranceSubType->name, 0, 25) . '...' : $rating->insuranceSubType->name }}
            </div>
        </div>
    </div>
    <hr class="max-w-[95%] mx-auto border-t border-gray-200">
    <div class="p-4">
        @switch($rating->status)
            @case('pending')
                <span class="text-yellow-600 font-medium">Prüfung steht bevor</span>
                @break
            @case('rating')
                <span class="text-yellow-600 font-medium">In Prüfung</span>
                @break
            @default
                <x-insurance.insurance-rating-stars :score="$rating->rating_score" />
                <div class="mt-2 text-gray-800 max-h-16">
                    {{ strlen($rating->attachments['scorings']['ai_overall_comment']) > 100 ? substr($rating->attachments['scorings']['ai_overall_comment'], 0, 100) . '...' : $rating->attachments['scorings']['ai_overall_comment'] }}
                </div>
        @endswitch
    </div>
</div>