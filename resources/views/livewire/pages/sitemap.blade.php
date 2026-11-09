@php
    $sitemapSections = [
        'Leistungs-CHECK' => [
            ['route' => 'home', 'label' => 'Home'],
            ['route' => 'insurances', 'label' => 'Versicherer'],
            ['route' => 'reviews', 'label' => 'Bewertungen'],
            ['route' => 'ranking', 'label' => 'Ranking'],
        ],
        'Informationen' => [
            ['route' => 'guidance', 'label' => 'Ratgeber'],
            ['route' => 'faqs', 'label' => 'Häufige Fragen'],
            ['route' => 'howto', 'label' => 'So funktioniert’s'],
            ['route' => 'aboutus', 'label' => 'Über uns'],
            ['route' => 'abos', 'label' => 'Abonnements'],
            ['route' => 'contact', 'label' => 'Kontakt'],
        ],
        'Rechtliches' => [
            ['route' => 'terms', 'label' => 'AGB'],
            ['route' => 'privacypolicy', 'label' => 'Datenschutz'],
            ['route' => 'imprint', 'label' => 'Impressum'],
        ],
    ];
@endphp
<div class="app-page app-sitemap-page" wire:loading.class="cursor-wait">
    <div class="app-shell grid gap-6 md:grid-cols-3">
        @foreach($sitemapSections as $section => $links)
            <section class="app-panel">
                <h2 class="app-heading !text-xl">{{ $section }}</h2>
                <ul class="mt-5 divide-y divide-[var(--line)]">
                    @foreach($links as $link)
                        <li><a href="{{ route($link['route']) }}" wire:navigate class="flex min-h-12 items-center justify-between gap-3 py-3 text-sm text-[var(--ink)] transition hover:text-[var(--accent)]"><span>{{ $link['label'] }}</span><span class="text-[var(--accent)]" aria-hidden="true">↗</span></a></li>
                    @endforeach
                    @if($section === 'Informationen' && \App\Models\Setting::enabled('webcontent', 'news_enabled', false))
                        <li><a href="{{ route('news.index') }}" wire:navigate class="flex min-h-12 items-center justify-between gap-3 py-3 text-sm text-[var(--ink)] transition hover:text-[var(--accent)]"><span>News</span><span class="text-[var(--accent)]" aria-hidden="true">↗</span></a></li>
                    @endif
                    @if($section === 'Informationen' && \App\Models\Setting::enabled('webcontent', 'blog_enabled', false))
                        <li><a href="{{ route('blog.index') }}" wire:navigate class="flex min-h-12 items-center justify-between gap-3 py-3 text-sm text-[var(--ink)] transition hover:text-[var(--accent)]"><span>Blog</span><span class="text-[var(--accent)]" aria-hidden="true">↗</span></a></li>
                    @endif
                </ul>
            </section>
        @endforeach
    </div>
</div>