<div class="bg-white p-4 rounded shadow  h-full" >
    <div>

    </div>
    <div class="flex justify-between items-center">
        <x-insurance.insurance-rating-stars :score="$rating->rating_score" />
        <div class="text-sm text-gray-500">
             {{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}
        </div>
    </div>
    <div class="mt-2 text-gray-800">
        {{ substr( $rating->attachments['scorings']['ai_overall_comment'], 0 ,90) }} ...
    </div>
    <div class="mt-3">
        <a href="" class="text-blue-600">mehr lesen ...</a>
    </div>
</div>