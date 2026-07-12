@php
    $images = $post->newsImages();
    $heroImage = $images[0] ?? null;
    $category = $post->newsCategory;
    $project = $post->pagebuilderProject;
    $layout = in_array($post->layout, \App\Models\Post::NEWS_LAYOUTS, true) ? $post->layout : 'image_top';
@endphp

<div class="min-h-screen w-full bg-white text-gray-900">
    <div class="container mx-auto px-4 pb-12">
        <article class="space-y-6">
        <a href="{{ route('news.index') }}" wire:navigate class="inline-flex items-center gap-2 pt-4 text-sm font-medium text-teal-700 hover:text-teal-900">
            <i class="fal fa-arrow-left"></i>
            Zurück zu News
        </a>

        {{-- Hero mit Bild, Kategorie-Badge und Datum --}}
        <header class="overflow-hidden rounded-2xl bg-white/95 shadow-xl">
            @if($heroImage)
                <div class="relative">
                    <img
                        src="{{ $heroImage['url'] }}"
                        alt="{{ $heroImage['alt'] ?? $post->title }}"
                        class="h-56 w-full object-cover md:h-80"
                    >
                    <div class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-3 bg-gradient-to-t from-black/70 to-transparent px-4 pb-3 pt-10 md:px-6">
                        @if($category)
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold text-white"
                                style="background-color: {{ $category->color }};"
                            >
                                @if($category->icon)
                                    <i class="fal {{ $category->icon }} text-[11px]"></i>
                                @endif
                                {{ $category->name }}
                            </span>
                        @endif
                        <span class="text-xs font-medium text-white/90">{{ $post->published_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            @endif

            <div class="p-5 md:p-8">
                @if(!$heroImage)
                    <div class="mb-3 flex items-center gap-3">
                        @if($category)
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold text-white"
                                style="background-color: {{ $category->color }};"
                            >
                                {{ $category->name }}
                            </span>
                        @endif
                        <span class="text-xs text-gray-500">{{ $post->published_at->format('d.m.Y') }}</span>
                    </div>
                @endif

                <h1 class="text-2xl font-semibold leading-tight text-gray-900 md:text-4xl">
                    {!! $post->title !!}
                </h1>

                @if($post->excerpt)
                    <p class="mt-3 max-w-3xl text-base leading-relaxed text-gray-600 md:text-lg">
                        {{ $post->excerpt }}
                    </p>
                @endif

                <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-gray-100 pt-4 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1.5">
                        <i class="fal fa-clock"></i>
                        {{ $post->reading_time_minutes }} Min. Lesezeit
                    </span>
                    @if($category)
                        <span aria-hidden="true">&middot;</span>
                        <span>{{ $category->name }}</span>
                    @endif
                </div>
            </div>
        </header>

        {{-- Inhalt: Page Builder, Fallback auf klassisches Layout --}}
        @if($project && $project->cleaned_html)
            <div class="overflow-hidden rounded-2xl bg-white/95 shadow-xl" wire:ignore>
                <div id="news-pagebuilder-{{ $project->id }}" class="news-pagebuilder-content">
                    {!! $project->cleaned_html !!}
                    <style>{!! $project->css !!}</style>
                    <script>{!! $project->js !!}</script>
                </div>
            </div>
        @else
            @if($layout === 'image_top')
                @if(count($images) > 1)
                    <x-news.media :images="array_slice($images, 1)" :title="$post->title" />
                @endif

                <div class="rounded-2xl bg-white/95 p-5 shadow-xl md:p-8">
                    <div class="default-format-text blog-content w-full">
                        {!! $post->body !!}
                    </div>
                </div>
            @elseif($layout === 'image_bottom')
                <div class="rounded-2xl bg-white/95 p-5 shadow-xl md:p-8">
                    <div class="default-format-text blog-content w-full">
                        {!! $post->body !!}
                    </div>
                </div>

                @if(count($images))
                    <x-news.media :images="$images" :title="$post->title" />
                @endif
            @elseif($layout === 'image_left')
                <div class="grid gap-6 md:grid-cols-2 md:items-start">
                    @if(count($images))
                        <x-news.media :images="$images" :title="$post->title" />
                    @endif

                    <div class="rounded-2xl bg-white/95 p-5 shadow-xl md:p-8">
                        <div class="default-format-text blog-content w-full">
                            {!! $post->body !!}
                        </div>
                    </div>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 md:items-start">
                    <div class="rounded-2xl bg-white/95 p-5 shadow-xl md:p-8">
                        <div class="default-format-text blog-content w-full">
                            {!! $post->body !!}
                        </div>
                    </div>

                    @if(count($images))
                        <x-news.media :images="$images" :title="$post->title" />
                    @endif
                </div>
            @endif
        @endif

        {{-- Ähnliche Themen --}}
        @if($relatedPosts->isNotEmpty())
            <section class="rounded-2xl bg-white/95 p-5 shadow-xl md:p-8">
                <h2 class="text-lg font-semibold text-gray-900 md:text-xl">Ähnliche Themen</h2>

                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                    @foreach($relatedPosts as $related)
                        @php
                            $relatedImage = $related->firstNewsImage();
                            $relatedCategory = $related->newsCategory;
                        @endphp
                        <a href="{{ route('news.show', $related) }}" wire:navigate class="group flex items-center gap-3 rounded-xl border border-gray-100 p-3 transition hover:border-blue-200 hover:bg-blue-50/60 md:flex-col md:items-stretch">
                            @if($relatedImage)
                                <img
                                    src="{{ $relatedImage['url'] }}"
                                    alt="{{ $relatedImage['alt'] ?? $related->title }}"
                                    class="h-16 w-20 shrink-0 rounded-lg object-cover md:h-28 md:w-full"
                                    loading="lazy"
                                >
                            @endif

                            <div class="min-w-0">
                                @if($relatedCategory)
                                    <span
                                        class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold text-white"
                                        style="background-color: {{ $relatedCategory->color }};"
                                    >
                                        {{ $relatedCategory->name }}
                                    </span>
                                @endif
                                <h3 class="mt-1 line-clamp-2 text-sm font-semibold leading-snug text-gray-900 group-hover:text-blue-700">
                                    {!! $related->title !!}
                                </h3>
                                <p class="mt-1 text-xs text-gray-500">{{ $related->published_at->format('d.m.Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
        </article>
    </div>
</div>
