<div class="container mx-auto px-3 pb-12 pt-3 sm:px-5 sm:pb-16 sm:pt-5 lg:px-8">
    <div class="mx-auto max-w-6xl">
        @if($isAdminPreview)
            <x-news.admin-preview-notice class="mb-4 sm:mb-5" />
        @endif

        <header class="mb-5 overflow-hidden rounded-[1.75rem] border border-white/20 bg-white/95 px-5 py-6 shadow-[0_20px_55px_-32px_rgba(15,23,42,0.65)] sm:mb-7 sm:px-8 sm:py-8 lg:flex lg:items-end lg:justify-between lg:gap-10 lg:px-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Aktuelles</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">News</h1>
            </div>

            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-600 sm:text-base lg:mt-0 lg:text-right">
                Urteile, Marktdaten und Ratgeber rund um Versicherungen und Schadenregulierung.
            </p>
        </header>

        @if($posts->count())
            <div class="space-y-3 sm:space-y-5">
                @foreach($posts as $post)
                    <x-news.card :post="$post" />
                @endforeach
            </div>
        @else
            <div class="rounded-[1.5rem] border border-white/20 bg-white/95 px-5 py-8 text-center shadow-lg sm:px-8">
                <span class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-full bg-secondary/10 text-secondary">
                    <i class="fal fa-newspaper text-lg" aria-hidden="true"></i>
                </span>
                <p class="mt-3 text-sm font-medium text-slate-600">Derzeit sind keine News verfügbar.</p>
            </div>
        @endif

        @if($posts->hasMorePages())
            <div class="mt-7 text-center sm:mt-9">
                <button
                    type="button"
                    wire:click="loadMore"
                    wire:loading.attr="disabled"
                    class="group inline-flex items-center justify-center gap-2 rounded-full border border-white/40 bg-white px-6 py-3 text-sm font-semibold text-primary shadow-lg shadow-slate-950/10 transition duration-300 hover:-translate-y-0.5 hover:bg-secondary hover:text-white disabled:pointer-events-none disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="loadMore">Weitere News laden</span>
                    <span wire:loading wire:target="loadMore">News werden geladen</span>
                    <i class="fal fa-arrow-down text-xs transition-transform duration-300 group-hover:translate-y-0.5" aria-hidden="true"></i>
                </button>
            </div>
        @endif
    </div>
</div>
