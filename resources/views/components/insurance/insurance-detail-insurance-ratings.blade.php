<div class="lg:flex gap-4 mt-12">
    <div class="bg-primary-50 p-4 rounded w-full lg:w-2/3">
        <div class="text-sm text-gray-600 mb-4">
            <strong class="my-4">Gesammt Scoring:</strong> 
            <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->total_score"  :size="'lg'"/>
        </div>
        <div class="prose max-w-full">
            <h2 class="text-lg font-semibold mb-2">Kommentar</h2>
            <p>{{ $detailInsuranceRating->ai_comment ?: 'Kein Kommentar vorhanden.' }}</p>
        </div>
    </div>
    <div class="bg-white p-4 rounded w-full lg:w-1/3  items-center">
        <div>
            <div class="shrink-0 transition-all relative self-auto" >
                <div  class="">
                    </div>
                    <div class="mt-5">
                        <div class="text-sm text-gray-500 font-medium text-center mb-3">
                            <div class="w-16 mx-auto text-xs text-white p-2 aspect-square bg-[#223d65]  rounded-full flex justify-center items-center"><span>Ø: {{ round($insurance->avgRatingDurationBySubtype()) }}<br> Tage</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div label="Regulations Dauer">
            <div class="flex items-center justify-between space-x-2">
                <span class="mr-4">Dauer:</span>
                <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->speed" :size="'md'" />
            </div>
        </div>
        <div label="Kundenservice">
            <div class="flex items-center justify-between space-x-2">
            <span class="mr-4">Kundenservice:</span>
                <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->communication" :size="'md'" />
            </div>
        </div>
        <div label="Fairness">
            <div class="flex items-center justify-between space-x-2">
            <span class="mr-4">Fairness:</span>
                <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->fairness" :size="'md'" />
            </div>
        </div>
        <div label="Transparency">
            <div class="flex items-center justify-between space-x-2">
            <span class="mr-4">Transparenz:</span>
                <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->transparency" :size="'md'" />
            </div>
        </div>
    </div>
</div>