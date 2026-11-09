@props([
    'detailInsuranceRating',
    'insurance',
    'autoSummaryItems' => [],
])

<div class="space-y-4">
    <div class="prose max-w-full">
        <h2 class="text-lg font-semibold mb-2 flex items-center gap-2">
            <i class="fal fa-comment-alt text-[var(--accent)]"></i>
            <span>Zusammenfassung</span>
        </h2>

        <x-ui.read-more-typewriter
            :text="$detailInsuranceRating->ai_comment ?: 'Kein Kommentar vorhanden.'"
            limitPx="200"
            speed="1"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:items-stretch">
        <div class="bg-[var(--surface)] border border-[var(--line)] shadow rounded-2xl p-4 h-full flex flex-col">
            <h3 class="text-sm font-semibold text-[var(--ink)] mb-2.5">Kompakte Auswertung</h3>

            <ul class="space-y-2 flex-1">
                @foreach ($autoSummaryItems as $item)
                    <li class="flex items-start gap-2.5  px-2 py-1.5">
                        <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-teal-600 text-white">
                            <i class="fal {{ $item['icon'] ?? 'fa-check' }} text-[10px]"></i>
                        </span>
                        <span class="text-[13px] text-[var(--ink)] leading-snug">
                            {{ $item['text'] ?? '' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-[var(--surface)] border border-[var(--line)] shadow rounded-2xl p-4 h-full flex flex-col">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-[var(--ink)] flex items-center gap-2">
                    <i class="fal fa-chart-bar text-[var(--accent)]"></i>
                    <span>Scorings</span>
                </h3>
                <span class="text-xs text-[var(--muted)] rounded-full bg-[var(--surface-soft)] px-2 py-0.5">
                    Ø / 5
                </span>
            </div>

            <div class="flex-1 flex flex-col justify-between gap-2.5">
                <div class="rounded-lg border border-[var(--line)] bg-[var(--surface-soft)] px-2 py-1.5" label="Regulations Dauer">
                    <div class="flex items-center flex-wrap justify-between gap-3">
                        <span class="text-sm text-[var(--ink)] inline-flex items-center gap-2">
                            <i class="fal fa-clock text-[var(--muted)]"></i>
                            <span>Dauer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </span>
                        <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->speed" :size="'md'" />
                    </div>
                </div>

                <div class="rounded-lg border border-[var(--line)] bg-[var(--surface-soft)] px-2 py-1.5" label="Kundenservice">
                    <div class="flex items-center flex-wrap justify-between gap-3">
                        <span class="text-sm text-[var(--ink)] inline-flex items-center gap-2">
                            <i class="fal fa-headset text-[var(--muted)]"></i>
                            <span>Kundenservice</span>
                        </span>
                        <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->communication" :size="'md'" />
                    </div>
                </div>

                <div class="rounded-lg border border-[var(--line)] bg-[var(--surface-soft)] px-2 py-1.5" label="Fairness">
                    <div class="flex items-center flex-wrap justify-between gap-3">
                        <span class="text-sm text-[var(--ink)] inline-flex items-center gap-2">
                            <i class="fal fa-balance-scale text-[var(--muted)]"></i>
                            <span>Fairness&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </span>
                        <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->fairness" :size="'md'" />
                    </div>
                </div>

                <div class="rounded-lg border border-[var(--line)] bg-[var(--surface-soft)] px-2 py-1.5" label="Transparency">
                    <div class="flex items-center flex-wrap justify-between gap-3">
                        <span class="text-sm text-[var(--ink)] inline-flex items-center gap-2">
                            <i class="fal fa-eye text-[var(--muted)]"></i>
                            <span>Transparenz&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </span>
                        <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->transparency" :size="'md'" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
