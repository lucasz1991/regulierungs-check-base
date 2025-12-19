<div class="">
    <div class="container mx-auto px-4  pb-8 space-y-8">

        {{-- Kategorie-Filter (nur UI optimiert) --}}
        <div class="flex gap-2 flex-wrap mb-4">

            <button
                wire:click="$set('selectedCategory', null)"
                class="
                    inline-flex items-center justify-center
                    px-4 py-2 rounded-full text-sm font-semibold
                    border transition
                    {{ $selectedCategory === null
                            ? 'bg-rcgold text-white border-rcgold-dark shadow-md'
                        : 'bg-white/95 text-gray-800 border-white/20 hover:bg-white hover:shadow-sm'
                    }}
                "
            >
                Alle Kategorien
            </button>

            @foreach ($categories as $category)
                <button
                    wire:click="$set('selectedCategory', {{ $category->id }})"
                    class="
                        inline-flex items-center justify-center
                        px-4 py-2 rounded-full text-sm font-medium
                        border transition
                        {{ $selectedCategory === $category->id
                            ? 'bg-rcgold text-white border-rcgold-dark shadow-md'
                            : 'bg-white/95 text-gray-800 border-white/20 hover:bg-white hover:shadow-sm'
                        }}
                    "
                >
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        @php
            $column1von2 = [];
            $column2von2 = [];

            $column1von3 = [];
            $column2von3 = [];
            $column3von3 = [];

            foreach ($posts as $index => $post) {
                if ($index % 2 === 0) $column1von2[] = $post;
                else $column2von2[] = $post;

                $columnIndex = $index % 3;
                if ($columnIndex === 0) $column1von3[] = $post;
                elseif ($columnIndex === 1) $column2von3[] = $post;
                else $column3von3[] = $post;
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
            {{-- Info-Kasten (neu im Stil) --}}
            <div class="rounded-xl border border-white/10 bg-white/5 backdrop-blur px-5 py-4">
                <p class="text-sm text-blue-100/80">Derzeit sind keine Beiträge verfügbar.</p>
            </div>
        @endif

        {{-- Lazy-Loading Button (neu im Stil) --}}
        @if($posts->hasMorePages())
            <div class="text-center mt-8">
                <button
                    wire:click="loadMore"
                    class="
                        inline-flex items-center justify-center
                        px-6 py-3 rounded-xl font-medium
                        bg-blue-600 text-white
                        hover:bg-blue-500 transition
                        shadow-lg shadow-blue-600/20
                        disabled:opacity-50 disabled:cursor-not-allowed
                    "
                >
                    Weitere laden
                </button>
            </div>
        @endif

    </div>
</div>
