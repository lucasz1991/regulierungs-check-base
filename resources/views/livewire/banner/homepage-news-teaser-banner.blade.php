<div>
    @if($newsEnabled && $posts->isNotEmpty())
        <section class="container mx-auto px-2 md:px-4 py-3 md:py-5">
            <div class="overflow-hidden rounded-2xl bg-white/95 shadow-xl">
                <div class="flex flex-col gap-4 p-4 md:flex-row md:items-center md:justify-between md:p-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-rcgold">News</p>
                        <h2 class="mt-1 text-xl font-semibold text-gray-900 md:text-2xl">Aktuelles vom Regulierungs-Check</h2>
                    </div>

                    <a href="{{ route('news.index') }}" wire:navigate class="inline-flex w-max items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Alle News
                        <i class="fal fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 border-t border-gray-100 md:grid-cols-3 md:divide-x md:divide-gray-100 divide-y divide-gray-100 md:divide-y-0">
                    @foreach($posts as $post)
                        @php
                            $image = $post->firstNewsImage();
                            $category = $post->newsCategory;
                        @endphp
                        <a href="{{ route('news.show', $post) }}" wire:navigate class="group relative flex items-center gap-4 p-4 transition hover:bg-blue-50/70 md:flex-col md:items-stretch md:p-5">
                            @if($image)
                                <img
                                    src="{{ $image['url'] }}"
                                    alt="{{ $image['alt'] ?? $post->title }}"
                                    class="h-24 w-24 shrink-0 rounded-xl object-cover md:h-36 md:w-full"
                                    loading="lazy"
                                >
                            @endif

                            <div class="min-w-0 flex-1 pr-6 md:pr-0">
                                @if($category)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold text-white"
                                        style="background-color: {{ $category->color }};"
                                    >
                                        @if($category->icon)
                                            <i class="fal {{ $category->icon }} text-[10px]"></i>
                                        @endif
                                        {{ $category->name }}
                                    </span>
                                @endif

                                <h3 class="mt-1.5 line-clamp-2 text-sm font-semibold leading-snug text-gray-900 md:text-base">
                                    {!! $post->title !!}
                                </h3>
                                <p class="mt-1.5 line-clamp-2 text-xs leading-relaxed text-gray-600 md:text-sm">
                                    {{ $post->excerpt_preview }}
                                </p>

                                <div class="mt-2.5 flex items-center gap-2 text-xs text-gray-500">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fal fa-clock text-[10px]"></i>
                                        {{ $post->reading_time_minutes }} Min.
                                    </span>
                                    <span aria-hidden="true">&middot;</span>
                                    <span>{{ $post->published_at->format('d.m.Y') }}</span>
                                </div>
                            </div>

                            <i class="fal fa-chevron-right absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 transition group-hover:translate-x-0.5 group-hover:text-blue-600 md:hidden"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
