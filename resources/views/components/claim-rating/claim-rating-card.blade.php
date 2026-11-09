@php
    use Illuminate\Support\Str;
    use App\Support\Text;
    $comment = Text::utf8((string) data_get($rating->attachments, 'scorings.ai_overall_comment', ''));
    $commentShort = Str::limit($comment, 100, '…');
@endphp

<article class="app-review-card flex h-full flex-col rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-5 text-[var(--ink)] [&_.text-gray-800]:text-[var(--ink)]">
    <header class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <x-user.public-info :user="$rating->user" context="ratings" />
            <div class="mt-3 flex justify-start"><x-insurance.insurance-rating-stars :score="$rating->rating_score" /></div>
        </div>
        <time class="shrink-0 text-[10px] text-[var(--muted)]" datetime="{{ \Carbon\Carbon::parse($rating->created_at)->toDateString() }}">{{ \Carbon\Carbon::parse($rating->created_at)->format('d.m.Y') }}</time>
    </header>
    <a href="{{ route('review.show', $rating->id) }}" class="my-5 block text-sm leading-relaxed text-[var(--ink)] hover:text-[var(--accent)]">
        <span class="line-clamp-3 min-h-[4.5rem]">{{ $comment }}</span>
        <span class="mt-4 inline-flex min-h-10 items-center gap-3 text-xs font-semibold text-[var(--accent)]">Bewertung ansehen <span aria-hidden="true">↗</span></span>
    </a>
    <footer class="mt-auto space-y-3 border-t border-[var(--line)] pt-4">
        @if(!str_contains(request()->path(), 'insurance/'))
            <div class="flex justify-start"><x-insurance.insurance-name-button :insurance="$rating->insurance" /></div>
        @endif
        @if(!str_contains(request()->path(), 'insurancetype/'))
            @php
                $bgColor = $rating->insuranceSubType->style['bg_color'] ?? '#f3f4f6';
                $fontColor = $rating->insuranceSubType->style['font_color'] ?? '#4d4d4d';
                $borderColor = $rating->insuranceSubType->style['border_color'] ?? '#4d4d4d';
            @endphp
            <a href="/insurancetype/{{ $rating->insuranceSubType->slug }}"
                class="inline-flex max-w-full items-center rounded-md border px-2.5 py-1.5 text-[10px] font-medium leading-relaxed"
                style="background-color: {{ $bgColor }}; color: {{ $fontColor }}; border-color: {{ $borderColor }};"
                title="{{ $rating->insuranceSubType->name }}">{{ $rating->insuranceSubType->name }}</a>
        @endif
    </footer>
</article>