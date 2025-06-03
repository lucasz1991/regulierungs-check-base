<div class="bg-white rounded-lg shadow-xl  border  border-gray-300 h-full overflow-hidden" >
    <div class=" p-4 border-b border-gray-300 grayscale-75 hover:grayscale-0 transition-all duration-200">
        @if(!str_contains(request()->path(), 'insurance/'))
            <div class="opacity-70 hover:opacity-100  transition-all duration-200 cursor-pointer mb-3">
                <div  class="flex gap-2 overflow-hidden">
                    <div class="flex-none shrink-0">
                        <a href="{{ route('insurance.show-insurance', $rating->insurance->slug) }}"  class=" w-min rounded flex items-center justify-center text-base border px-2 font-medium" style="background-color: {{ $rating->insurance->style['bg_color'] ?? '#eee' }}; color: {{ $rating->insurance->style['font_color'] ?? '#333' }}; border-color: {{ $rating->insurance->style['border_color'] ?? '#ccc' }};">
                            {{ strtoupper(substr( $rating->insurance->initials, 0 ,8)) }}
                        </a>
                    </div>
                    <div class="grow">
                        <a href="{{ route('insurance.show-insurance', $rating->insurance->slug) }}" >
                        <h2 class="text-base break-words  truncate text-ellipsis">
                            {{ strlen($rating->insurance->name) > 20 ? substr($rating->insurance->name, 0, 20) . '...' : $rating->insurance->name }}
                        </h2>
                        </a>
                    </div>
                </div>
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
        <div class="flex justify-between items-center">
            <x-insurance.insurance-rating-stars :score="$rating->rating_score" />
            <div class="text-sm text-gray-500">
                 {{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}
            </div>
        </div>
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