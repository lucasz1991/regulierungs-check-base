<div class="container mx-auto px-4 pb-10">
    <div class="mb-8 rounded-2xl border border-white/10 bg-white/20 p-6 shadow-2xl backdrop-blur-xl md:p-8">
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-100">Aktuelles</p>
        <h1 class="mt-2 text-2xl font-semibold text-white md:text-3xl">News</h1>
    </div>

    @if($posts->count())
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($posts as $post)
                <x-news.card :post="$post" />
            @endforeach
        </div>
    @else
        <div class="rounded-xl border border-white/10 bg-white/5 px-5 py-4 backdrop-blur">
            <p class="text-sm text-blue-100/80">Derzeit sind keine News verfügbar.</p>
        </div>
    @endif

    @if($posts->hasMorePages())
        <div class="mt-8 text-center">
            <button
                wire:click="loadMore"
                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 font-medium text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-500"
            >
                Weitere laden
            </button>
        </div>
    @endif
</div>
