<a href="{{ route('post.show', $post->slug) }}" wire:navigate class="app-blog-card group flex h-full min-w-0 flex-col overflow-hidden rounded-2xl border border-[var(--line)] bg-[var(--surface)] transition duration-300 hover:-translate-y-1 hover:border-[var(--accent)]">
    @if($post->cover_image)
        <div class="relative aspect-[16/10] overflow-hidden bg-[var(--surface-soft)]">
            <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.04]" />
        </div>
    @endif
    <div class="flex flex-1 flex-col p-5 sm:p-6">
        <h2 class="!m-0 !text-xl !font-semibold !leading-snug text-[var(--ink)]">{{ $post->title }}</h2>
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-[var(--line)] pt-4 text-xs text-[var(--muted)]">
            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('d.m.Y') }}</time>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.5 8.5 0 0 1-8.5 8.5H4l-1 1V12.5A8.5 8.5 0 0 1 11.5 4H12a9 9 0 0 1 9 7.5Z" /></svg><span class="sr-only">Kommentare:</span>{{ $post->comments_count() ?? 0 }}</span>
                <span class="inline-flex items-center gap-1.5"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 10v11H3V10h4Zm0 10h10a2 2 0 0 0 2-1.5l2-7A2 2 0 0 0 19 9h-6l1-5a2 2 0 0 0-3-1L7 10" /></svg><span class="sr-only">Likes:</span>{{ $post->likes_count() ?? 0 }}</span>
            </div>
        </div>
        <p class="app-muted mt-4 line-clamp-3 text-sm leading-relaxed">{{ $post->excerpt_preview }}</p>
        <div class="mt-auto flex items-center justify-between pt-6 text-sm font-semibold text-[var(--accent)]"><span>Mehr lesen</span><span class="flex h-9 w-9 items-center justify-center rounded-full bg-[var(--surface-soft)] transition-transform group-hover:translate-x-1" aria-hidden="true">↗</span></div>
    </div>
</a>