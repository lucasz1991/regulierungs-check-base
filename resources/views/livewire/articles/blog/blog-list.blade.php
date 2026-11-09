<div class="app-page app-blog-page">
    <div class="app-shell">
        <div class="mb-8 flex flex-wrap gap-2" role="group" aria-label="Beiträge nach Kategorie filtern">
            <button type="button" wire:click="$set('selectedCategory', null)" aria-pressed="{{ $selectedCategory === null ? 'true' : 'false' }}" class="app-category-filter inline-flex min-h-11 items-center justify-center rounded-full border px-5 py-2 text-sm font-medium transition {{ $selectedCategory === null ? 'border-[var(--accent)] bg-[var(--surface-soft)] text-[var(--accent)]' : 'border-[var(--line)] bg-[var(--surface)] text-[var(--muted)] hover:text-[var(--ink)]' }}">Alle Kategorien</button>
            @foreach($categories as $category)
                <button type="button" wire:key="blog-category-{{ $category->id }}" wire:click="$set('selectedCategory', {{ $category->id }})" aria-pressed="{{ (int) $selectedCategory === (int) $category->id ? 'true' : 'false' }}" class="app-category-filter inline-flex min-h-11 items-center justify-center rounded-full border px-5 py-2 text-sm font-medium transition {{ (int) $selectedCategory === (int) $category->id ? 'border-[var(--accent)] bg-[var(--surface-soft)] text-[var(--accent)]' : 'border-[var(--line)] bg-[var(--surface)] text-[var(--muted)] hover:text-[var(--ink)]' }}">{{ $category->name }}</button>
            @endforeach
        </div>

        @if($posts->count())
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach($posts as $post)
                    <div class="min-w-0" wire:key="blog-post-{{ $post->id }}"><x-blog.blog-card :post="$post" /></div>
                @endforeach
            </div>
        @else
            <div class="app-panel py-10 text-center"><p class="app-muted text-sm">Derzeit sind keine Beiträge verfügbar.</p></div>
        @endif

        @if($posts->hasMorePages())
            <div class="mt-8 text-center">
                <button type="button" wire:click="loadMore" wire:loading.attr="disabled" class="app-button-secondary">Weitere laden <span aria-hidden="true">↓</span></button>
            </div>
        @endif
    </div>
</div>