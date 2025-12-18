<div class="lg:flex gap-4">
    <div class="relative w-full lg:w-2/3">

        <div class="prose max-w-full">
            <h2 class="text-lg font-semibold mb-2 flex items-center gap-2">
                <i class="fal fa-comment-alt text-blue-600"></i>
                <span>Kommentar</span>
            </h2>

            <p class="text-gray-700 leading-relaxed">
                {{ $detailInsuranceRating->ai_comment ?: 'Kein Kommentar vorhanden.' }}
            </p>
        </div>

    </div>

    <div class="bg-white/95 border border-white/10 shadow rounded-2xl w-full lg:w-1/3 p-5">
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
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm text-gray-700 inline-flex items-center gap-2">
                        <i class="fal fa-clock text-gray-400"></i>
                        <span>Dauer</span>
                    </span>
                    <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->speed" :size="'md'" />
                </div>
            </div>

            <div label="Kundenservice">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm text-gray-700 inline-flex items-center gap-2">
                        <i class="fal fa-headset text-gray-400"></i>
                        <span>Kundenservice</span>
                    </span>
                    <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->communication" :size="'md'" />
                </div>
            </div>

            <div label="Fairness">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm text-gray-700 inline-flex items-center gap-2">
                        <i class="fal fa-balance-scale text-gray-400"></i>
                        <span>Fairness</span>
                    </span>
                    <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->fairness" :size="'md'" />
                </div>
            </div>

            <div label="Transparency">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm text-gray-700 inline-flex items-center gap-2">
                        <i class="fal fa-eye text-gray-400"></i>
                        <span>Transparenz</span>
                    </span>
                    <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->transparency" :size="'md'" />
                </div>
            </div>
        </div>
    </div>
</div>
