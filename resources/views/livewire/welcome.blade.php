<div  wire:loading.class="cursor-wait">
      <div class="container mx-auto px-4 py-2 md:py-6">
        <div id="ix1xm-2" class="relative overflow-hidden rounded-xl home-banner md:py-4">
          <div>
            <div id="ib4xx-2" data-aos="fade-right" class="max-w-full md:max-w-3xl text-center md:text-left space-y-4 md:py-6  relative h-max z-10 md:py-4">
              <div id="ijooq">
                <h1 id="ilifi-2" class="title-font sm:text-4xl text-3xl mb-4 font-medium text-white">
                  Versicherungen im Bonitäts-CHECK.
                </h1>
                <h1 id="ilifi-2-2" class="title-font text-3xl max-md:text-xl mb-4 text-white" style="text-shadow: 0px 0px 15px rgba(0,0,0,0.8);">
                  Weil du vorher wissen solltest, wie nachher
                  geregelt wird.
                </h1>
              </div>
            </div>
          </div>
          <div class="">
            <div data-aos="fade-right" class="max-w-full md:max-w-3xl text-center md:text-left space-y-4  relative h-max z-10 py-2 md:py-6">
              <div>
                <div  class="flex space-x-2 md:space-x-4 items-center md:mb-4 w-full max-md:justify-center">
                  <x-buttons.button-basic class="font-semibold" :mode="'layoutgold'" x-on:click="Livewire.dispatch('showFormModal'); isClicked = true; setTimeout(() => isClicked = false, 100)">
                    Jetzt bewerten
                  </x-buttons.button-basic>
                  <x-buttons.button-basic class="font-semibold" :mode="'layoutprimary'" href="/insurances" x-data="{ isClicked: false }" x-on:click="isClicked = true; setTimeout(() => isClicked = false, 100)" x-bind:style="isClicked ? 'transform:scale(0.9);' : ''">
                    Vergleichen
                  </x-buttons.button-basic>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <livewire:banner.top-insurances-banner  />
      <x-pagebuilder-module :position="'content_between_1'"/>
      <x-pagebuilder-module :position="'content_between_2'"/>
      <section>
        <livewire:banner.homepage-claimratings-random-banner  />
      </section>
      <livewire:customer.rating.rating-form   />
</div>
