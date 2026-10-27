<div wire:loading.class="cursor-wait" x-data data-homepage-content>
@section('title', 'Erfahrungen zur Schadenregulierung')
<section aria-labelledby="hero-title" class="hero" id="start">
    <div class="container hero-grid">
        <div class="hero-copy">
            <p class="eyebrow">Regulierungs-CHECK</p>
            <h1 id="hero-title"><span class="hero-title-line">Du hattest schon mal</span> <span class="hero-title-line hero-title-accent">einen Schadenfall?</span></h1>
            <p class="hero-subtitle">Hilf dabei, <em>Transparenz</em> zu schaffen.</p>
            <p class="hero-lead">Damit jeder schon vor Vertragsabschluss weiß, was ihn im Schadenfall erwartet.</p>
            <div class="hero-actions">
                <button class="btn btn-primary" type="button" x-on:click="Livewire.dispatch('showFormModal')">Erfahrung teilen <span aria-hidden="true" class="button-icon">↗</span></button>
                <a class="text-link" href="#versicherer">Versicherer entdecken <span aria-hidden="true">→</span></a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-art-stage">
                <div aria-hidden="true" class="hero-orbit"></div>
                <img alt="" class="hero-art" fetchpriority="high" height="757" src="{{ asset('site-images/start_illu6.png') }}" width="665"/>
            </div>
            @if($ratedInsurerCount > 0)
                <p class="hero-count">
                    <span aria-hidden="true" class="hero-count-icon"><svg aria-hidden="true" class="nav-icon" fill="currentColor" focusable="false" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10H3v10h4V10zm7-6h-4v16h4V4zm7 10h-4v6h4v-6z"></path></svg></span>
                    <span class="hero-count-copy"><span class="hero-count-label">Ø-Regulierungsdauer von</span><strong><span class="hero-count-number">{{ $ratedInsurerCount }}</span> Versicherern</strong></span>
                </p>
            @endif
        </div>
    </div>
</section>
    <livewire:banner.homepage-news-teaser-banner />
    <livewire:banner.top-insurances-by-type-banner />
    <livewire:banner.homepage-claimratings-random-banner />
<section aria-labelledby="process-title" class="process-section" id="ablauf"><div class="container process-layout"><div class="process-intro" data-reveal=""><span class="section-label">Erfahrung teilen</span><h2 id="process-title">So funktioniert’s</h2><button class="btn btn-primary" type="button" x-on:click="Livewire.dispatch('showFormModal')">Jetzt bewerten <span aria-hidden="true" class="button-icon">↗</span></button></div><div class="steps"><article data-reveal=""><span class="step-number">01</span><div><h3>Versicherung auswählen</h3><p>Wähle die Versicherungsgesellschaft aus, über die du deine Erfahrungen teilen möchtest.</p></div></article><article data-reveal=""><span class="step-number">02</span><div><h3>Regulierung bewerten</h3><p>Bewerte, wie dein Schadenfall abgewickelt wurde – zum Beispiel Bearbeitungsdauer, Kommunikation oder Fairness.</p></div></article><article data-reveal=""><span class="step-number">03</span><div><h3>Erfahrungen teilen</h3><p>Deine Bewertung ist jetzt online und hilft anderen dabei, eine faire Versicherung zu finden.</p></div></article></div></div></section>
    @php
        $middleModules = collect(['content_between_1', 'content_between_2'])
            ->map(fn ($position) => new \App\View\Components\PagebuilderModule(position: $position));
        $hasMiddleContent = $middleModules->contains(fn ($module) => $module->modules->isNotEmpty());
        $hasSecondMiddleContent = $middleModules->last()->modules->isNotEmpty();
        $hasFooterContent = (new \App\View\Components\PagebuilderModule(position: 'footer'))->modules->isNotEmpty();
    @endphp
    @if($hasMiddleContent)
        @foreach($middleModules as $middleModule)
            @if($middleModule->modules->isNotEmpty())
                @include('components.pagebuilder-module', ['position' => $middleModule->position, 'modules' => $middleModule->modules, 'homepageModuleContext' => 'content'])
            @endif
        @endforeach
    @else
        <x-homepage.about />
    @endif
    @if(!$hasSecondMiddleContent && !$hasFooterContent)
        <x-homepage.newsletter />
    @endif
    <livewire:customer.rating.rating-form />
</div>
