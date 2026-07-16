<div wire:loading.class="cursor-wait">
      <div class="container mx-auto px-4 pt-6">
        <div  class="relative  rounded-xl home-banner md:pt-20">
          {{-- Mobile: Illustration dezent rechts ohne Hintergrund-Overlay --}}
          <div class="md:hidden absolute inset-y-0 right-0 w-2/3 pointer-events-none" aria-hidden="true">
            <img class="absolute right-[-10%] top-1/2 -translate-y-1/2 max-h-[90%] opacity-50" src="{{ asset('/site-images/start_illu6.png') }}" alt="">
          </div>

          <div class=" flex items-center justify-between">
            <div class="w-full md:w-1/2 relative z-10"  data-aos="fade-up">
              <div   class="max-w-full md:max-w-3xl text-left space-y-4 relative h-max z-10 md:py-4">
                <div id="ijooq">
                  <h1 id="ilifi-2" class="title-font sm:text-4xl text-3xl mb-4 font-medium text-white">
                    Du hattest schon mal einen <span class="text-rcgold">Schadenfall?</span>
                  </h1>
                  <h1 id="ilifi-2-2" class="title-font text-3xl max-md:text-xl mb-4 text-white" style="text-shadow: 0px 0px 15px rgba(0,0,0,0.8);">
                    Hilf dabei, <span class="text-rcgold">Transparenz</span> zu schaffen.
                  </h1>
                  <p class="leading-relaxed text-white/90 text-sm md:text-base" style="text-shadow: 0px 0px 10px rgba(0,0,0,0.7);">
                    Damit jeder schon vor Vertragsabschluss weiß, was ihn im Schadenfall erwartet.
                  </p>
                </div>
              </div>
              <div class="">
                <div class="max-w-full md:max-w-3xl text-left space-y-3 relative h-max z-10 py-2 md:py-6">
                  @if($ratedInsurerCount > 0)
                    <div class="h-0.5 w-12 rounded-full bg-rcgold/80" aria-hidden="true"></div>
                    <p class="flex items-center gap-2 text-sm text-white/90" style="text-shadow: 0px 0px 10px rgba(0,0,0,0.7);">
                      Ø-Regulierungsdauer von <span class="font-semibold text-rcgold">{{ $ratedInsurerCount }} Versicherern</span>
                    </p>
                  @endif

                  <div  class="flex flex-wrap gap-2 md:gap-4 items-center md:mb-4 w-full ">
                    <x-buttons.button-basic class="group font-semibold" :mode="'layoutsecondary'" x-on:click="Livewire.dispatch('showFormModal'); isClicked = true; setTimeout(() => isClicked = false, 100)">
                      <span>Erfahrung teilen</span>
                      <svg class="ml-2 h-4 w-4 transition-transform duration-300 ease-out group-hover:translate-x-1" aria-hidden="true" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 12h14"></path>
                        <path d="m13 6 6 6-6 6"></path>
                      </svg>
                    </x-buttons.button-basic>

                  </div>

                  {{-- Avatare mit leichter Überlappung + Hinweis --}}
                  <div class="flex flex-wrap items-center gap-3 pt-2">
                    <div class="flex items-center -space-x-2">
                      <span class="inline-flex h-9 w-9 items-center justify-center overflow-hidden rounded-full ring-2 ring-white/80">
                        <img src="{{ asset('/site-images/home/avatars/avatar-brunette.png') }}" alt="" class="h-full w-full object-cover">
                      </span>
                      <span class="inline-flex h-9 w-9 items-center justify-center overflow-hidden rounded-full ring-2 ring-white/80">
                        <img src="{{ asset('/site-images/home/avatars/avatar-bearded.png') }}" alt="" class="h-full w-full object-cover">
                      </span>
                      <span class="inline-flex h-9 w-9 items-center justify-center overflow-hidden rounded-full ring-2 ring-white/80">
                        <img src="{{ asset('/site-images/home/avatars/avatar-blonde.png') }}" alt="" class="h-full w-full object-cover">
                      </span>
                      <span class="inline-flex h-9 w-9 items-center justify-center overflow-hidden rounded-full ring-2 ring-white/80">
                        <img src="{{ asset('/site-images/home/avatars/avatar-glasses.png') }}" alt="" class="h-full w-full object-cover">
                      </span>
                      <span class="inline-flex h-9 w-9 items-center justify-center rounded-full ring-2 ring-white/80 bg-white/20 text-white backdrop-blur-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
                      </span>
                    </div>

                    <svg class="h-5 w-5 shrink-0 text-rcgold" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16c6 2 12 0 15-6"></path><path d="M19 10l1-4-4 1"></path></svg>

                    <p class="text-sm text-white/90" style="text-shadow: 0px 0px 10px rgba(0,0,0,0.7);">
                      …und viele weitere.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="hidden md:flex w-1/2 justify-center text-center p-2 md:p-6" data-aos="fade-up" data-aos-delay="150">
              <img class="max-w-full md:max-w-[330px]" src="{{ asset('/site-images/start_illu6.png') }}" alt="">
            </div>
          </div>

          <livewire:banner.homepage-news-teaser-banner />
        </div>
      </div>
      <div class="">
        <div class="mb-2" data-aos="fade-up" data-aos-delay="500">
          <livewire:banner.top-insurances-by-type-banner />
        </div>
        <section  class="mb-6" data-aos="fade-up" data-aos-delay="500">
          <livewire:banner.homepage-claimratings-random-banner  />
        </section>
      </div>
      <div class="container mx-auto  px-2 md:px-4 py-2 md:py-4"  data-aos="fade-up" data-aos-delay="200">
        <div class="max-md:bg-rcgold text-white max-md:px-2 max-md:py-2 text-base  md:text-xl md:w-max uppercase md:border-b-2  md:border-primary md:pb-1">
          <span>So funktioniert's</span>
        </div>
      </div>

      <div class="container mx-auto px-2 py-2 md:py-6 mb-4"  data-aos="fade-up"  data-aos-delay="300">
        <div class="grid grid-cols-3 gap-4">
          <a href="{{ route('howto') }}" class="flex flex-col items-center text-center bg-white/80 hover:bg-white rounded-xl shadow-md px-4 py-5 md:px-6 md:py-6">
            <div class="mb-2 flex items-center justify-center rounded-full bg-primary-light text-white p-3">
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
            <div class="mb-2 flex  items-center justify-center rounded-full bg-primary-light text-white p-3">
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
