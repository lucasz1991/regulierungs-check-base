<div class="bg-white rounded-lg shadow-xl  border  border-gray-300 h-full overflow-hidden" >
    <div class=" p-4 pb-0">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <x-user.public-info :user="$rating->user" context="rating" />
                <div class="text-sm text-gray-500 pl-1">
                      - {{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}
                </div>
            </div>
            <x-insurance.insurance-rating-stars :score="$rating->rating_score" />
        </div>
    </div>
    <div class=" p-4 border-b border-gray-300 grayscale-75 hover:grayscale-0 transition-all duration-200">
        @if(!str_contains(request()->path(), 'insurance/'))
            <div class="opacity-70 hover:opacity-100  transition-all duration-200 cursor-pointer mb-3">
                <x-insurance.insurance-name-button :insurance="$rating->insurance" />

            </div>
        @endif
        @if(!str_contains(request()->path(), 'insurancetype/'))
            <div class="">
                <a href="/insurancetype/{{ $rating->insuranceSubType->slug }}"  class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded  border border-gray-500 w-max opacity-60 hover:opacity-100 transition-opacity duration-200 cursor-pointer">
                    {{ strlen($rating->insuranceSubType->name) > 25 ? substr($rating->insuranceSubType->name, 0, 25) . '...' : $rating->insuranceSubType->name }}
                </a>
            </div>
        @endif
    </div>
    <div class="p-4">
        <div class="mt-2 text-gray-800 max-h-16">
        {{ strlen($rating->attachments['scorings']['ai_overall_comment']) > 100 ? substr($rating->attachments['scorings']['ai_overall_comment'], 0, 100) . '...' : $rating->attachments['scorings']['ai_overall_comment'] }}
        </div>
    </div>
    <div class=" p-4 flex justify-end">
        <a  href="{{ route('review.show', $rating->id) }}" class="text-blue-800 bg-gray-100 hover:bg-blue-800 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 text-center inline-flex items-center ">
            ansehen
            <svg class="rtl:rotate-180 w-2.5 h-2.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
             <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>

    </div>
</div>