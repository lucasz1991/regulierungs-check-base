@props([
    'detailInsuranceRating',
    'insurance',
    'autoSummaryItems' => [],
])

<div class="space-y-4">
    <div class="prose max-w-full">
        <h2 class="text-lg font-semibold mb-2 flex items-center gap-2">
            <i class="fal fa-comment-alt text-blue-600"></i>
            <span>Zusammenfassung</span>
        </h2>

        <x-ui.read-more-typewriter
            :text="$detailInsuranceRating->ai_comment ?: 'Kein Kommentar vorhanden.'"
            limitPx="200"
            speed="1"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white/95 border border-white/10 shadow rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-slate-800 mb-3">Kompakte Auswertung</h3>

            <ul class="space-y-2">
                @foreach ($autoSummaryItems as $item)
                    <li class="flex items-start gap-2 text-sm text-slate-700 leading-relaxed">
                        <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-teal-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.414l-7.5 7.5a1 1 0 01-1.414 0l-3.5-3.5A1 1 0 015.704 9.29l2.793 2.793 6.793-6.793a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span>{{ $item }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white/95 border border-white/10 shadow rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fal fa-chart-bar text-blue-600"></i>
                    <span>Scorings</span>
                </h3>
                <span class="text-xs text-gray-500 rounded-full bg-gray-100 px-2 py-1">
                    Ø / 5
                </span>
            </div>

            <div class="space-y-3">
                <div label="Regulations Dauer">
                    <div class="flex items-center flex-wrap justify-between gap-3">
                        <span class="text-sm text-gray-700 inline-flex items-center gap-2">
                            <i class="fal fa-clock text-gray-400"></i>
                            <span>Dauer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </span>
                        <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->speed" :size="'md'" />
                    </div>
                </div>

                <div label="Kundenservice">
                    <div class="flex items-center flex-wrap justify-between gap-3">
                        <span class="text-sm text-gray-700 inline-flex items-center gap-2">
                            <i class="fal fa-headset text-gray-400"></i>
                            <span>Kundenservice</span>
                        </span>
                        <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->communication" :size="'md'" />
                    </div>
                </div>

                <div label="Fairness">
                    <div class="flex items-center flex-wrap justify-between gap-3">
                        <span class="text-sm text-gray-700 inline-flex items-center gap-2">
                            <i class="fal fa-balance-scale text-gray-400"></i>
                            <span>Fairness&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </span>
                        <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->fairness" :size="'md'" />
                    </div>
                </div>

                <div label="Transparency">
                    <div class="flex items-center flex-wrap justify-between gap-3">
                        <span class="text-sm text-gray-700 inline-flex items-center gap-2">
                            <i class="fal fa-eye text-gray-400"></i>
                            <span>Transparenz&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </span>
                        <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->transparency" :size="'md'" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
