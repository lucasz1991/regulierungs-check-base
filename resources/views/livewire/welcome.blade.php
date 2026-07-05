<div wire:loading.class="cursor-wait">
      <div class="container mx-auto px-4 py-2 md:py-6 max-md:pt-6  mb-8">
        <div  class="relative overflow-hidden rounded-xl home-banner md:py-4 md:pt-20">
          <div class=" flex items-center justify-between">
            <div class="w-2/3 md:w-1/2"  data-aos="fade-up">
              <div   class="max-w-full md:max-w-3xl text-left space-y-4 relative h-max z-10 md:py-4">
                <div id="ijooq">
                  <h1 id="ilifi-2" class="title-font sm:text-4xl text-3xl mb-4 font-medium text-white">
                    Du hattest schon mal einen Schadenfall?
                  </h1>
                  <h1 id="ilifi-2-2" class="title-font text-3xl max-md:text-xl mb-4 text-white" style="text-shadow: 0px 0px 15px rgba(0,0,0,0.8);">
                    Hilf dabei, Transparenz zu schaffen.
                  </h1>
                  <p class="leading-relaxed text-white/90 text-sm md:text-base" style="text-shadow: 0px 0px 10px rgba(0,0,0,0.7);">
                    Damit jeder schon vor Vertragsabschluss weiß, was ihn im Schadenfall erwartet. </p>
                </div>
              </div>
              <div class="">
                <div class="max-w-full md:max-w-3xl text-center md:text-left space-y-4  relative h-max z-10 py-2 md:py-6">
                  <div>
                    <div  class="flex space-x-2 md:space-x-4 items-center md:mb-4 w-full ">
                      <x-buttons.button-basic class="font-semibold" :mode="'layoutgold'" x-on:click="Livewire.dispatch('showFormModal'); isClicked = true; setTimeout(() => isClicked = false, 100)">
                        Erfahrung teilen
                      </x-buttons.button-basic>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="w-1/3 md:w-1/2 block md:flex justify-center text-center p-2 md:p-6" data-aos="fade-up" data-aos-delay="150">
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
