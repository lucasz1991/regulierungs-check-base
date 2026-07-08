@php
    $images = $post->newsImages();
    $layout = in_array($post->layout, \App\Models\Post::NEWS_LAYOUTS, true) ? $post->layout : 'image_top';
@endphp

<div class="container mx-auto px-4 pb-12">
    <article class="space-y-8">
        <header class="rounded-2xl border border-white/10 bg-white/20 p-6 shadow-2xl backdrop-blur-xl md:p-8">
            <a href="{{ route('news.index') }}" wire:navigate class="mb-4 inline-flex items-center gap-2 text-sm font-medium text-white/80 hover:text-white">
                <i class="fal fa-arrow-left"></i>
                Zurück zu News
            </a>

            <p class="text-sm text-white/80">{{ $post->published_at->format('d.m.Y') }}</p>
            <h1 class="mt-3 text-2xl font-semibold leading-tight text-white md:text-4xl">
                {!! $post->title !!}
            </h1>

            @if($post->excerpt)
                <p class="mt-4 max-w-3xl text-base leading-relaxed text-white/85 md:text-lg">
                    {{ $post->excerpt }}
                </p>
            @endif
        </header>

        @if($layout === 'image_top')
            @if(count($images))
                <x-news.media :images="$images" :title="$post->title" />
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
    </article>
</div>
