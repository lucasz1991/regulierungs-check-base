<div class="bg-white rounded-lg shadow-xl  border  border-gray-300 h-full" >
    <div class=" p-4 pb-0">
        <div class="flex justify-between items-start">
            <div class="">
                <x-user.public-info :user="$rating->user" context="ratings" />
                <div class="text-sm text-gray-500 mt-1">
                      {{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}
                </div>
            </div>
            <x-insurance.insurance-rating-stars :score="$rating->rating_score" />
        </div>
    </div>
    <div class=" p-4 border-b border-gray-300 ">
        @if(!str_contains(request()->path(), 'insurance/'))
            <div class=" cursor-pointer mb-3">
                <x-insurance.insurance-name-button :insurance="$rating->insurance" />
            </div>
        @endif
        @if(!str_contains(request()->path(), 'insurancetype/'))
            <div class="">
                @php
                    $bgColor = $rating->insuranceSubType->style['bg_color'] ?? '#f3f4f6';
                    $fontColor = $rating->insuranceSubType->style['font_color'] ?? '#4d4d4d';
                    $borderColor = $rating->insuranceSubType->style['border_color'] ?? '#4d4d4d';
                @endphp
                <a href="/insurancetype/{{ $rating->insuranceSubType->slug }}"  class=" text-xs font-medium me-2 px-2.5 py-0.5 rounded  border  w-max opacity-80 hover:opacity-100 transition-opacity duration-200 cursor-pointer" style="background-color: {{ $bgColor }}; color: {{ $fontColor }}; border-color: {{ $borderColor }};" title="{{ $rating->insuranceSubType->name }}">
                    {{ strlen($rating->insuranceSubType->name) > 25 ? substr($rating->insuranceSubType->name, 0, 25) . '...' : $rating->insuranceSubType->name }}
                </a>
            </div>
        @endif
    </div>
 
    <div class=" p-4 flex justify-end">
        <a  href="{{ route('review.show', $rating->id) }}" class="text-blue-800 bg-gray-100 hover:bg-blue-800 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 text-center inline-flex items-center ">
            ansehen&nbsp;
            <svg class="rtl:rotate-180 w-2.5 h-2.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
             <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>
    </div>
</div>