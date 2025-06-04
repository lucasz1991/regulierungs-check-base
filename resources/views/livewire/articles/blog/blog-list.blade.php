<div class="max-w-5xl mx-auto px-4 space-y-8">
    <h1 class="text-3xl font-bold mb-6">Aktuelle Blogartikel</h1>

    {{-- Kategorie-Filter --}}
    <div class="flex gap-2 flex-wrap mb-4">
        <button wire:click="$set('selectedCategory', null)"
                class="px-3 py-1 border rounded {{ $selectedCategory === null ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">
            Alle Kategorien
        </button>
        @foreach ($categories as $category)
            <button wire:click="$set('selectedCategory', {{ $category->id }})"
                    class="px-3 py-1 border rounded {{ $selectedCategory === $category->id ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    {{-- Beitragliste --}}
    @forelse ($posts as $post)
        <a href="" class="block border-b pb-6 hover:bg-gray-50 rounded">
            @if($post->cover_image)
                <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="w-full h-52 object-cover rounded mb-3">
            @endif
            <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
            <p class="text-sm text-gray-500">{{ $post->published_at->format('d.m.Y') }}</p>
            <p class="mt-2 text-gray-700">{{ $post->excerpt_preview }}</p>
        </a>
    @empty
        <p class="text-gray-500">Derzeit sind keine Beiträge verfügbar.</p>
    @endforelse

    {{-- Lazy-Loading Button --}}
    @if($posts->hasMorePages())
        <div class="text-center mt-8">
            <button wire:click="loadMore" class="px-6 py-2 bg-blue-600 text-white rounded">
                Weitere laden
            </button>
        </div>
    @endif
</div>
