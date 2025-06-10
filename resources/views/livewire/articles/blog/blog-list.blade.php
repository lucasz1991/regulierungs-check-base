<div class="bg-gray-100">
    <div class="container mx-auto px-6 md:px-12  py-12 space-y-8">
    
        {{-- Kategorie-Filter --}}
        <div class="flex gap-2 flex-wrap mb-4">
            <button wire:click="$set('selectedCategory', null)"
                    class="px-3 py-1 border rounded {{ $selectedCategory === null ? 'bg-primary text-white' : 'bg-white text-gray-700' }}">
                Alle Kategorien
            </button>
            @foreach ($categories as $category)
                <button wire:click="$set('selectedCategory', {{ $category->id }})"
                        class="px-3 py-1 border rounded {{ $selectedCategory === $category->id ? 'bg-primary text-white' : 'bg-white text-gray-700' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
            @php
                // Für 2-Spalten-Layout
                $column1von2 = [];
                $column2von2 = [];

                // Für 3-Spalten-Layout
                $column1von3 = [];
                $column2von3 = [];
                $column3von3 = [];

                foreach ($posts as $index => $post) {
                    // 2-Spalten-Aufteilung
                    if ($index % 2 === 0) {
                        $column1von2[] = $post;
                    } else {
                        $column2von2[] = $post;
                    }

                    // 3-Spalten-Aufteilung
                    $columnIndex = $index % 3;
                    if ($columnIndex === 0) {
                        $column1von3[] = $post;
                    } elseif ($columnIndex === 1) {
                        $column2von3[] = $post;
                    } else {
                        $column3von3[] = $post;
                    }
                }
            @endphp

            @if($posts->count())
                {{-- 3 Spalten auf XL --}}
                <div class="hidden xl:grid grid-cols-3 gap-6">
                    @foreach([ $column1von3, $column2von3, $column3von3 ] as $column)
                        <div class="space-y-6">
                            @foreach($column as $post)
                                <x-blog.blog-card :post="$post" />
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{-- 2 Spalten auf MD bis XL --}}
                <div class="hidden md:grid xl:hidden grid-cols-2 gap-6">
                    @foreach([ $column1von2, $column2von2 ] as $column)
                        <div class="space-y-6">
                            @foreach($column as $post)
                                <x-blog.blog-card :post="$post" />
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{-- 1 Spalte auf Mobil --}}
                <div class="md:hidden space-y-6">
                    @foreach($posts as $post)
                        <x-blog.blog-card :post="$post" />
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Derzeit sind keine Beiträge verfügbar.</p>
            @endif

    
        {{-- Lazy-Loading Button --}}
        @if($posts->hasMorePages())
            <div class="text-center mt-8">
                <button wire:click="loadMore" class="px-6 py-2 bg-blue-600 text-white rounded">
                    Weitere laden
                </button>
            </div>
        @endif
    </div>
</div>
