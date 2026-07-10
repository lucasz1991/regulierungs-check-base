<div wire:loading.class="cursor-wait">
      <div class="container mx-auto px-4 py-2 md:py-6 max-md:pt-6  mb-8">
        <div  class="relative overflow-hidden rounded-xl home-banner md:py-4 md:pt-20">
          {{-- Mobile: Illustration dezent im Hintergrund rechts, mit Overlay für gute Lesbarkeit --}}
          <div class="md:hidden absolute inset-y-0 right-0 w-2/3 pointer-events-none" aria-hidden="true">
            <img class="absolute right-[-10%] top-1/2 -translate-y-1/2 max-h-[90%] opacity-40" src="{{ asset('/site-images/start_illu6.png') }}" alt="">
            <div class="absolute inset-0 bg-gradient-to-r from-primary via-primary/70 to-primary/20"></div>
          </div>

          <div class=" flex items-center justify-between">
            <div class="w-full md:w-1/2 relative z-10"  data-aos="fade-up">
              <div   class="max-w-full md:max-w-3xl text-left space-y-4 relative h-max z-10 md:py-4">
                <div id="ijooq">
                  <h1 id="ilifi-2" class="title-font sm:text-4xl text-3xl mb-4 font-medium text-white">
                    Du hattest schon mal einen <span class="text-teal-300">Schadenfall?</span>
                  </h1>
                  <h1 id="ilifi-2-2" class="title-font text-3xl max-md:text-xl mb-4 text-white" style="text-shadow: 0px 0px 15px rgba(0,0,0,0.8);">
                    Hilf dabei, <span class="text-teal-300">Transparenz</span> zu schaffen.
                  </h1>
                  <p class="leading-relaxed text-white/90 text-sm md:text-base" style="text-shadow: 0px 0px 10px rgba(0,0,0,0.7);">
                    Damit jeder schon vor Vertragsabschluss weiß, was ihn im Schadenfall erwartet. </p>
                </div>
              </div>
              <div class="">
                <div class="max-w-full md:max-w-3xl text-left space-y-3 relative h-max z-10 py-2 md:py-6">
                  @if($ratedInsurerCount > 0)
                    <p class="flex items-center gap-2 text-sm text-white/90" style="text-shadow: 0px 0px 10px rgba(0,0,0,0.7);">
                      <svg class="h-4 w-4 shrink-0 text-teal-300" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                      Ø-Regulierungsdauer von <span class="font-semibold text-teal-300">{{ $ratedInsurerCount }} Versicherern</span>
                    </p>
                  @endif

                  <div  class="flex flex-wrap gap-2 md:gap-4 items-center md:mb-4 w-full ">
                    <x-buttons.button-basic class="font-semibold" :mode="'layoutgold'" x-on:click="Livewire.dispatch('showFormModal'); isClicked = true; setTimeout(() => isClicked = false, 100)">
                      Erfahrung teilen
                    </x-buttons.button-basic>

                    <a href="{{ route('news.index') }}" wire:navigate class="inline-flex items-center gap-2 rounded-lg border border-white/40 bg-white/10 px-4 py-2 text-base font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">
                      News &amp; Urteile
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>
                    </a>
                  </div>

                  {{-- Avatare mit leichter Überlappung + Hinweis --}}
                  <div class="flex flex-wrap items-center gap-3 pt-2">
                    <div class="flex items-center -space-x-2">
                      <span class="inline-flex h-9 w-9 items-center justify-center overflow-hidden rounded-full ring-2 ring-white/80 bg-[#f3d9c6]">
                        <svg viewBox="0 0 36 36" class="h-full w-full"><rect width="36" height="36" fill="#e8f4f2"/><circle cx="18" cy="30" r="10" fill="#0f766e"/><circle cx="18" cy="14" r="7" fill="#f3d9c6"/><path d="M10 13c0-6 4-9 8-9s8 3 8 9c0 2-1 3-1 3 0-5-3-7-7-7s-7 2-7 7c0 0-1-1-1-3z" fill="#5b3a29"/></svg>
                      </span>
                      <span class="inline-flex h-9 w-9 items-center justify-center overflow-hidden rounded-full ring-2 ring-white/80 bg-[#e7c1a0]">
                        <svg viewBox="0 0 36 36" class="h-full w-full"><rect width="36" height="36" fill="#eef2f8"/><circle cx="18" cy="30" r="10" fill="#1e3a8a"/><circle cx="18" cy="14" r="7" fill="#e7c1a0"/><path d="M11 12c0-5 3-8 7-8s7 3 7 8c0 1-.5 2-.5 2 0-4-2.5-6-6.5-6s-6.5 2-6.5 6c0 0-.5-1-.5-2z" fill="#2b2b2b"/></svg>
                      </span>
                      <span class="inline-flex h-9 w-9 items-center justify-center overflow-hidden rounded-full ring-2 ring-white/80 bg-[#f5dcc4]">
                        <svg viewBox="0 0 36 36" class="h-full w-full"><rect width="36" height="36" fill="#f3ede8"/><circle cx="18" cy="30" r="10" fill="#7c3aed"/><circle cx="18" cy="14" r="7" fill="#f5dcc4"/><path d="M9 16c0-8 5-11 9-11s9 3 9 11c0 0-2-2-3-5-1 2-9 3-12 0-1 3-3 5-3 5z" fill="#d9a441"/></svg>
                      </span>
                      <span class="inline-flex h-9 w-9 items-center justify-center overflow-hidden rounded-full ring-2 ring-white/80 bg-[#d9b08c]">
                        <svg viewBox="0 0 36 36" class="h-full w-full"><rect width="36" height="36" fill="#eaf2ee"/><circle cx="18" cy="30" r="10" fill="#111827"/><circle cx="18" cy="14" r="7" fill="#d9b08c"/><path d="M11 12c0-5 3-8 7-8s7 3 7 8c0 1-.5 2-.5 2 0-4-2.5-6-6.5-6s-6.5 2-6.5 6c0 0-.5-1-.5-2z" fill="#3f3f3f"/><rect x="11" y="12" width="6" height="4" rx="2" fill="none" stroke="#374151" stroke-width="1.2"/><rect x="19" y="12" width="6" height="4" rx="2" fill="none" stroke="#374151" stroke-width="1.2"/><path d="M17 13.5h2" stroke="#374151" stroke-width="1.2"/></svg>
                      </span>
                      <span class="inline-flex h-9 w-9 items-center justify-center rounded-full ring-2 ring-white/80 bg-white/20 text-white backdrop-blur-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
                      </span>
                    </div>

                    <svg class="h-5 w-5 shrink-0 text-teal-300" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16c6 2 12 0 15-6"></path><path d="M19 10l1-4-4 1"></path></svg>

                    <p class="text-sm text-white/90" style="text-shadow: 0px 0px 10px rgba(0,0,0,0.7);">
                      Gemeinsam besser entscheiden.<br class="hidden sm:block">
                      Für mehr <span class="font-semibold text-teal-300">Klarheit</span> im Ernstfall.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="hidden md:flex w-1/2 justify-center text-center p-2 md:p-6" data-aos="fade-up" data-aos-delay="150">
              <img class="max-w-full md:max-w-[330px]" src="{{ asset('/site-images/start_illu6.png') }}" alt="">
            </div>
          </div>
        </div>
      </div>
      <div class="">
        <div class="mb-2" data-aos="fade-up" data-aos-delay="500">
          <livewire:banner.top-insurances-by-type-banner />
        </div>
        <section  class="mb-6" data-aos="fade-up" data-aos-delay="500">
          <livewire:banner.homepage-claimratings-random-banner  />
        </section>
        <section class="mb-6" data-aos="fade-up" data-aos-delay="500">
          <livewire:banner.homepage-news-teaser-banner />
        </section>
      </div>
      <div class="container mx-auto  px-2 md:px-4 py-2 md:py-4"  data-aos="fade-up" data-aos-delay="200">
        <div class="max-md:bg-rcgold text-white max-md:px-2 max-md:py-2 text-base  md:text-xl md:w-max uppercase md:border-b-2  md:border-secondary md:pb-1">
          <span>So funktioniert's</span>
        </div>
      </div>

      <div class="container mx-auto px-2 py-2 md:py-6 mb-4"  data-aos="fade-up"  data-aos-delay="300">
        <div class="grid grid-cols-3 gap-4">
          <a href="{{ route('howto') }}" class="flex flex-col items-center text-center bg-white/80 hover:bg-white rounded-xl shadow-md px-4 py-5 md:px-6 md:py-6">
            <div class="mb-2 flex items-center justify-center rounded-full bg-secondary-light text-white p-3">
              <svg  class="h-6 w-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" id="iu3mvh" class="w-5 h-5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>
            <h3 class="text-xs md:text-base font-semibold text-gray-900">
              Versicherung auswählen
            </h3>
            <p class="mt-1 hidden md:block text-xs md:text-sm text-gray-600">
              Wähle die Versicherungsgesellschaft aus, über die du deine Erfahrungen teilen möchtest.
            </p>
          </a>
          <a href="{{ route('howto') }}" class="flex flex-col items-center text-center bg-white/80 hover:bg-white rounded-xl shadow-md px-4 py-5 md:px-6 md:py-6">
            <div class="mb-2 flex  items-center justify-center rounded-full bg-secondary-light text-white p-3">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" id="ifhv0i" class="w-5 h-5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
            </div>
            <h3 class="text-xs md:text-base font-semibold text-gray-900">
              Regulierung bewerten
            </h3>
            <p class="mt-1 hidden md:block text-xs md:text-sm text-gray-600">
              Bewerte, wie dein Schadenfall abgewickelt wurde – zum Beispiel Bearbeitungsdauer, Kommunikation oder Fairness.
            </p>
          </a>
          <a href="{{ route('howto') }}" class="flex flex-col items-center text-center bg-white/80 hover:bg-white rounded-xl shadow-md px-4 py-5 md:px-6 md:py-6">
            <div class="mb-2 flex  items-center justify-center rounded-full bg-rcgold text-white p-3">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path><path d="M22 4L12 14.01l-3-3"></path></svg>
            </div>
            <h3 class="text-xs md:text-base font-semibold text-gray-900">
              Erfahrungen teilen
            </h3>
            <p class="mt-1 hidden md:block text-xs md:text-sm text-gray-600">
              Deine Bewertung ist jetzt online und hilft anderen dabei, eine faire Versicherung zu finden.            
            </p>
          </a>
        </div>
      </div>
      <div>
        <x-pagebuilder-module :position="'content_between_1'"/>
      </div>
      <div>
        <x-pagebuilder-module :position="'content_between_2'"/>
      </div>
      <livewire:customer.rating.rating-form   />
</div>
