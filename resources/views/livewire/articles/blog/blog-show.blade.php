<div class="min-h-screen ">

    <div class="container mx-auto px-4 pb-12 space-y-8 ">

        {{-- Header Card --}}
        <div class="rounded-2xl border border-white/10 bg-white/20 backdrop-blur-xl shadow-2xl p-6 md:p-8">
            <p class="text-sm text-white mb-3">
                {{ $post->published_at->format('d.m.Y') }}
            </p>

            <h1 class="text-xl md:text-2xl font-semibold text-white leading-tight hyphens-auto">
                {!! $post->title !!}
            </h1>

            {{-- Actions Row --}}
            <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="inline-flex items-center gap-2 text-sm text-white">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/20 px-3 py-1">
                        <i class="fal fa-calendar-alt text-white"></i>
                        <span>Veröffentlicht</span>
                    </span>
                </div>

                {{-- Like Button in einer hellen kleinen Card --}}
                <div class="rounded-xl bg-white/95 border border-white/10 shadow p-2">
                    <livewire:likes.like-button
                        :likeable-type="\App\Models\Post::class"
                        :likeable-id="$post->id"
                        size="lg"
                        :wire:key="'like-post-'.$post->id"
                    />
                </div>
            </div>
        </div>

        {{-- Content Card --}}
        <div class="rounded-2xl bg-white/95  shadow-xl overflow-hidden">
            @if($post->cover_image)
                <div class="relative overflow-hidden">
                    <img
                        src="{{ $post->cover_image_url }}"
                        alt="{{ $post->title }}"
                        class="w-full max-h-[420px] object-cover"
                        loading="lazy"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-black/0 to-black/0 pointer-events-none"></div>
                </div>
            @endif

            <div class="p-6 md:p-8">
                {{-- Prose --}}
                <div class="prose prose-lg max-w-none prose-headings:scroll-mt-24 prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline">
                    {!! $post->body !!}
                </div>
            </div>
        </div>

        {{-- Kommentare --}}
        <div class="rounded-2xl border border-white/10 bg-white/20 backdrop-blur-xl shadow-2xl overflow-hidden">
            <div class="px-6 md:px-8 py-5 flex items-center justify-between border-b border-white/10">
                <h2 class="text-lg md:text-xl font-semibold text-white">
                    Kommentare
                </h2>

                <span class="text-xs text-white rounded-full bg-white/20 border border-white/10 px-3 py-1">
                    Diskussion
                </span>
            </div>

            <div class="p-4 md:p-6 bg-white/95">
                <livewire:comments.comment-thread
                    :commentable-type="\App\Models\Post::class"
                    :commentable-id="$post->id"
                    :depth="1"
                />
            </div>
        </div>

    </div>
</div>
