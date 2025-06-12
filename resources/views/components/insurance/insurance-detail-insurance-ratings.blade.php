<div class="max-w-md">
    <div label="Regulations Dauer">
        <div class="flex items-center justify-between space-x-2">
            <span class="mr-4">Dauer:</span>
            <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->speed" />
        </div>
    </div>
    <div label="Kundenservice">
        <div class="flex items-center justify-between space-x-2">
        <span class="mr-4">Kundenservice:</span>
            <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->communication" />
        </div>
    </div>
    <div label="Fairness">
        <div class="flex items-center justify-between space-x-2">
        <span class="mr-4">Fairness:</span>
            <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->fairness" />
        </div>
    </div>
    <div label="Transparency">
        <div class="flex items-center justify-between space-x-2">
        <span class="mr-4">Transparenz:</span>
            <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->transparency" />
        </div>
    </div>
</div>