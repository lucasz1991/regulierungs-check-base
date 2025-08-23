<div class="">
    <div class="container mx-auto px-4 py-12 space-y-4">
        <h1 class="text-3xl font-bold hyphens-auto">{!! $post->title !!}</h1>
        <p class="text-gray-500 text-sm">{{ $post->published_at->format('d.m.Y') }}</p>
        <livewire:likes.like-button
            :likeable-type="\App\Models\Post::class"
            :likeable-id="$post->id"
            size="lg"
            :wire:key="'like-post-'.$post->id"/>
        <div class="">
            <div class="prose max-w-none mt-6 md:mt-0 md:pr-4 md:py-2">
                @if($post->cover_image)
                    <img 
                        src="{{ $post->cover_image_url }}" 
                        alt="{{ $post->title }}" 
                        class="w-full md:w-1/2 lg:w-1/3  md:float-right md:ml-8 md:mb-4 mb-6 h-auto max-h-full object-cover rounded">
                @endif
                {!! $post->body !!}
            </div>
        </div>
    </div>
    <div class="bg-gray-50 mt-12">
        <div class="container mx-auto px-4 py-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-500">Kommentare</h2>
            <livewire:comments.comment-thread :commentable-type="\App\Models\Post::class" :commentable-id="$post->id" :depth="1" />
        </div>
    </div>
</div>
