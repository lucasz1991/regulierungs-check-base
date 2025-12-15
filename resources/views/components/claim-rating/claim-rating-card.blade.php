<div class="bg-white rounded-lg shadow-xl  border  border-gray-300 h-full" >
    <div class=" p-2 pb-0">
        <div class="flex justify-between items-start">
            <div class="">
                <x-user.public-info :user="$rating->user" context="ratings" />
                <x-insurance.insurance-rating-stars :score="$rating->rating_score" />
            </div>
            <div class="text-sm text-gray-500">
                  {{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}
            </div>
        </div>
    </div>
    @php
        use Illuminate\Support\Str;
        use App\Support\Text;

        // Robust aus JSON holen + UTF-8 normalisieren
        $comment = (string) data_get($rating->attachments, 'scorings.ai_overall_comment', '');
        $comment = Text::utf8($comment);

        // Multibyte-sicher kürzen
        $commentShort = Str::limit($comment, 100, '…');
    @endphp

    <div class="py-2 px-3">
        <div class=" text-gray-800 text-sm h-16 overflow-hidden line-clamp-3">
            {{ $comment }}
        </div>
    </div>
    <div class=" px-2 py-2 border-t border-gray-300 ">
        @if(!str_contains(request()->path(), 'insurance/'))
            <div class=" cursor-pointer ">
                <x-insurance.insurance-name-button :insurance="$rating->insurance" />
            </div>
        @endif
@if(!str_contains(request()->path(), 'insurancetype/'))
  @php
    $bgColor     = $rating->insuranceSubType->style['bg_color'] ?? '#f3f4f6';
    $fontColor   = $rating->insuranceSubType->style['font_color'] ?? '#4d4d4d';
    $borderColor = $rating->insuranceSubType->style['border_color'] ?? '#4d4d4d';
  @endphp

  <a
    href="/insurancetype/{{ $rating->insuranceSubType->slug }}"
    class="block w-full text-center text-xs font-medium px-2.5 py-0.5 rounded border opacity-80 hover:opacity-100 transition-opacity duration-200 truncate"
    style="background-color: {{ $bgColor }}; color: {{ $fontColor }}; border-color: {{ $borderColor }};"
    title="{{ $rating->insuranceSubType->name }}"
  >
    {{ $rating->insuranceSubType->name }}
  </a>
@endif
    </div>
    <div class="px-2 pb-1 flex justify-end">
        <a  href="{{ route('review.show', $rating->id) }}" class="text-blue-800 bg-gray-100 hover:bg-blue-800 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 text-center inline-flex items-center ">
            ansehen&nbsp;
            <svg class="rtl:rotate-180 w-2.5 h-2.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
             <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>
    </div>
</div>