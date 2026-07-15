<section class="min-h-screen w-full bg-white">
    <div class="container mx-auto px-3 pb-12 pt-3 sm:pb-16 sm:pt-5">
        <div class="w-full">
        @if($isAdminPreview)
            <x-news.admin-preview-notice class="mb-4 sm:mb-5" />
        @endif

        <header class="mb-5 border-b border-slate-200 px-2 py-5 sm:mb-7 sm:px-3 sm:py-7 lg:flex lg:items-end lg:justify-between lg:gap-10">
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
            <div class="rounded-[1.5rem] border border-slate-200 bg-white px-5 py-8 text-center shadow-[0_10px_30px_-24px_rgba(15,23,42,0.5)] sm:px-8">
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
                    class="group inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-primary shadow-[0_8px_24px_-18px_rgba(15,23,42,0.5)] transition duration-300 hover:-translate-y-0.5 hover:border-secondary hover:bg-secondary hover:text-white disabled:pointer-events-none disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="loadMore">Weitere News laden</span>
                    <span wire:loading wire:target="loadMore">News werden geladen</span>
                    <i class="fal fa-arrow-down text-xs transition-transform duration-300 group-hover:translate-y-0.5" aria-hidden="true"></i>
                </button>
            </div>
        @endif
        </div>
    </div>
</section>
