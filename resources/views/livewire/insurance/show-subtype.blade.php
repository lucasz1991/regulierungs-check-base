<div class="min-h-screen">

    {{-- HERO / ÜBERBLICK (dark background friendly) --}}
    <div class="container mx-auto px-4 pb-8 pt-12">
        <div class="">

            {{-- Title row --}}
            <div class="flex items-start gap-4">

                {{-- Icon Badge (links) --}}
                <div class="shrink-0">
                    <div class="h-12 w-12 rounded-2xl bg-white/10 border border-white/15 shadow-lg backdrop-blur flex items-center justify-center">
                        <i class="fal fa-shield-alt text-white/90 text-xl"></i>
                    </div>
                </div>

                {{-- Title + Description --}}
                <div class="min-w-0">
                    <h1 class="text-xl md:text-4xl font-semibold text-white leading-tight">
                        {{ $insuranceSubtype->name }}
                    </h1>
                </div>
            </div>

            {{-- Info / Notice (nur wenn keine Auswertung) --}}
            @if($insuranceSubtype->published_ratings_count() === 0)
                <div class="
                    rounded-2xl
                    bg-white/10
                    backdrop-blur
                    border border-white/15
                    p-5
                    flex items-start gap-4
                    shadow-lg
                ">
                    {{-- Icon --}}
                    <div class="
                        shrink-0
                        h-10 w-10
                        rounded-xl
                        bg-amber-400/20
                        text-amber-300
                        flex items-center justify-center
                    ">
                        <i class="fal fa-info-circle text-lg"></i>
                    </div>

                    {{-- Content --}}
                    <div class="min-w-0">
                        <h3 class="font-semibold text-base text-white mb-1">
                            Noch keine detaillierte Auswertung
                        </h3>

                        <p class="text-sm text-blue-100/80 leading-relaxed">
                            Für diese Versicherungsart liegen aktuell noch keine ausreichend bewerteten Fälle vor.
                            Sobald erste Bewertungen eingegangen sind, wird hier automatisch eine Auswertung angezeigt.
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>


    {{-- BEWERTUNGEN --}}
    <div class="">
        <div class="container mx-auto px-4  pb-10">

            @if($insuranceSubtype->published_ratings_count() > 0)

                {{-- Section Title (wie Vorlage) --}}
                <h2 class="flex items-center justify-center text-lg px-2 py-1 w-max mb-5">
                    <span class="w-max text-white">Bewertungen</span>
                    <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full h-7 leading-none">
                        {{ $insuranceSubtype->published_ratings_count() }}
                    </span>
                </h2>

                <x-filter.filter-container>
                    <x-slot name="filters">
                        <div class="p-2">
                            <x-filter.filter-search-field wire:model.live="search" :label="'Suche'"/>
                        </div>
                    </x-slot>

                    <x-slot name="listContent">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($claimRatings as $claim_rating)
                                <x-claim-rating.claim-rating-card :rating="$claim_rating" />
                            @endforeach
                        </div>
                    </x-slot>
                </x-filter.filter-container>

            @else
                {{-- Optional: falls du trotzdem unten noch einen Hinweis willst (dark) --}}
                <div class="container mx-auto px-0">
                    <div class="rounded-2xl bg-white/10 border border-white/15 backdrop-blur p-5 text-blue-100/80">
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-white">
                                <i class="fal fa-comment-alt-lines"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Noch keine Bewertungen vorhanden</p>
                                <p class="text-sm text-blue-100/80 mt-1">
                                    Sobald erste Bewertungen eingehen, erscheinen sie hier automatisch.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
