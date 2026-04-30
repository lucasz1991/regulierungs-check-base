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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:items-stretch">
        <div class="bg-white/95 border border-white/10 shadow rounded-2xl p-4 h-full flex flex-col">
            <h3 class="text-sm font-semibold text-slate-800 mb-2.5">Kompakte Auswertung</h3>

            <ul class="space-y-2 flex-1">
                @foreach ($autoSummaryItems as $item)
                    <li class="flex items-start gap-2.5  px-2 py-1.5">
                        <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-teal-600 text-white">
                            <i class="fal {{ $item['icon'] ?? 'fa-check' }} text-[10px]"></i>
                        </span>
                        <span class="text-[13px] text-slate-700 leading-snug">
                            {{ $item['text'] ?? '' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white/95 border border-white/10 shadow rounded-2xl p-4 h-full flex flex-col">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fal fa-chart-bar text-blue-600"></i>
                    <span>Scorings</span>
                </h3>
                <span class="text-xs text-gray-500 rounded-full bg-gray-100 px-2 py-0.5">
                    Ø / 5
                </span>
            </div>

            <div class="flex-1 flex flex-col justify-between gap-2.5">
                <div class="rounded-lg border border-slate-100 bg-slate-50/70 px-2 py-1.5" label="Regulations Dauer">
                    <div class="flex items-center flex-wrap justify-between gap-3">
                        <span class="text-sm text-gray-700 inline-flex items-center gap-2">
                            <i class="fal fa-clock text-gray-400"></i>
                            <span>Dauer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </span>
                        <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->speed" :size="'md'" />
                    </div>
                </div>

                <div class="rounded-lg border border-slate-100 bg-slate-50/70 px-2 py-1.5" label="Kundenservice">
                    <div class="flex items-center flex-wrap justify-between gap-3">
                        <span class="text-sm text-gray-700 inline-flex items-center gap-2">
                            <i class="fal fa-headset text-gray-400"></i>
                            <span>Kundenservice</span>
                        </span>
                        <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->communication" :size="'md'" />
                    </div>
                </div>

                <div class="rounded-lg border border-slate-100 bg-slate-50/70 px-2 py-1.5" label="Fairness">
                    <div class="flex items-center flex-wrap justify-between gap-3">
                        <span class="text-sm text-gray-700 inline-flex items-center gap-2">
                            <i class="fal fa-balance-scale text-gray-400"></i>
                            <span>Fairness&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </span>
                        <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->fairness" :size="'md'" />
                    </div>
                </div>

                <div class="rounded-lg border border-slate-100 bg-slate-50/70 px-2 py-1.5" label="Transparency">
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
