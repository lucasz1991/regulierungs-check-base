<div  wire:loading.class="cursor-wait">
      <x-ui.basic.content-container class=" mb-4">

        <div id="ix1xm-2" class="relative overflow-hidden rounded-xl home-banner px-4 py-4">
          <div id="iy0ef-2-3" class="absolute inset-0 " style="background: url('/site-images/startbanner-img.jpg') center center / cover no-repeat;">
            <div class="absolute inset-0 bg-black/10"></div>
  
          </div>
          <div id="i52c7-2" class="container mx-auto">
            <div id="ib4xx-2" data-aos="fade-right" class="max-w-full md:max-w-3xl text-center md:text-left space-y-4 md:py-6  relative h-max z-10 py-4">
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
          
          <div  class="container mx-auto">
            <div  data-aos="fade-right" class="max-w-full md:max-w-3xl text-center md:text-left space-y-4 md:py-6  relative h-max z-10 py-6">
              <div >
                <div  class="flex space-x-2 md:space-x-4 items-center mb-4 w-full max-md:justify-center">
                  <button type="button" x-data="{ isClicked: false }" x-on:click="Livewire.dispatch('showFormModal'); isClicked = true; setTimeout(() => isClicked = false, 100)" x-bind:style="isClicked ? 'transform:scale(0.9);' : ''"  class="text-white bg-primary hover:bg-primary-700 focus:ring-primary-300 border-primary-600 px-3 md:px-4 py-2 text-base transition-all duration-100 inline-flex items-center justify-center text-center border rounded-lg focus:ring-4">
                    Jetzt bewerten
                  </button>
                      <a href="/insurances" x-data="{ isClicked: false }" x-on:click="isClicked = true; setTimeout(() => isClicked = false, 100)" x-bind:style="isClicked ? 'transform:scale(0.9);' : ''"  class="text-white bg-secondary hover:bg-seconday-700 focus:ring-seconday-300 border-secondary-600 px-3 md:px-4 py-2 text-base transition-all duration-100 inline-flex items-center justify-center text-center border rounded-lg focus:ring-4">
                        Vergleichen
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <livewire:banner.top-insurances-banner  />
              
        </div>
      </x-ui.basic.content-container>

      <x-pagebuilder-module :position="'content_between_1'"/>
      <section>
      </section>
      <x-pagebuilder-module :position="'content_between_2'"/>
      <section>
        <livewire:banner.homepage-claimratings-random-banner  />
        </section>
      <livewire:customer.rating.rating-form   />
</div>
