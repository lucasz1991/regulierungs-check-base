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

                <div class="grid grid-cols-1 gap-0 border-t border-gray-100 md:grid-cols-3 md:divide-x md:divide-gray-100">
                    @foreach($posts as $post)
                        @php($image = $post->firstNewsImage())
                        <a href="{{ route('news.show', $post) }}" wire:navigate class="group flex gap-4 p-4 transition hover:bg-blue-50/70 md:flex-col md:p-5">
                            @if($image)
                                <img
                                    src="{{ $image['url'] }}"
                                    alt="{{ $image['alt'] ?? $post->title }}"
                                    class="h-20 w-24 shrink-0 rounded-xl object-cover md:h-36 md:w-full"
                                    loading="lazy"
                                >
                            @endif

                            <div class="min-w-0">
                                <p class="text-xs text-gray-500">{{ $post->published_at->format('d.m.Y') }}</p>
                                <h3 class="mt-1 line-clamp-2 text-sm font-semibold leading-snug text-gray-900 md:text-base">
                                    {!! $post->title !!}
                                </h3>
                                <p class="mt-2 hidden text-sm leading-relaxed text-gray-600 md:line-clamp-2">
                                    {{ $post->excerpt_preview }}
                                </p>
                                <span class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-blue-600 group-hover:text-blue-800">
                                    Lesen
                                    <i class="fal fa-arrow-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
