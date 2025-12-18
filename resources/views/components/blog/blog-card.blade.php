<a href="{{ route('post.show', $post->slug) }}"
   wire:navigate
   class="
        group block overflow-hidden rounded-2xl
        bg-white/95 
        shadow-md transition
        hover:shadow-xl 
   "
>
@if($post->cover_image)
    <div class="relative overflow-hidden">
        <img
            src="{{ $post->cover_image_url }}"
            alt="{{ $post->title }}"
            loading="lazy"
            class="
                w-full h-48 md:h-56 object-cover
                transition-transform duration-500 ease-out
                group-hover:scale-[1.1]
                group-hover:rotate-[0.9deg]
            "
        />

        <!-- Overlay bleibt ruhig -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-black/0 to-black/0 pointer-events-none"></div>
    </div>
@endif

    <div class="p-4 md:p-5">

        <!-- Titel -->
        <h2 class="text-lg md:text-xl font-semibold text-gray-900 leading-snug">
            {!! $post->title !!}
        </h2>

        <!-- Meta -->
        <div class="mt-3 pt-3 border-t border-gray-200/80 flex items-center justify-between gap-3">
            <p class="text-xs md:text-sm text-gray-500">
                {{ $post->published_at->format('d.m.Y') }}
            </p>

            <div class="flex items-center gap-2">
                <!-- Kommentare -->
                <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 text-gray-700 px-2.5 py-1 text-xs">
<i class="fal fa-comment text-gray-400"></i>
                    <span>{{ $post->comments_count() ?? 0 }}</span>
                </span>

                <!-- Likes -->
                <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 text-gray-700 px-2.5 py-1 text-xs">
<i class="fal fa-thumbs-up text-gray-400"></i>
                    <span>{{ $post->likes_count() ?? 0 }}</span>
                </span>
            </div>
        </div>

        <!-- Excerpt -->
        <p class="mt-3 text-sm md:text-base text-gray-700 leading-relaxed line-clamp-3">
            {{ $post->excerpt_preview }}
        </p>

        <!-- Mehr lesen CTA -->
        <div class="mt-5 flex justify-end">
            <span
                class="
                    inline-flex items-center gap-2
                    rounded-full px-4 py-2
                    text-sm font-medium
                    text-blue-600
                    bg-blue-50
                    transition
                    group-hover:bg-blue-600
                    group-hover:text-white
                "
            >
                Mehr lesen
<i class="fal fa-arrow-right text-xs transition-transform group-hover:translate-x-0.5"></i>
            </span>
        </div>
    </div>
</a>
