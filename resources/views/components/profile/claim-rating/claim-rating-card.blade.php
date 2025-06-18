<div class="bg-white rounded-lg shadow-xl  border  border-gray-300 h-full overflow-hidden" >
    <div class=" p-4 py-3">
        <div class="flex justify-between ">
            <div class="">
                <div class="text-sm text-gray-500">
                    Erstellt am  - {{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}
                </div>
            </div>

            <div class="flex justify-between items-start">
                <div class="flex mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-1 opacity-40 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <g>
                        <!-- Schild (Shield) -->
                        <path stroke="currentColor" stroke-width="1.5" fill="#f3f4f6" d="M12 3l7 3v5c0 5.25-3.5 9.75-7 11-3.5-1.25-7-5.75-7-11V6l7-3z"/>
                        <!-- Auge (Eye) -->
                        <ellipse cx="12" cy="11" rx="3" ry="2" fill="#fff" stroke="currentColor" stroke-width="1"/>
                        <circle cx="12" cy="11" r="0.8" fill="#1f2937"/>
                        <path d="M9.5 11c.5-.7 1.5-1.2 2.5-1.2s2 .5 2.5 1.2" stroke="#6b7280" stroke-width="0.7" fill="none"/>
                    </g>
                </svg>
                <div class="w-6">
    
                    <x-profile.claim-rating.claim-rating-circleprogress/>
                </div>
            </div>
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
    <div class=" p-4  grayscale-75 hover:grayscale-0 transition-all duration-200">
        <div class=" mb-3  opacity-80">
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