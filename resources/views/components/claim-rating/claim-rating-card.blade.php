<div class="bg-white p-4 rounded shadow-xl  border  border-gray-200 h-full" >
    @if(!str_contains(request()->path(), 'insurance'))
        <div class="opacity-70 hover:opacity-100 transition-opacity duration-200 cursor-pointer">
            <div class="flex gap-4 overflow-hidden">
                <div class="flex-none shrink-0">
                    <div class=" w-min rounded flex items-center justify-center text-base border px-2 font-medium" style="background-color: {{ $rating->insurance->style['bg_color'] ?? '#eee' }}; color: {{ $rating->insurance->style['font_color'] ?? '#333' }}; border-color: {{ $rating->insurance->style['border_color'] ?? '#ccc' }};">
                        {{ strtoupper(substr( $rating->insurance->initials, 0 ,8)) }}
                    </div>
                </div>
                <div class="grow">
                    <h2 class="text-base break-words  truncate text-ellipsis">
                        {{ strlen($rating->insurance->name) > 25 ? substr($rating->insurance->name, 0, 25) . '...' : $rating->insurance->name }}
                    </h2>
                </div>
            </div>
        </div>
    @endif
    <div class="mt-3">
        <div class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded  border border-gray-500 w-max opacity-60 hover:opacity-100 transition-opacity duration-200 cursor-pointer">
            {{ strlen($rating->insuranceSubType->name) > 25 ? substr($rating->insuranceSubType->name, 0, 25) . '...' : $rating->insuranceSubType->name }}
        </div>
    </div>
    <hr class="my-4" />
    <div class="flex justify-between items-center">
        <x-insurance.insurance-rating-stars :score="$rating->rating_score" />
        <div class="text-sm text-gray-500">
             {{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}
        </div>
    </div>
    <div class="mt-2 text-gray-800 max-h-16 truncate text-ellipsis">
    {{ $rating->attachments['scorings']['ai_overall_comment'] ?? '' }}
    </div>
    <div class="mt-3">
        <a href="{{ route('review.show', $rating->id) }}" class="text-blue-600 hover:underline">
            mehr lesen …
        </a>

    </div>
</div>