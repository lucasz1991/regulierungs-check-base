<article class="app-page app-blog-detail">
    <div class="app-shell app-stack">
        <header class="mx-auto w-full max-w-4xl">
            <p class="app-muted mb-4 text-sm"><time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('d.m.Y') }}</time></p>
            <h1 class="text-balance text-3xl font-semibold leading-tight tracking-tight text-[var(--ink)] sm:text-4xl lg:text-5xl">{{ $post->title }}</h1>
            <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-b border-[var(--line)] pb-6">
                <span class="app-muted inline-flex items-center gap-2 text-sm">
                    <svg class="h-4 w-4 text-[var(--accent)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2" /><path stroke-linecap="round" d="M7 3v4m10-4v4M3 11h18" /></svg>
                    Veröffentlicht
                </span>
                <livewire:likes.like-button :likeable-type="\App\Models\Post::class" :likeable-id="$post->id" size="lg" :wire:key="'like-post-'.$post->id" />
            </div>
        </header>

        <div class="mx-auto w-full max-w-4xl overflow-hidden rounded-2xl border border-[var(--line)] bg-[var(--surface)]">
            @if($post->cover_image)
                <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="max-h-[480px] w-full object-cover" loading="lazy" />
            @endif
            <div class="p-6 sm:p-9 lg:p-12">
                <div class="blog-content app-prose prose w-full !max-w-full">{!! \App\Support\BlogHtmlSanitizer::sanitize((string) $post->body) !!}</div>
            </div>
        </div>

        <section class="app-panel mx-auto w-full max-w-4xl">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3 border-b border-[var(--line)] pb-5">
                <h2 class="app-heading">Kommentare</h2>
                <span class="rounded-full bg-[var(--surface-soft)] px-3 py-1.5 text-xs font-medium text-[var(--muted)]">Diskussion</span>
            </div>
            <livewire:comments.comment-thread :commentable-type="\App\Models\Post::class" :commentable-id="$post->id" :depth="1" />
        </section>
    </div>
</article>